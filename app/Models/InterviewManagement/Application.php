<?php

namespace App\Models\InterviewManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\InterviewManagement\Offerletter;
use Carbon\Carbon;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_code',
        'verification_token',
        'email',
        'email_verified_at',
        'name',
        'phone',
        'college',
        'degree',
        'year',
        'cgpa',
        'domain',
        'duration',
        'duration_unit',
        'skills',
        'resume_path',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($application) {
            if (empty($application->application_code)) {
                $datePart = now()->format('dmy');
                
                // 1. Fetch the absolute latest record regardless of the date in the code
                $lastApplication = static::orderBy('id', 'desc')->first();

                if ($lastApplication && !empty($lastApplication->application_code)) {
                    // 2. Extract the numeric suffix from the end of the string
                    // We use explode or regex to ensure we get the number after the last slash
                    $segments = explode('/', $lastApplication->application_code);
                    $lastSequence = (int) end($segments); 
                    
                    $newSequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newSequence = '001';
                }

                // 3. Combine current date with the incrementing number
                $application->application_code = "APP/" . $datePart . "/" . $newSequence;
            }

            if (empty($application->verification_token)) {
                $application->verification_token = Str::random(60);
            }
        });

        static::updated(function ($application) {
            // If name or college changes, find the associated offer letter
            $offerLetter = $application->offerLetter;
            if ($offerLetter) {
                // Note: Since name/college live on Application, this simply 
                // ensures the relationship stays logically consistent.
                $offerLetter->touch(); // Refreshes timestamps if needed
            }
        });
    }

    public function intern(): HasOne
    {
        // Assuming 'application_id' is the foreign key on the 'interns' table
        return $this->hasOne(\App\Models\InternManagement\Intern::class, 'application_id');
    }
    public function offer_letters(): HasMany
    {
        // Adjust the foreign key if it's not 'application_id'
        return $this->hasMany(OfferLetter::class, 'application_id');
    }
    public function offerLetter(): HasOne // Note the singular name
    {
        return $this->hasOne(OfferLetter::class, 'application_id');
    }

}
