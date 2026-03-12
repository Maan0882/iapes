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
        'intern_id',
       
    ];  



    public function intern()
    {
        return $this->belongsTo(Intern::class,'intern_id');
    }
    
    public function team()
    {
        return $this->belongsTo(InternTeam::class, 'team_id');
    }
}
