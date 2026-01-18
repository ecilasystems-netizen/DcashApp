<?php

declare(strict_types=1);

namespace App\Notifications\SafeHaven;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SafeHavenTransferCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $reference,
        public bool $success,
        public string $message
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'reference' => $this->reference,
            'success' => $this->success,
            'message' => $this->message,
            'type' => 'bank_transfer'
        ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'reference' => $this->reference,
            'success' => $this->success,
            'message' => $this->message,
            'type' => 'bank_transfer',
            'timestamp' => now()->toDateTimeString()
        ];
    }
}
