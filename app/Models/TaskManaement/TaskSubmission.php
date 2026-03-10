<?php

namespace App\Models\TaskManaement;

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
}
