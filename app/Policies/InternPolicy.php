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

    public function view(Authenticatable $user, Intern $intern): bool
    {
        if ($user->is_admin) {
            return true;
        }

        // 2. Interns can only see their own record
        // Use the foreign key 'user_id' stored in the interns table
        return $user->id === $intern->user_id;
    }

    public function update(Authenticatable $user, Intern $intern): bool
    {
        // This will now correctly return true for users with is_admin = 1
        return (bool) $user->is_admin; 
    }

    public function delete(Authenticatable $user, Intern $intern): bool
    {
        return (bool) $user->is_admin;
    }
}
