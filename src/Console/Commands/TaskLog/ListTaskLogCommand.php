<?php

namespace Jdlx\Task\Console\Commands\TaskLog;

use Jdlx\Task\Models\TaskLog;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ListTaskLogCommand extends Command
{
    /**
     * @var OutputInterface
     */
    protected $originalOutput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasklog:list
                        {--count=10 : How many results to print}
                        {--page=1 : The page of result to show}
                        {--search= : Find text in tasklog}
                        {--type=* : Filter by types of tasks}
                        {--status=* : Task status to list NEW, IN_PROGRESS, FAILED, COMPLETED}
                        {--store_name=* : Filter list by provided store names}
                        {--store_id=* : Filter list by provided store id}
                        {--watch : Keep table open}';

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

    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->originalOutput = $output;
        return parent::run($input, $output);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $watch = $this->option('watch');

        $output = $this->originalOutput;
        if($this->originalOutput instanceof ConsoleOutput){
            $output = $this->originalOutput->section();
        }

        $this->render($output);

        while($watch){
            sleep(5);
            $output->clear();
            $this->render($output);
        }

        return 0;
    }

    protected function render(ConsoleSectionOutput $section){
        $count = $this->option('count');
        $page = $this->option('page');
        $status = $this->option('status');

        $query = TaskLog
            ::with("entries", "subjects")
            ->forPage($page, $count)
            ->orderBy("created_at", "desc");

        if (sizeof($status) > 0) {
            $query = $query->whereIn("status", $status);
        }


        $taskLogs = $query->get();
        /* @var  TaskLog $log */
        $entries = [];
        foreach ($taskLogs as $log) {
            $subjects = [];
            foreach ($log->subjects as $subject) {
                if (isset($subject->Shop_Name)) {
                    $subjects[] = $subject->Shop_Name;
                }
            }

            $entries[] = [
                'created_at' => $log->created_at->toDateTimeString(),
                'id' => $log->id,
                'type' => $log->type,
                'entries' => $log->entries()->count(),
                'log' => implode("\n", $this->getMessages($log)),
                'status' => $this->colorByState($log->status, $log->status),
            ];
            $entries[] = new TableSeparator();
        }

        // remove last separator
        array_pop($entries);

        $table = new Table($section);
        $table->setHeaders(['created', 'id', 'type', 'entries', 'log', 'status']);
        $table->setRows($entries);
        $table->setColumnMaxWidth(4, 70);
        $table->setFooterTitle($query->count() . " records");
        $table->render();
    }

    protected function getMessages(TaskLog $log)
    {
        $entries = $log->messagesForPrint();

        $messages = [];
        foreach ($entries as $index => $entry) {
            $message = $entry["message"];
            if ($entry["isLast"]) {
                $message = $this->colorByState($message, $entry["state"]);
            }
            $messages[] = $message;
        }
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
