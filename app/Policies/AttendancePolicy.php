<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Access\Response;

class AttendancePolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return true; // Both Admin and Intern can view
    }

    public function create(Authenticatable $user): bool
    {
        // Check if the authenticated model has an is_admin property/method
        return isset($user->is_admin) && $user->is_admin; 
    }

    public function update(Authenticatable $user, Attendance $attendance): bool
    {
        return isset($user->is_admin) && $user->is_admin;
    }

    public function delete(Authenticatable $user, Attendance $attendance): bool
    {
        return isset($user->is_admin) && $user->is_admin;
    }
}
