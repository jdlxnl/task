<?php

namespace Jdlx\Task\Exception;

use Illuminate\Contracts\Queue\Job;
use Throwable;

/**
 * Encapsulates the error so we have access to the job
 */
class TaskRunnerException extends \Exception
{

    public $job;
    public $exception;

    public function __construct(Job $job, \Exception $exception, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->job = $job;
        parent::__construct($message, $code, $previous);
    }
}
