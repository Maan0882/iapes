<?php

namespace App\Models\InternManagement;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskManagement\TaskSubmission;
use App\Models\InternManagement\InternshipBatch;
use App\Models\InternManagement\Intern;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InternTeam extends Model
{
    protected $fillable = [
        'internship_batch_id', //
        'team_name', 
        'slug',         //
        //'team_leader_id',      //
    ];
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($team) {
            if (empty($team->slug)) {
                $team->slug = Str::slug($team->name);
            }
        });
        static::updating(function ($team) {
            if ($team->isDirty('team_name')) {
                $team->slug = Str::slug($team->team_name);
            }
        });
    }
    public function batch()
    {
        return $this->belongsTo(InternshipBatch::class, 'internship_batch_id');
    }

    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class, 'team_id');
    }
    // To get the students in this team
    public function interns()
    {
        // Use 'intern_team_id' to match the column in your Intern model
        return $this->hasMany(Intern::class, 'intern_team_id');
    }
}
