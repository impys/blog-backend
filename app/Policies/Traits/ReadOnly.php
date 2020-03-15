<?php

namespace App\Policies\Traits;

use App\User;

trait ReadOnly
{
    public function view(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user)
    {
        return false;
    }

    public function delete(User $user)
    {
        return false;
    }

    public function restore(User $user)
    {
        return false;
    }

    public function forceDelete(User $user)
    {
        return false;
    }
}
