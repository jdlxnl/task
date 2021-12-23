<?php


namespace Jdlx\Task\Traits;

use Illuminate\Support\Str;
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
    //use ProgressiveBackoff;
    protected $log_id;

    /**
     * Set the log_id that we will be using
     * for retries
     */
    public function startLog()
    {
        $this->log_id = Str::uuid();
    }

    public function getLog()
    {
        $log = TaskLog::find($this->log_id);
        if (!$log) {
            $log = new TaskLog();
            $log->id = $this->log_id;
            $log->save();
        }
        return $log;
    }
}
