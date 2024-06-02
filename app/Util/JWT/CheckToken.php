<?php

namespace App\Util\JWT;

use App\Util\JSONResponse;
use App\Util\JWT\GenerateToken;
use DateTime;

class CheckToken
{
    private static $key; // Application Key.

    public function __construct()
    {
        self::$key = env('JWT_SECRET');
    }

    public static function checkAuth($token)
    {
        if (isset($token) && $token != null) {

            $tokenParts = explode('.', $token);
            if (count($tokenParts) !== 3) {
                return 401; // Invalid token structure
            }

            list($header, $payload, $sign) = $tokenParts;

            // Decode payload
            $payloadDecode = base64_decode($payload);
            $payload_data = json_decode($payloadDecode, true);

            if (!$payload_data) {
                return 401; // Invalid payload
            }

            // Check expiration
            $expired = $payload_data['exp'];
            $currentTimestamp = time();

            if ($currentTimestamp > $expired) {
                return 401; // Token expired
            }

            // Verify signature
            $valid = hash_hmac('sha256', $header . "." . $payload, self::$key, true);
            $valid = GenerateToken::base64UrlEncode($valid);

            if ($sign === $valid) {
                return 200; // Token is valid
            }

            return 401; // Invalid token signature
        }

        return 401; // No token provided
    }

    private static function diff_real_minutes(DateTime $dt1, DateTime $dt2)
    {
        return abs($dt1->getTimestamp() - $dt2->getTimestamp()) / 60;
    }
}
