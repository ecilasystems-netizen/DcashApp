<?php

namespace App\Mail\app;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpVerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $name;

    public function __construct(string $otp, string $name)
    {
        $this->otp = $otp;
        $this->name = $name;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Otp Verification - DCash Wallet',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.otp-verification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
