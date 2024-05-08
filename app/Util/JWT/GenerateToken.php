<?php

namespace App\Util\JWT;

use DateTime;

class GenerateToken
{
    private static $key; //Application Key.

    public function __construct()
    {
        self::$key = env('JWT_SECRET');
    }

    public static function CreateToken($user, $email, $roles, $is_admin, $is_root)
    {


        if ($user && $email) {
            //Header Token
            $header = [
                'typ' => 'JWT',
                'alg' => env('JWT_ALGO')
            ];

            $issuedAt = time();

            //Payload - Content
            $payload = [
                'email' => $email,
                'exp' => $issuedAt + 60,
                'roles' => $roles,
                'isAdmin' => $is_admin
            ];

            //JSON
            $header = json_encode($header);
            $payload = json_encode($payload);

            //Base 64
            $header = self::base64UrlEncode($header);
            $payload = self::base64UrlEncode($payload);

            //Sign
            $sign = hash_hmac('sha256', $header . "." . $payload, self::$key, true);
            $sign = self::base64UrlEncode($sign);

            //Token
            $token = $header . '.' . $payload . '.' . $sign;


            return $token;
        }

        throw new \Exception('Não autenticado');
    }

    /*Criei os dois métodos abaixo, pois o jwt.io agora recomenda o uso do 'base64url_encode' no lugar do 'base64_encode'*/
    public static function base64UrlEncode($data)
    {
        // First of all you should encode $data to Base64 string
        $b64 = base64_encode($data);

        // Make sure you get a valid result, otherwise, return FALSE, as the base64_encode() function do
        if ($b64 === false) {
            return false;
        }

        // Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
        $url = strtr($b64, '+/', '-_');

        // Remove padding character from the end of line and return the Base64URL result
        return rtrim($url, '=');
    }




    private static function diff_real_minutes(DateTime $dt1, DateTime $dt2)
    {
        return abs($dt1->getTimestamp() - $dt2->getTimestamp()) / 60;
    }
}
