<?php

namespace App\Policies;

use App\Models\InternManagement\Intern;
//use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class InternPolicy
{
    use HandlesAuthorization;

    // This ensures they can only view their own record details
    public function view(Intern $user, Intern $intern): bool
    {
        // Since $user is now an Intern instance, we compare their IDs or Usernames
        return $user->id === $intern->id;
    }

    public function update(Intern $user, Intern $intern): bool
    {
        return $user->id === $intern->id;
    }

    public function delete(Intern $user, Intern $intern): bool
    {
        return false;
    }
}
