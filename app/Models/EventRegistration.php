<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    //
    protected $fillable = [
        'event_id',
       // 'user_id',
        'name',
        'email',
        'phone',
        'institution',
        'attendance_status',
        'certificate_issued',
        'certificate_number',
        'certificate_path'
    ];

     public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function generateCertificateNumber()
    {
        $prefix = "TS"; // Organization Initials
        $year = now()->format('y'); // 26
        $type = strtoupper(substr($this->event->event_type, 0, 4)); // WKSH, HACK, SEMI
        
        // Get the count of issued certificates for this event to create a sequence
        $sequence = self::where('event_id', $this->event_id)
            ->whereNotNull('certificate_number')
            ->count() + 1;

        // TS-WKSH-26-0001
        return sprintf("%s-%s-%s-%04d", $prefix, $type, $year, $sequence);
    }
}
