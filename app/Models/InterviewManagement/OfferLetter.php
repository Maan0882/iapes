<?php

namespace App\Models\InterviewManagement;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(Application::class);
    }
}
