<?php

namespace Jdlx\Task\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Jdlx\Slack\Attachment\UnknownException;
use Jdlx\Slack\Notifications\SlackNotification;
use Jdlx\Slack\Sender;
use Jdlx\Slack\Slack;
use Jdlx\Task\Exception\TaskRunnerException;
use Jdlx\Task\Models\TaskLog;
use Jdlx\Task\Slack\CanSendJobFailureToSlack;
use Jdlx\Task\Traits\WithTaskLog;

/**
 * A job type that acts as a TaskRunner.
 *
 * Class TaskRunner
 * @package App\Jobs
 */
abstract class TaskRunner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use CanSendJobFailureToSlack;
    use WithTaskLog;

    /**
     * Default tries to one can be overwritten by
     * other classes
     *
     * @var int
     */
    public $tries = 1;


    public $timeout = 30;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->startLog();
    }


    /**
     * Handle is taken on by
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->runTask($this->getLog());
        } catch (\Exception $exception) {
            throw new TaskRunnerException($this->job, $exception, $this->getErrorMessage());
        }
    }

    abstract public function runTask(TaskLog $log);

    abstract public function getErrorMessage();

    public function failed($exception)
    {
        $msg = $this->getErrorMessage();
        if ($exception instanceof TaskRunnerException) {
            $log = TaskLog::find($this->log_id);
            if ($log) {
                $this->sendToSlack(
                    $msg,
                    $exception->job,
                    $log
                );
            }
        } else {
            Slack::channel()->notify(
                new SlackNotification(
                    $msg,
                    Sender::persona(Sender::TASK_RUNNER),
                    (new UnknownException($exception))->toSlackAttachment()
                ));
        }


        // Else send other message

    }
}
