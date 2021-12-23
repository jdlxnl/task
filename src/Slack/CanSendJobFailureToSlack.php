<?php


namespace Jdlx\Task\Slack;


use Jdlx\Slack\Notifications\SlackNotification;
use Jdlx\Slack\Sender;
use Jdlx\Slack\Slack;
use Jdlx\Task\Slack\Attachment\TaskFailure;
use Jdlx\Task\Models\TaskLog;
use Illuminate\Contracts\Queue\Job;

trait CanSendJobFailureToSlack
{
    protected function sendToSlack($title, ?Job $job, TaskLog $log)
    {
        $builder = (new TaskFailure())->withJob($job)->withTaskLog($log);

        Slack::channel()->notify(
            new SlackNotification(
                $title,
                Sender::persona(Sender::TASK_RUNNER),
                $builder->toSlackAttachment()
            ));
    }
}
