<?php

namespace App\Models\InternManagement;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskManagement\TaskSubmission;
use App\Models\InternManagement\InternshipBatch;
use App\Models\InternManagement\Intern;

class InternTeam extends Model
{
    public function batch()
    {
        return $this->belongsTo(InternshipBatch::class, 'internship_batch_id');
    }

    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class, 'team_id');
    }

    // To get the students in this team
    public function members()
    {
        return $this->hasMany(Intern::class, 'team_id');
    }
}
