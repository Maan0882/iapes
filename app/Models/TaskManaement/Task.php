<?php

namespace App\Models\TaskManaement;

use Illuminate\Database\Eloquent\Model;
use App\Models\InternManagement\Intern;
use App\Models\TaskManagement\TaskSubmission;

class Task extends Model
{
    //

    protected $primaryKey = 'task_id';

     protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'intern_id',
        'team_id',
        'batch_id',
        'due_date'
    ];  

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class,'task_id');
    }
}
