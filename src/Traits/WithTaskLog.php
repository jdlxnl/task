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
    protected $log_id;
    protected $log;

    /**
     * Set the log that we will be using
     * for retries. This is currently not working
     * as we do not want to create a tasklogID
     * at task creation time
     */
    public function startLog()
    {

    }

    public function getLog()
    {
        if (!empty($this->log_id) && empty($this->log)) {
            $this->log = TaskLog::find($this->log_id);
        }

        if (empty($log_id) || empty($this->log)) {
            $this->log = new TaskLog();
            $this->log->save();
            $this->log_id = $this->log->id;
        }
        return $this->log;
    }
}
