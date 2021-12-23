<?php

namespace Jdlx\Task\Generated\TaskLog;

use Faker\Generator;
use Jdlx\Traits\FieldDescriptor;

class TaskLogFields
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
        "entries" => ["type" => "hasMany", "target" => "\Jdlx\Task\Models\TaskLogEntry", "args" => []],
        "subjects" => ["type" => "morphedByMany", "target" => "\Jdlx\Task\Models\TaskLog", "args" => ["task_logs_relation"]],
    ];

    public const ACCESS = [
        'id' => ['readOnly', 'sortable', 'filterable'],
        'type' => ['editable', 'sortable', 'filterable'],
        'step' => ['editable', 'sortable', 'filterable'],
        'status' => ['editable', 'sortable', 'filterable'],
        'payload' => ['editable', 'sortable', 'filterable'],
        'created_at' => ['readOnly', 'sortable', 'filterable'],
        'updated_at' => ['readOnly', 'sortable', 'filterable'],
    ];

    public const CASTS = [
        'payload' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const FILLABLE = [
        'type',
        'step',
        'status',
        'payload',
    ];

    public const HIDDEN = [
    ];

    public static function factory(Generator $faker)
    {
        return [
            'id' => $faker->uuid,
            'type' => $faker->words(1, true),
            'step' => $faker->words(1, true),
            'status' => $faker->words(1, true),
            'payload' => ["foo" => "bar"],
            'created_at' => $faker->dateTimeBetween('-1 years', '-1 hour'),
            'updated_at' => $faker->dateTimeBetween('-1 years', '-1 hour'),
        ];
    }

    public static function validation($for)
    {
        if ($for === "update") {
            return [
                'type' => 'sometimes|required',
                'step' => 'sometimes|required',
                'status' => 'sometimes|required',
                'payload' => 'json',
            ];
        }

        if ($for === "create") {
            return [
                'type' => 'required',
                'step' => 'required',
                'status' => 'required',
                'payload' => 'json',
            ];
        }

        return [];
    }

}
