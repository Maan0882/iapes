<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\InternManagement\Intern;
use Illuminate\Support\Str;

class InternWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $password;

    public function __construct(public \App\Models\InternManagement\Intern $intern)
    {
        // Sets password as the username prefix (e.g., ts26001)
        $this->password = str($this->intern->username)->before('@user.com')->toString();
    }

    public function build()
    {
        return $this->subject('Welcome to Techstrota - Your Internship Credentials')
                    ->view('emails.interns.welcome'); // Changed from markdown to view for table support
    }
}
