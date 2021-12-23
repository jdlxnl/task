<?php

namespace Jdlx\Task\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Jdlx\Task\Generated\TaskLogEntry\WithTaskLogEntryResourceFields;

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
