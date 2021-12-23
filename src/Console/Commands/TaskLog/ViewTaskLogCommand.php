<?php

namespace Jdlx\Task\Console\Commands\TaskLog;

use Jdlx\Task\Models\TaskLog;
use Illuminate\Console\Command;

class ViewTaskLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasklog:view {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List most recent tasklogs and results';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /* @var \Jdlx\Task\Models\TaskLog $log */
        $log = TaskLog::find($this->argument("id"));

        if(!$log){
            $this->error("Order not found");
            return -1;
        }

        $record = [
            'created_at' => $log->created_at->toDateTimeString(),
            'id' => $log->id,
            'type' => $log->type,
            'entries' => $log->entries()->count(),
            'status' => $this->colorByState($log->status, $log->status),
        ];


        $this->table(['created', 'id', 'type', 'entries', 'status'], [$record]);

        $log->entries()
            ->orderBy('entry_number', 'asc')
            ->each(function ($entry) {
                $this->output->writeln($entry->created_at->toDateTimeString() . ": " . $entry->message);
                if ($entry->context) {
                    dump($entry->context);
                }
            });

        if($this->output->isVerbose()){
            dump(json_decode($log->payload));
        }

        if($this->output->isVeryVerbose()){
            dump( $log->payload);
        }

        return 0;
    }

    protected function getMessages(TaskLog $log, $lineLimit = 0)
    {
        $messages = $log->entryMessages();
        $end = sizeof($messages) - 1;
        $state = $log->status;

        if ($lineLimit > 0 && $lineLimit < $end) {
            $start = floor($lineLimit / 2);
            $end = floor($lineLimit / 2);
            $cut = $end - $lineLimit;

            $start = array_slice($messages, 0, floor($lineLimit / 2));
            $end = array_slice($messages, -1, ceil($lineLimit / 2));

            $messages = array_merge($start, [" ... $cut more"], $end);

            // recalc end
            $end = count($messages) - 1;
        }

        foreach ($messages as $index => $message) {
            $isLast = ($end !== 0) && ($index === $end);
            if ($isLast) {
                $messages[$index] = $this->colorByState($message, $state);
            }
        }
        $messages[] = "-------";
        return $messages;
    }

    protected function colorByState($text, $state)
    {
        switch ($state) {
            case TaskLog::COMPLETED:
                return "<fg=green>{$text}</>";
            case TaskLog::FAILED:
                return "<fg=red>{$text}</>";
            default:
                return "<fg=magenta>{$text}</>";
        }
    }
}
