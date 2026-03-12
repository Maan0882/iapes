<?php

namespace App\Models\InternManagement;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskManagement\TaskSubmission;
use App\Models\InternManagement\Intern;

class InternshipBatch extends Model
{
    //
    //  protected $fillable = [
       
    // ];  



    public function intern()
    {
        return $this->belongsTo(Intern::class,'intern_id');
    }
    
}
