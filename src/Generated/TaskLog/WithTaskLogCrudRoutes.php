<?php

namespace Jdlx\Task\Generated\TaskLog;

use Illuminate\Http\Request;
use Jdlx\Http\Controllers\Traits\CanFilterSortAndPage;
use Jdlx\Task\Models\TaskLog;

trait WithTaskLogCrudRoutes
{
    use CanFilterSortAndPage;


    public function index(Request $request)
    {
        $this->authorize('viewAny', TaskLog::class);

        $with = $this->getRelationshipFields($request, $this->getRelationshipWhiteList());

        $items = TaskLog::query()->with($with);
        $paging = $this->filterSortAndPage($request, $items, TaskLog::getFilterableFields());
        $data = $paging->jsonSerialize();

        $resource = $this->getResourceClass();
        $data["items"] = call_user_func($resource . '::collection', $paging->items());
        return response()->success($data, 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create', [TaskLog::class]);

        $request->validate($this->getValidationRules("create"));

        $fields = $request->only(TaskLog::getWritableFields());

        $taskLog = new TaskLog($fields);
        $taskLog->save();

        $resource = $this->getResourceClass();
        return response()->success(new $resource($taskLog), 201);
    }


    public function show(TaskLog $taskLog)
    {
        $this->authorize('view', $taskLog);

        $resource = $this->getResourceClass();
        return response()->success(new $resource($taskLog));
    }


    public function update(Request $request, TaskLog $taskLog)
    {
        $this->authorize('update', $taskLog);

        $request->validate($this->getValidationRules("update"));
        $fields = $request->only(TaskLog::getEditableFields());
        $taskLog->fill($fields)->save();
        $resource = $this->getResourceClass();
        return response()->success(new $resource($taskLog));
    }

    public function destroy(TaskLog $taskLog)
    {
        $this->authorize('delete', $taskLog);

        $taskLog->delete();
        return response()->success([
            "id" => $taskLog->id,
            "deleted" => true
        ], 200);
    }

    protected function getValidationRules($for = "create")
    {
        return TaskLogFields::validation($for);
    }

    /**
     * Return the resource class to be used to return responses
     * defaults to the generated resource, but can be overwritten
     * to alter responses
     *
     * @return string
     */
    protected function getResourceClass(): string
    {
        return TaskLogResource::class;
    }

    /**
     * Whitelist of relationships that can be added using
     * with in query
     *
     * @return string
     */
    protected function getRelationshipWhiteList(): array
    {
        return [
            "entries",
            "subjects",
        ];
    }
}
