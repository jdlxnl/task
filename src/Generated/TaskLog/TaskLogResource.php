<?php

namespace Jdlx\Task\Generated\TaskLog;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskLogResource extends JsonResource
{
    use WithTaskLogResourceFields;


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
