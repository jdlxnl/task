<?php

namespace Jdlx\Task\Generated\TaskLogEntry;

use App\Models\User;
use Jdlx\Task\Models\TaskLogEntry;

trait WithTaskLogEntryPolicyOwned
{
    /**
     * Determine whether the user can take action on the model
     *
     * @param\App\Models\User $userWithRequest
     * @return bool
     */
    public function before(User $userWithRequest)
    {
        if($userWithRequest->hasRole("Super Admin")){
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param\App\Models\User $userWithRequest
     * @return bool
     */
    public function viewAny(User $userWithRequest): bool
    {
        return $userWithRequest->hasPermissionTo("*.taskLogEntry.view.*");
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param\App\Models\User $userWithRequest
     * @param \Jdlx\Task\Models\TaskLogEntry $taskLogEntry
     * @return bool
     */
    public function view(User $userWithRequest, TaskLogEntry $taskLogEntry): bool
    {
        $name = $this->getOwnerName($taskLogEntry);
        return $userWithRequest->hasPermissionTo("{$name}.taskLogEntry.view.{$taskLogEntry->id}");
    }

    /**
     * Determine whether the user can create models.
     *
     * @param\App\Models\User $userWithRequest
     * @return bool
     */
    public function create(User $userWithRequest, $name): bool
    {
        return $userWithRequest->hasPermissionTo("{$name}.taskLogEntry.create");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param\App\Models\User $userWithRequest
     * @param \Jdlx\Task\Models\TaskLogEntry $taskLogEntry
     * @return bool
     */
    public function update(User $userWithRequest, TaskLogEntry $taskLogEntry): bool
    {
        $name = $this->getOwnerName($taskLogEntry);
        return $userWithRequest->hasPermissionTo("{$name}..taskLogEntry.update.{$taskLogEntry->id}");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param\App\Models\User $userWithRequest
     * @param \Jdlx\Task\Models\TaskLogEntry $taskLogEntry
     * @return bool
     */
    public function delete(User $userWithRequest, TaskLogEntry $taskLogEntry): bool
    {
        $name = $this->getOwnerName($taskLogEntry);
        return $userWithRequest->hasPermissionTo("{$name}..taskLogEntry.delete.{$taskLogEntry->id}");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param\App\Models\User $userWithRequest
     * @param \Jdlx\Task\Models\TaskLogEntry $taskLogEntry
     * @return bool
     */
    public function restore(User $userWithRequest, TaskLogEntry $taskLogEntry): bool
    {
        $name = $this->getOwnerName($taskLogEntry);
        return $userWithRequest->hasPermissionTo("{$name}..taskLogEntry.restore.{$taskLogEntry->id}");
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param\App\Models\User $userWithRequest
     * @param \Jdlx\Task\Models\TaskLogEntry $taskLogEntry
     * @return bool
     */
    public function forceDelete(User $userWithRequest, TaskLogEntry $taskLogEntry): bool
    {
        $name = $this->getOwnerName($taskLogEntry);
        return $userWithRequest->hasPermissionTo("{$name}..taskLogEntry.delete.{$taskLogEntry->id}");
    }

    abstract public function getOwnerName(TaskLogEntry $model);
}



