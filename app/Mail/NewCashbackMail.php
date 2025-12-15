<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewCashbackMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Cashback',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-cashback',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
