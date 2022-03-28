<?php

namespace Jdlx\Task\Generated\TaskLog;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Jdlx\Traits\FieldDescriptor;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Database\Eloquent\Relations\MorphToMany;
use \Jdlx\Task\Models\TaskLog;
use \Jdlx\Task\Models\TaskLogEntry;

/**
 *  @property int id
 *  @property string type
 *  @property string step
 *  @property string status
 *  @property object payload
 *  @property Carbon created_at
 *  @property Carbon updated_at
 *  @property Collection entries
 *  @property Collection subjects
 *
 *  @mixin Builder
 */
trait WithTaskLogCrudFields
{
    use FieldDescriptor;

    protected static $fieldSetup = TaskLogFields::ACCESS;

    /**
     * @return HasMany
     */
    public function entries(){
        return $this->hasMany(TaskLogEntry::class);
    }

    /**
     * @return MorphToMany
     */
    public function subjects(){
        return $this->morphedByMany(TaskLog::class, "task_logs_relation");
    }
}
