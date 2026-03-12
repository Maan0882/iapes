<?php

namespace App\Models\TaskManagement;

use Illuminate\Database\Eloquent\Model;
use App\Models\InternManagement\Intern;
use App\Models\InternManagement\InternshipBatch;
use App\Models\InternManagement\InternTeam;
use App\Models\TaskManagement\TaskSubmission;
use App\Models\TaskManagement\TaskAssignment;


class Task extends Model
{
    //

    protected $primaryKey = 'task_id';

     protected $fillable = [
       'title',
        'description',
        'due_date',
        'attachment',
        'priority'
    ];  

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class,'task_id');
    }

    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class,'task_id');
    }

    // public function intern()
    // {
    //     return $this->belongsTo(Intern::class,'intern_id');
    // }

    // public function team()
    // {
    //     return $this->belongsTo(InternTeam::class,'team_id');
    // }

    // public function batch()
    // {
    //     return $this->belongsTo(InternshipBatch::class,'batch_id');
    // }
}
