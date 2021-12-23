<?php

namespace Jdlx\Task\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Jdlx\Task\Generated\TaskLogEntry\WithTaskLogEntryPolicyGlobal;
use Jdlx\Task\Generated\TaskLogEntry\WithTaskLogEntryPolicyOwned;

class TaskLogEntryPolicy
{

    use HandlesAuthorization;

    // Pick the UserPolicy scaffold that best fits
    // Global = App wide ownership of records
    // Owned =  Determine per owner namespacing of models Model
    use WithTaskLogEntryPolicyGlobal;
    // use WithTaskLogEntryPolicyOwned;

}
