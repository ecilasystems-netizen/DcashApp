<?php

namespace App\Services;

use Carbon\Carbon;
use Firebase\JWT\JWT;

class ZohoJWTService
{
    public static function generateToken($user)
    {
        $payload = [
            "sub" => $user->id,                // external ID for your user
            "name" => $user->fname,           // name
            "email" => $user->email,          // email
            "iat" => Carbon::now()->timestamp, // issued at
            "exp" => Carbon::now()->addSeconds(env("ZOHO_JWT_EXPIRY", 3600))->timestamp
        ];

        return JWT::encode($payload, env("ZOHO_JWT_SECRET"), 'HS256');
    }
}
