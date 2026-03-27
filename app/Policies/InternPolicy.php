<?php

namespace App\Policies;

use App\Models\InternManagement\Intern;
//use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class InternPolicy
{
    use HandlesAuthorization;

    public function viewAny(Authenticatable $user): bool
    {
        return true;
    }

    public function update(Authenticatable $user, Intern $intern): bool
    {
        // Admins can update
        if ($user instanceof \App\Models\User && $user->is_admin) {
            return true;
        }

        return false;
    }

    public function delete(Authenticatable $user, Intern $intern): bool
    {
        // Admins can delete
        if ($user instanceof \App\Models\User && $user->is_admin) {
            return true;
        }

        return false;
    }
}

