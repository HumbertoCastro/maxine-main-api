<?php

namespace App\Services;

class TokenService {

    public static function GetCompanyUuidByToken($token)
    {
        $token_data = self::ExtractTokenData($token);
        if (!$token_data) {
            // Handle error, token data is invalid
            return null;
        }

        $user_data = UserService::GetUserDataByEmail($token_data['email']);
        return $user_data['company_uuid'];
    }

    public static function ExtractTokenData($token)
    {
        $parts = explode('.', $token);

        // Check if all parts of the token exist
        if (count($parts) !== 3) {
            error_log('Invalid token structure');
            return null;
        }

        // Decode the payload
        $payloadDecode = base64_decode($parts[1], true);
        if (!$payloadDecode) {
            error_log('Failed to decode payload');
            return null;
        }

        // Convert the payload from JSON to an array
        $payload_data = json_decode($payloadDecode, true);
        if (!is_array($payload_data)) {
            error_log('Payload is not a valid array');
            return null;
        }

        return $payload_data;
    }
}