<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Access\Response;

class AttendancePolicy
{
    public function viewAny($user): bool
    {
        // 1. Admin (User model) can see all records
        if ($user instanceof \App\Models\User && $user->is_admin) {
            return true;
        }

        // 2. Interns (Intern model) can see the resource
        if ($user instanceof \App\Models\InternManagement\Intern) {
            return true;
        }

        return (bool) $user->is_admin;
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
