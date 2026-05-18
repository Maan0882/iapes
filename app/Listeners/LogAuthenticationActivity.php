<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Request;

class LogAuthenticationActivity
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $action = '';
        $description = '';
        $user = null;

        if ($event instanceof Login) {
            $action = 'login';
            $user = $event->user;
            $description = "User logged in";
        } elseif ($event instanceof Logout) {
            $action = 'logout';
            $user = $event->user;
            $description = "User logged out";
        } elseif ($event instanceof Failed) {
            $action = 'login_failed';
            $description = "Failed login attempt for email: " . ($event->credentials['email'] ?? 'unknown');
        }

        if ($action) {
            ActivityLog::create([
                'user_id' => $user?->id,
                'action' => $action,
                'subject_type' => $user ? get_class($user) : null,
                'subject_id' => $user?->id,
                'description' => $description,
                'properties' => [
                    'ip' => Request::ip(),
                    'user_agent' => Request::userAgent(),
                ],
            ]);
        }
    }
}
