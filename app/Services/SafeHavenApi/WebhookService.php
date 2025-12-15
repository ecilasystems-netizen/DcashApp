<?php

namespace App\Services\SafeHavenApi;

use Illuminate\Support\Facades\Log;

class WebhookService
{
    public function handleWebhook(array $payload): array
    {
        Log::info('WebhookService - received webhook', [
            'payload' => $payload
        ]);

        // Add your webhook handling logic here
        // For example: validate signature, process events, etc.

        return [
            'success' => true,
            'message' => 'Webhook processed successfully'
        ];
    }

    public function verifySignature(string $signature, string $payload, string $secret): bool
    {
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        
        return hash_equals($expectedSignature, $signature);
    }
}
