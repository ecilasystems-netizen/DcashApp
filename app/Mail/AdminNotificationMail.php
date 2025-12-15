<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $notificationType;
    public string $userName;
    public string $userEmail;
    public array $details;
    public ?string $actionUrl;

    public function __construct(
        string $notificationType,
        string $userName,
        string $userEmail,
        array $details = [],
        ?string $actionUrl = null
    ) {
        $this->notificationType = $notificationType;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->details = $details;
        $this->actionUrl = $actionUrl;
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'kyc_submission' => 'New KYC Submission - Action Required',
            'limit_upgrade' => 'Account Limit Upgrade Request',
            'bonus_redemption' => 'Bonus Redemption Request',
            'wallet_transaction' => 'Wallet Transaction Alert',
            'exchange_transaction' => 'Exchange Transaction Alert',
        ];

        return new Envelope(
            subject: $subjects[$this->notificationType] ?? 'Admin Notification',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-notification',
            with: [
                'notificationType' => $this->notificationType,
                'userName' => $this->userName,
                'userEmail' => $this->userEmail,
                'details' => $this->details,
                'actionUrl' => $this->actionUrl,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
