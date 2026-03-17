<?php

namespace App\Models\TaskManagement;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskManagement\Task;

class TaskSubmission extends Model
{
    //
     protected $primaryKey = 'submission_id';

    protected $fillable = [
        'task_id',
        'intern_id',
        'status',
        'submission_text',
        'submission_file',
        'submitted_at'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class,'task_id');
    }


    public function taskAssignment()
    {
        return $this->belongsTo(
            \App\Models\TaskManagement\TaskAssignment::class,
            'task_id',
            'task_id'
        )->where('intern_id', auth()->id());
    }
}
