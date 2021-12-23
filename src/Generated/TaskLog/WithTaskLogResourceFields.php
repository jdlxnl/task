<?php

namespace Jdlx\Task\Generated\TaskLog;

use Illuminate\Http\Resources\Json\JsonResource;
use Jdlx\Task\Generated\TaskLogEntry\TaskLogEntryResource;

/**
 * Use in a resource to automatically create a fields
 * model
 *
 * @mixin JsonResource
 */
trait WithTaskLogResourceFields
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function toArrayGenerated($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'step' => $this->step,
            'status' => $this->status,
            'payload' => is_array($this->payload) ? $this->payload : json_decode($this->payload),
            'created_at' => is_null($this->created_at) ? null : $this->created_at->toRfc3339String(),
            'updated_at' => is_null($this->updated_at) ? null : $this->updated_at->toRfc3339String(),
            "entries" => TaskLogEntryResource::collection($this->whenLoaded('entries')),
        ];
    }
}



