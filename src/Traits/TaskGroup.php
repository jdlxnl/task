<?php

namespace Jdlx\Task\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait TaskGroup
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tasks()
    {
        return $this->morphToMany('Jdlx\Task\Models\TaskLog', 'task_logs_relation');
    }
}
