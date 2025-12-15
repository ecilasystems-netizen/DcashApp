<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $name;
    public string $browser;
    public string $location;

    public function __construct(string $name, string $browser = '', string $location = '')
    {
        $this->name = $name;
        $this->browser = $browser;
        $this->location = $location;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset Notification',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.password-reset-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
