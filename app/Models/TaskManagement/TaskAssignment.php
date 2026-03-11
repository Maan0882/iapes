<?php

namespace App\Models\TaskManagement;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskManagement\Task;

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

}
