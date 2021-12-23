<?php

namespace Jdlx\Task\Generated\TaskLogEntry;

use Illuminate\Http\Request;
use Jdlx\Http\Controllers\Traits\CanFilterSortAndPage;
use Jdlx\Task\Models\TaskLogEntry;

trait WithTaskLogEntryCrudRoutes
{
    use CanFilterSortAndPage;


    public function index(Request $request)
    {
        $this->authorize('viewAny', TaskLogEntry::class);

        $with = $this->getRelationshipFields($request, $this->getRelationshipWhiteList());

        $items = TaskLogEntry::query()->with($with);
        $paging = $this->filterSortAndPage($request, $items, TaskLogEntry::getFilterableFields());
        $data = $paging->jsonSerialize();

        $resource = $this->getResourceClass();
        $data["items"] = call_user_func($resource . '::collection', $paging->items());
        return response()->success($data, 200);
    }

    public function store(Request $request)
    {
        $this->authorize('create', [TaskLogEntry::class]);

        $request->validate($this->getValidationRules("create"));

        $fields = $request->only(TaskLogEntry::getWritableFields());

        $taskLogEntry = new TaskLogEntry($fields);
        $taskLogEntry->save();

        $resource = $this->getResourceClass();
        return response()->success(new $resource($taskLogEntry), 201);
    }


    public function show(TaskLogEntry $taskLogEntry)
    {
        $this->authorize('view', $taskLogEntry);

        $resource = $this->getResourceClass();
        return response()->success(new $resource($taskLogEntry));
    }


    public function update(Request $request, TaskLogEntry $taskLogEntry)
    {
        $this->authorize('update', $taskLogEntry);

        $request->validate($this->getValidationRules("update"));
        $fields = $request->only(TaskLogEntry::getEditableFields());
        $taskLogEntry->fill($fields)->save();
        $resource = $this->getResourceClass();
        return response()->success(new $resource($taskLogEntry));
    }

    public function destroy(TaskLogEntry $taskLogEntry)
    {
        $this->authorize('delete', $taskLogEntry);

        $taskLogEntry->delete();
        return response()->success([
            "id" => $taskLogEntry->id,
            "deleted" => true
        ], 200);
    }

    protected function getValidationRules($for = "create")
    {
        return TaskLogEntryFields::validation($for);
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
        return TaskLogEntryResource::class;
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
            "taskLog",
        ];
    }
}
