<?php

namespace App\Policies;

use App\User;
use App\file;
use App\Policies\Traits\ReadOnly;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;
    use ReadOnly;
}
