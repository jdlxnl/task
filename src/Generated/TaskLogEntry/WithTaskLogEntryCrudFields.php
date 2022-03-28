<?php

namespace Jdlx\Task\Generated\TaskLogEntry;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Jdlx\Traits\FieldDescriptor;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Jdlx\Task\Models\TaskLog;

/**
 *  @property int id
 *  @property int task_log_id
 *  @property integer entry_number
 *  @property string severity
 *  @property string message
 *  @property object context
 *  @property Carbon created_at
 *  @property Carbon updated_at
 *  @property TaskLog taskLog
 *
 *  @mixin Builder
 */
trait WithTaskLogEntryCrudFields
{
    use FieldDescriptor;

    protected static $fieldSetup = TaskLogEntryFields::ACCESS;

    /**
     * @return BelongsTo
     */
    public function taskLog(){
        return $this->belongsTo(TaskLog::class);
    }
}
