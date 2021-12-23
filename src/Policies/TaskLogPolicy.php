<?php

namespace Jdlx\Task\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Jdlx\Task\Generated\TaskLog\WithTaskLogPolicyGlobal;
use Jdlx\Task\Generated\TaskLog\WithTaskLogPolicyOwned;

class TaskLogPolicy
{

    use HandlesAuthorization;

    // Pick the UserPolicy scaffold that best fits
    // Global = App wide ownership of records
    // Owned =  Determine per owner namespacing of models Model
    use WithTaskLogPolicyGlobal;
    // use WithTaskLogPolicyOwned;

}
