<?php

namespace Jdlx\Task\Slack\Attachment\Traits;

use Illuminate\Contracts\Queue\Job;

trait AdsJobFailure
{
    public function withJob(?Job $job)
    {
        if (empty($job)) {
            return $this;
        }
        $base = config('app.url');
        return $this
            ->setField('Job ID', $job->getJobId())
            ->setField('Attempts', $job->attempts())
            ->setField('Job', "{$base}/horizon/failed/{$job->getJobId()}");
    }
}
