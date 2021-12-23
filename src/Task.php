<?php


namespace Jdlx\Task;


use Jdlx\Task\Models\TaskLog;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Log;

abstract class Task
{
    /**
     * @var Job|null
     */
    protected $job = '';

    /**
     * @var TaskLog
     */
    protected $log;

    /**
     * @var string
     */
    protected $taskType = '';


    public function __construct(TaskLog $log = null)
    {
        $this->log = $log;
        if(is_null($this->log)){
            $this->log = new TaskLog();
            $this->log->save();
        }
    }

    /**
     * @throws \Exception
     */
    public function run($catch = false)
    {
        try {
            $this->initialize();
            if ($this->log->entries() === 0) {
                throw new \Exception("The TaskLog should be initialized in the tasks initialize method");
            }

            $this->doRun();
            return true;
        } catch (\Exception $e) {
            $this->log->errored($e->getMessage(), $e);
            if($catch) {
                return true;
            }else { // Rethrow the exception so the task runner can fail.
                throw $e;
            }
        }
    }

    /**
     * Initialize the taskLog with an appropriate message
     * and get ready to perform the actual task at hand.
     *
     * @return mixed
     */
    abstract protected function initialize();

    /**
     * Entry point for running the task
     *
     * @return mixed
     */
    abstract protected function doRun();

    /**
     * @return TaskLog
     */
    public function getLog(): TaskLog
    {
        return $this->log;
    }
}
