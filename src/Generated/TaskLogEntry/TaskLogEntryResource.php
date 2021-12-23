<?php

namespace Jdlx\Task\Generated\TaskLogEntry;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskLogEntryResource extends JsonResource
{
    use WithTaskLogEntryResourceFields;


    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->toArrayGenerated($request);
    }
}
