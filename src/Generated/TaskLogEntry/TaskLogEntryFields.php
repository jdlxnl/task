<?php

namespace Jdlx\Task\Generated\TaskLogEntry;

use Faker\Generator;
use Jdlx\Traits\FieldDescriptor;

class TaskLogEntryFields
{
    use FieldDescriptor;

    protected static $fieldSetup = self::ACCESS;

    /**
     * Define Relationships to be used during generation
     * -----------------------------
     * "field" => ["type" => "hasOne", "target" => "\App\Models\Target", "args" => ["foreign_key", "local_key"]],
     * "field" => ["type" => "belongsTo", "target" => "\App\Models\Target", "args" => ["foreign_key", "owner_key"]]
     * "fields" => ["type" => "hasMany", "target" => "\App\Models\Target", "args" => ["foreign_key", "local_key"]],
     * "fields" => ["type" => "belongsToMany", "target" => "\App\Models\Target", "args" => ["table", "local_key", "target_key"]],
     * "fields" => ["type" => "morphTo", "target" => "morhphable"],
     * "fields" => ["type" => "morphMany", "target" => "\App\Models\Target", "args" => ["morphable", "local_key", "target_key"]],
     * "fields" => ["type" => "morphedByMany", "target" => "\App\Models\Target", "args" => ["morphable", "local_key", "target_key"]],
     * "fields" => ["type" => "morphToMany", "target" => "\App\Models\Target", "args" => ["morphable", "local_key", "target_key"]],
     */
    public const RELATIONSHIPS = [
        "taskLog" => [ "type" => "belongsTo", "target" => "\Jdlx\Task\Models\TaskLog" ],
    ];

    public const ACCESS = [
        'id' => ['readOnly', 'sortable', 'filterable'],
        'task_log_id' => ['editable', 'sortable', 'filterable'],
        'entry_number' => ['editable', 'sortable', 'filterable'],
        'severity' => ['editable', 'sortable', 'filterable'],
        'message' => ['editable', 'sortable', 'filterable'],
        'context' => ['editable', 'sortable', 'filterable'],
        'created_at' => ['readOnly', 'sortable', 'filterable'],
        'updated_at' => ['readOnly', 'sortable', 'filterable'],
    ];

    public const CASTS = [
        'context' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const FILLABLE = [
        'task_log_id',
        'entry_number',
        'severity',
        'message',
        'context',
    ];

    public const HIDDEN = [
    ];

    public static function factory(Generator $faker)
    {
        return [
            'id' => $faker->uuid,
            'task_log_id' => $faker->uuid,
            'entry_number' => $faker->randomNumber(2),
            'severity' =>  $faker->words(1, true),
            'message' => $faker->paragraph,
            'context' => ["foo" => "bar"],
            'created_at' => $faker->dateTimeBetween('-1 years', '-1 hour'),
            'updated_at' => $faker->dateTimeBetween('-1 years', '-1 hour'),
        ];
    }

    public static function validation($for)
    {
        if($for === "update") {
            return [
                'task_log_id' => 'sometimes|required',
                'entry_number' => 'sometimes|required|integer',
                'severity' => 'sometimes|required',
                'message' => 'sometimes|required',
                'context' => 'json',
            ];
        }

        if($for === "create") {
            return [
                'task_log_id' => 'required',
                'entry_number' => 'required|integer',
                'severity' => 'required',
                'message' => 'required',
                'context' => 'json',
            ];
        }

        return [];
    }

}
