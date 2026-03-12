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
use App\Models\User;
use App\Models\InternManagement\InternshipBatch;
use App\Models\InternManagement\InternTeam;
use App\Models\TaskManagement\TaskSubmission;

class Intern extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'application_id',
        'internship_batch_id',
        'intern_team_id',
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
        // Make sure the method name 'offerletter' matches what you wrote in the Infolist
        return $this->hasOne(OfferLetter::class, 'intern_id'); 
    }

    public function user(): BelongsTo
    {
        // Intern 'username' matches User 'email'
        return $this->belongsTo(User::class, 'username', 'email');
    }

    public function batch()
    {
        return $this->belongsTo(InternshipBatch::class, 'internship_batch_id');
    }

    public function team()
    {
        // Note: This assumes you added 'team_id' to your interns table
        return $this->belongsTo(InternTeam::class, 'intern_team_id');
    }

    public function teammates()
    {
        // Gets other interns in the same team, excluding the current intern
        return $this->hasMany(Intern::class, 'intern_team_id', 'intern_team_id')
            ->where('id', '!=', $this->id);
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class, 'intern_id');
    }
}
