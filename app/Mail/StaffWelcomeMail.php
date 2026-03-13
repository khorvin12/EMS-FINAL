<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $firstName,
        public string $email,
        public string $role,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congratulations — You Have Been Selected for EMS',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.staff-welcome',
        );
    }
}