<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewWalletTransactionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Wallet Transaction',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-wallet-transaction',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
