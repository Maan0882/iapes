<?php

namespace App\Models\InterviewManagement;

use Illuminate\Database\Eloquent\Model;
use App\Models\InternManagement\Intern;

class OfferLetter extends Model
{
    protected $fillable = [
        'offer_letter_code',
        'application_id',
        'joining_date',
        'completion_date',
        'internship_role',
        'working_hours',
        'intern_id',
        'template',
        'description',
        'college',   // ← add this too
        'name',
        'email',
        'phone',
        'university',
        'internship_position',
    ];
    protected $casts = [
        'joining_date' => 'date',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($offer) {

            $year = now()->year;

            $lastOffer = self::latest('id')->first();

            if ($lastOffer) {
                $lastNumber = (int) substr($lastOffer->offer_letter_code, -3);
                $sequence = $lastNumber + 1;
            } else {
                $sequence = 1;
            }

            $sequence = str_pad($sequence, 3, '0', STR_PAD_LEFT);

            $offer->offer_letter_code = "OFFER/{$year}/{$sequence}";
        });
    }

    public function application()
    {
        // Ensure this matches your foreign key in the offer_letters table
        return $this->belongsTo(Application::class, 'application_id');
    }

    // Accessor for convenience
    // public function getNameAttribute()
    // {
    //     return $this->application?->name;
    // }

    // public function getCollegeAttribute()
    // {
    //     return $this->application?->college;
    // }

    public function intern()
    {
        return $this->belongsTo(Intern::class, 'intern_id');
    }

    protected static function booted()
    {
        parent::booted();

        static::updated(function ($offerLetter) {
            // If the OfferLetter was updated with new name/college data, 
            // we sync it back to the related application.
            if ($offerLetter->application) {
                $offerLetter->application->update([
                    'name' => $offerLetter->name,
                    'college' => $offerLetter->college,
                ]);
            }
        });
    }
}
