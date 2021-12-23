<?php

namespace Jdlx\Task\Http\Controllers\Api;

use Illuminate\Http\Request;
use Jdlx\Http\Controllers\Controller;
use Jdlx\Task\Generated\TaskLog\WithTaskLogCrudRoutes;
use Jdlx\Task\Models\TaskLog;

class TaskLogController extends Controller
{
    use WithTaskLogCrudRoutes;

    public function index(Request $request)
    {
        $items = TaskLog::query()->with('entries');
        $paging = $this->filterSortAndPage($request, $items, TaskLog::getFilterableFields());
        $data = $paging->jsonSerialize();

        $resource = $this->getResourceClass();
        $data["items"] = call_user_func($resource . '::collection', $paging->items());
        return response()->success($data, 200);
    }

}
