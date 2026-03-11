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
        // 1. Check if the logged-in entity is an Admin User
        // (Assuming your User model has 'role' but Intern model does not)
        if (isset($user->role) && $user->role === 'admin') {
            return true;
        }

        // 2. Check if the logged-in entity is the Intern themselves
        // Case A: $user is a User model (matches email to username)
        // Case B: $user is an Intern model (matches id to id)
        
        $userIdentifier = $user->email ?? $user->username;
        $internIdentifier = $intern->username;

        return ($userIdentifier === $internIdentifier) || ($user->id === $intern->id);
    }

    public function update(Authenticatable $user, Intern $intern): bool
    {
        // Only allow if it's an Admin User
        return isset($user->role) && $user->role === 'admin';
    }

    public function delete(Authenticatable $user, Intern $intern): bool
    {
        return isset($user->role) && $user->role === 'admin';
    }
}
