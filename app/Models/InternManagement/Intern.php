<?php

namespace App\Models\InternManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use App\Models\InterviewManagement\Application;
use App\Models\InterviewManagement\OfferLetter;

class Intern extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'application_id',
        'intern_code',
        'username',
        'password',
        'name',
        'email',
        'joining_date',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    // public function getAuthIdentifierName()
    // {
    //     return 'username';
    // }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'intern';
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function offerletter()
    {
        return $this->belongsTo(OfferLetter::class);
    }
}
