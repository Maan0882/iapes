<?php

namespace App\Models\InternManagement;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskManagement\TaskSubmission;
use App\Models\InternManagement\Intern;

class InternshipBatch extends Model
{
    //
     protected $fillable = [
        'batch_name',
        'batch_timing',
        'no_of_interns',
        'team_id',
        // 'intern_id',
       
    ];  



    public function interns(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        // The first argument is the class, the second is the foreign key ON the interns table
        return $this->hasMany(Intern::class, 'internship_batch_id');
    }
    public function teams(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        // This looks for teams where 'internship_batch_id' matches this batch's ID
        return $this->hasMany(InternTeam::class, 'internship_batch_id');
    }
    public function team(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        // Use the full namespace to ensure Laravel finds the class
        return $this->belongsTo(\App\Models\InternManagement\InternTeam::class, 'team_id');
    }
}
