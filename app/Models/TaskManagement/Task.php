<?php

namespace App\Models\TaskManaement;

use Illuminate\Database\Eloquent\Model;
use App\Models\InternManagement\Intern;
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
}
