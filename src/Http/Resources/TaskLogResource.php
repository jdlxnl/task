<?php

namespace Jdlx\Task\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Jdlx\Task\Generated\TaskLog\WithTaskLogResourceFields;

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
