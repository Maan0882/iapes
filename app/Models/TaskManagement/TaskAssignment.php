<?php

namespace App\Models\TaskManagement;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskManagement\Task;

use App\Models\InternManagement\Intern;
use App\Models\InternManagement\InternshipBatch;
use App\Models\InternManagement\InternTeam;

class TaskAssignment extends Model
{
    //
    protected $primaryKey = 'task_assignment_id';

    protected $fillable = [
        'task_id',
        'assigned_type',
        'intern_id',
        'team_id',
        'batch_id'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class,'task_id');
    }

    public function team()
    {
        return $this->belongsTo(InternTeam::class, 'team_id');
    }


    public function intern()
    {
        return $this->belongsTo(Intern::class,'intern_id');
    }

    // public function team()
    // {
    //     return $this->belongsTo(InternTeam::class,'team_id');
    // }

    public function batch()
    {
        return $this->belongsTo(InternshipBatch::class,'batch_id');
    }

    public function task_submission()
    {
        return $this->hasOne(
            \App\Models\TaskManagement\TaskSubmission::class,
            'task_id',
            'task_id'
        )->where('intern_id', auth()->id);
    }
}
