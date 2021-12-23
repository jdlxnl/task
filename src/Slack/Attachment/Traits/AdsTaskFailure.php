<?php

namespace Jdlx\Task\Slack\Attachment\Traits;

use Jdlx\Task\Models\TaskLog;

trait AdsTaskFailure
{
    /**
     * @var TaskLog
     */
    protected $log;

    public function withTaskLog(TaskLog $log)
    {
        $this->log = $log;
        $base = config('app.url');
        return $this
            ->setField('TaskLog ID', $log->id)
            ->setField('Started', $log->created_at)
            ->setField('Log', "{$base}/horizon/tasklogs/{$log->id}");
    }

    protected function getLog()
    {
        $entries = $this->log->messagesForPrint();

        $messages = [];
        foreach ($entries as $index => $entry) {
            $message = $entry["message"];
            if ($entry["isLast"]) {
                $message = $this->colorByState($message, $entry["state"]);
            }
            $messages[] = $message;
        }
        return implode("\n", $messages);
    }

    protected function colorByState($text, $state)
    {
        switch ($state) {
            case TaskLog::COMPLETED:
                return ":white_check_mark:{$text}";
            case TaskLog::FAILED:
                return ":boom:{$text}:boom:";
            default:
                return ":clock1:{$text}";
        }
    }

}
