<?php

return [
    'client_id' => env('SAFEHAVEN_API_CLIENT_ID'),
    'bearer_token' => env('SAFEHAVEN_API_BEARER_TOKEN'),
    'base_url' => env('SAFEHAVEN_API_URL'),
    'client_assertion' => env('SAFEHAVEN_API_CLIENT_ASSERTION'),
    'debit_account_number' => env('SAFEHAVEN_API_DEBIT_ACCOUNT_NUMBER'),


    // Webhook IP whitelist
    'webhook_allowed_ips' => array_filter(
        explode(',', env('SAFEHAVEN_WEBHOOK_IPS'))
    ),
];
