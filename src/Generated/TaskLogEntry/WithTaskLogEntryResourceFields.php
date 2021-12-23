<?php

namespace Jdlx\Task\Generated\TaskLogEntry;

use Illuminate\Http\Resources\Json\JsonResource;
use Jdlx\Task\Generated\TaskLog\TaskLogResource;

/**
 * Use in a resource to automatically create a fields
 * model
 *
 * @mixin JsonResource
 */
trait WithTaskLogEntryResourceFields
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function toArrayGenerated($request){
        return [
            'id' => $this->id,
            'task_log_id' => $this->task_log_id,
            'entry_number' => $this->entry_number,
            'severity' => $this->severity,
            'message' => $this->message,
            'context' => is_array($this->context) ? $this->context : json_decode($this->context),
            'created_at' => is_null($this->created_at) ? null : $this->created_at->toRfc3339String(),
            'updated_at' => is_null($this->updated_at) ? null : $this->updated_at->toRfc3339String(),
            "taskLog" =>  new TaskLogResource($this->whenLoaded('taskLog')),
        ];
    }
}



