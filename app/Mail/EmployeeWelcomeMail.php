<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployeeWelcomeMail extends Mailable
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
            subject: 'Welcome to EMS — Your Account Has Been Created',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.employee-welcome',
        );
    }
}