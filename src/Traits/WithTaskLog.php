<?php


namespace Jdlx\Task\Traits;

use Jdlx\Task\Models\TaskLog;

/**
 * Sets backoff values to 60, 120, 240
 * by default
 *
 * Trait ProgressiveBackoff
 * @package App\Library\Jobs\Traits
 */
trait WithTaskLog
{
    protected $log;

    /**
     * Set the log that we will be using
     * for retries
     */
    public function startLog()
    {
        $this->log = new TaskLog();
        $this->log->save();
    }

    public function getLog()
    {
        if (!$this->log) {
            $this->log = new TaskLog();
            $this->log->save();
        }
        return $this->log;
    }
}
