<?php

namespace Jdlx\Task\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jdlx\Task\Generated\TaskLogEntry\TaskLogEntryFields;
use Jdlx\Task\Generated\TaskLogEntry\WithTaskLogEntryCrudFields;

class TaskLogEntry extends Model
{
    use HasFactory;
    use WithTaskLogEntryCrudFields;

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
    protected $fillable = TaskLogEntryFields::FILLABLE;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = TaskLogEntryFields::HIDDEN;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = TaskLogEntryFields::CASTS;
}
