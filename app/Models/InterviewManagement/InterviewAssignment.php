<?php

namespace App\Models\InterviewManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\Models\InterviewManagement\Application;
use App\Models\InterviewManagement\InterviewBatch;

class InterviewAssignment extends Model
{
     protected $fillable = [
        'assignment_code',
        'interview_batch_id',
        'application_id',
        'attendance',
        'problem_solving',
        'communication',
        'overall_score',
        'remarks',
        'result'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {

            $batch = InterviewBatch::find($model->interview_batch_id);

            if ($batch && $batch->capacity_status === 'full') {
                throw new \Exception("Batch is already FULL.");
            }

            $date = now()->format('dmy');

            $lastNumber = (int) substr(
                optional(InterviewAssignment::latest('id')->first())->assignment_code,
                -3
            );

            $model->assignment_code = "ASSIGN/{$date}/" . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        });

        static::saving(function ($assignment) {

            if ($assignment->problem_solving && $assignment->communication) {
                $assignment->overall_score =
                    ($assignment->problem_solving + $assignment->communication);
            }
                 
            if ($assignment->overall_score > 50) {
                throw new \Exception("Total marks cannot exceed 50");
            }
            
            if ($assignment->attendance === 'present') {
                $assignment->application->update([
                    'status' => 'interviewed'
                ]);
            }

            if ($assignment->result === 'selected') {
                $assignment->application->update([
                    'status' => 'shortlisted'
                ]);
            }

            if ($assignment->result === 'rejected') {
                $assignment->application->update([
                    'status' => 'rejected'
                ]);
            }
        });

        static::updated(function ($assignment) {

            if ($assignment->result) {

                if ($assignment->result === 'selected') {
                    $assignment->application
                        ->update(['status' => 'shortlisted']);
                } else {
                    $assignment->application
                        ->update(['status' => 'rejected']);
                }
            }
        });

        static::created(function ($assignment) {

            // Update Application Status
            $assignment->application
                ->update(['status' => 'interview_scheduled']);

            // Update Batch Capacity
            $assignment->batch->updateCapacityStatus();
        });

        static::deleted(function ($assignment) {

            if ($assignment->batch) {
                $assignment->batch->updateCapacityStatus();
            }

        });
    }
    
    public function batch()
    {
        return $this->belongsTo(
            InterviewBatch::class,
            'interview_batch_id',
            'id'
        );
    }

    public function application()
    {
        return $this->belongsTo(
            Application::class,
            'application_id',
            'id'
        );
    }

}
