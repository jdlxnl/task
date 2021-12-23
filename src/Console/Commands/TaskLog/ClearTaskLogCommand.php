<?php

namespace Jdlx\Task\Console\Commands\TaskLog;

use Jdlx\Task\Models\TaskLog;
use Jdlx\Task\Models\TaskLogEntry;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearTaskLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasklog:clear
                        {--age=30: only remove log items [age] days and older}
                        {--all : Remove logs from all statuses, not only successful ones}
     ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear old tasklog items from the DB';

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
        $age = $this->option("age");
        $all = $this->option("all");

        // Retrieve tasklogs;
        $min_created_at = (new Carbon())->subtract("day", $age);
        $tasklogs = TaskLog::query();
        $tasklogs->where('created_at', "<", $min_created_at);
        if (!$all) {
            $tasklogs->where('status', TaskLog::COMPLETED);
        }

        // Logs
        $tasklogIds = $tasklogs->get(['id']);
        $this->info(COUNT($tasklogIds) . " task logs found");

        // Entries
        $entries = TaskLogEntry::query()->whereIn("task_log_id", $tasklogIds);
        $entryIds = $entries->get("id");
        $this->info(count($entryIds) . " entries found");

        // Associations
        $relations = DB::table('task_logs_relations')->whereIn('task_log_id', $tasklogIds);
        $this->info($relations->count() . " relations found");


        // Remove
        $relations->delete();
        DB::table('task_log_entries')->whereIn('task_log_id', $tasklogIds)->delete();
        DB::table('task_logs')->whereIn('id', $tasklogIds)->delete();
        $this->info("deleted");

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
            $end = sizeof($messages) - 1;
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
