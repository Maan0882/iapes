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
        if (isset($user->role) && $user->role === 'admin') {
            return true;
        }
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
