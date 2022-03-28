<?php

namespace Jdlx\Task\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Jdlx\Task\Generated\TaskLog\TaskLogFields;
use Jdlx\Task\Generated\TaskLog\WithTaskLogCrudFields;
use Jdlx\Task\TaskLogListener;
use Psr\Log\LogLevel;

class TaskLog extends Model
{
    public const NEW = "NEW";
    public const IN_PROGRESS = "IN_PROGRESS";
    public const ERRORED = "ERRORED";
    public const FAILED = "FAILED";
    public const COMPLETED = "COMPLETED";

    use HasFactory;
    use WithTaskLogCrudFields;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = TaskLogFields::FILLABLE;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = TaskLogFields::HIDDEN;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = TaskLogFields::CASTS;

    /**
     * Logger that can listen to it's output
     *
     * @var array
     */
    protected $listeners = [];


    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::NEW,
        'type' => 'undefined',
        'step' => "1"
    ];


    /**
     * Only complete tasks.
     *
     * @param $query
     * @return mixed
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::COMPLETED);
    }

    /**
     * Only complete tasks.
     *
     * @param $query
     * @return mixed
     */
    public function scopeErrored($query)
    {
        return $query->where('status', self::ERRORED);
    }

    /**
     * Only complete tasks.
     *
     * @param $query
     * @return mixed
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::FAILED);
    }


    /**
     * Only complete tasks.
     *
     * @param $query
     * @return mixed
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', self::IN_PROGRESS);
    }

    /**
     * @return HasMany
     */
    public function entries(){
        return $this->hasMany(TaskLogEntry::class)->orderBy("entry_number");
    }

    /**
     * Initialize the task and write the opening log message
     *
     * @param string $type
     * @param array $payload
     * @param string $msg
     */
    public function initialize($type, $payload = null, $msg = null)
    {
        if ($this->status !== self::NEW) {
            if (!(empty($msg))) {
                $msg = "Resuming: " . $msg;
            }
            $this->resume($msg);
            return;
        }

        if (empty($msg)) {
            $msg = "Task initialized";
        }

        $this->type = $type;
        $this->payload = $payload;

        $this->addLog(LogLevel::INFO, $msg);
        $this->save();
    }

    /**
     * Resume the task after it has been previously closed
     * for the purpose of loggin retries.
     *
     * @param string $type
     * @param array $payload
     * @param string $msg
     */
    public function resume($msg = null, $context = null, $payload = null)
    {
        if (!empty($payload)) {
            $this->payload = $payload;
        }

        if (empty($msg)) {
            $msg = "Task resuming";
        }

        $this->status = self::IN_PROGRESS;
        $this->addLog(LogLevel::INFO, $msg, $context);
        $this->save();
    }

    /**
     * Register progress on the task. Optionally override the task
     * payload, or provide additional context the messge.
     *
     * @param string $msg The progress message
     * @param object $context Additional information as part of this step
     * @param object $payload Override the task payload
     * @return $this
     */
    public function progress($msg, $context = null, $payload = null)
    {
        if (!empty($payload)) {
            $this->payload = $payload;
        }
        $this->status = self::IN_PROGRESS;
        $this->addLog(LogLevel::INFO, $msg, $context);
        $this->save();

        return $this;
    }

    /**
     * Register the task as completed. Optionally override the task
     * payload, or provide additional context the message.
     *
     * @param string $msg The progress message
     * @param object $context Additional information as part of this step
     * @param object $payload Override the task payload
     * @return $this
     */
    public function complete($msg = null, $context = null, $payload = null)
    {
        if (!empty($payload)) {
            $this->payload = $payload;
        }

        if (empty($msg)) {
            $msg = "Task completed";
        }

        $this->status = self::COMPLETED;
        $this->addLog(LogLevel::INFO, $msg, $context);
        $this->save();

        return $this;
    }

    /**
     * Register the task as completed but failed. Set to failed
     * if the task could not be completed, but because of an expected
     * problem.
     *
     * @param string $msg The progress message
     * @param object $context Additional information as part of this step
     * @param object $payload Override the task payload
     * @return $this
     */
    public function failed($msg = null, $context = null, $payload = null)
    {
        if (!empty($payload)) {
            $this->payload = $payload;
        }

        if (empty($msg)) {
            $msg = "Task completed";
        }

        $this->status = self::FAILED;
        $this->addLog(LogLevel::WARNING, $msg, $context);
        $this->save();

        return $this;
    }

    /**
     * Register the task as errored. This means an unexpected
     * error occurred, and the tasks should be retried. This state
     * will require a immediate notice.
     *
     * @param string $msg The progress message
     * @param object $context Additional information as part of this step
     * @param object $payload Override the task payload
     * @return $this
     */
    public function errored($msg, \Exception $exception)
    {
        $msg = Str::limit($msg, 1024, "[...truncated]");
        $this->status = self::ERRORED;
        $this->addLog(LogLevel::ERROR, $msg, $exception->getTrace());
        $this->save();

        return $this;
    }

    public function onEntry(TaskLogListener $listener)
    {
        $this->listeners[] = $listener;
    }

    /**
     * Add a general log message. Normally the methods: progress(), completed(),
     * failed() should suffice.
     *
     * @param string $severity A log severity from the LogLevel constants
     * @param string $message The message to be added to the log
     * @param object $context Json Payload that helps provide more clarity
     */
    public function addLog($severity, $message, $context = null)
    {
        if (!is_null($context) && !is_string($context)) {
            $context = json_encode($context);
        }

        $entry = new TaskLogEntry([
            'entry_number' => $this->entries()->count() + 1,
            'severity' => $severity,
            'message' => $message,
            'context' => $context,
        ]);

        $this->entries()->save($entry);

        foreach ($this->listeners as $listener) {
            $listener->onEntry($entry);
        }
    }

    public function entryMessages()
    {
        $messages = [];
        $this->entries()
            ->orderBy('entry_number', 'asc')
            ->each(function ($entry) use (&$messages) {
                $messages[] = $entry->message;
            });
        return $messages;
    }

    public function messagesForPrint()
    {
        $entries = $this->entries()
            ->orderBy('entry_number', 'asc')
            ->get();

        $end = sizeof($entries) - 1;
        $state = $this->status;

        $messages = [];
        foreach ($entries as $index => $entry) {
            $isLast = ($end !== 0) && ($index === $end);
            $messages[] = [
                "message" => $entry->message,
                "type" => $entry->type,
                "isLast" => $isLast,
                "state" => $state,
                "payload" => $entry->payload
            ];
        }
        return $messages;
    }
}
