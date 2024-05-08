<?php

namespace App\Services;

class JWTService {

    public static function getCompanyEmailByToken($token)
    {
        $token = explode('.', $token);
        $header = $token[0];
        $payload = $token[1];
        $sign = $token[2];

         //Pega o payload e aplica base64_decode.
         $payloadDecode = base64_decode($token[1]);
         //Aplica o json_decode.
         $payload_data = json_decode($payloadDecode, true);

         $user_email = $payload_data['email'];

        return $user_email;
    }

    public static function ExtractTokenData($token)
    {
        $token = explode('.', $token);
        $header = $token[0];
        $payload = $token[1];
        $sign = $token[2];

        //Pega o payload e aplica base64_decode.
        $payloadDecode = base64_decode($token[1]);
        //Aplica o json_decode.
        $payload_data = json_decode($payloadDecode, true);
        dd($payload_data);
        $user_email = $payload_data['email'];

        return $user_email;
    }

}
