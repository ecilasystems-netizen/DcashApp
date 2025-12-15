<?php

namespace App\Mail;

use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RefundMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public WalletTransaction $originalTransaction,
        public WalletTransaction $refundTransaction,
        public string $userName
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Transaction Refund Processed - DCash Wallet',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.refund',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
