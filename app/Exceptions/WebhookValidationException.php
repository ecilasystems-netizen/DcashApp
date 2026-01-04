<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class WebhookValidationException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(
        string $message = 'Webhook validation failed',
        int $code = 401,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
