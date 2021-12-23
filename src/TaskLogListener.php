<?php


namespace Jdlx\Task;


use Jdlx\Task\Models\TaskLogEntry;

interface TaskLogListener
{
    function onEntry(TaskLogEntry $entry);
}
