<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidateSelectedMail extends Mailable
{
    use SerializesModels;

    public $assignment;

    public function __construct($assignment)
    {
        $this->assignment = $assignment;
    }

    public function build()
    {
        return $this->subject('Congratulations! You Are Selected 🎉')
            ->view('emails.candidate-selected')
            ->with([
                'assignment' => $this->assignment,
            ]);
    }

}
