<?php

namespace App\Util\JWT;

use App\Util\JSONResponse;
use App\Util\JWT\GenerateToken;
use DateTime;

class CheckToken
{
    private static $key; //Application Key.

    public function __construct()
    {
        self::$key = env('JWT_SECRET');
    }


    public static function checkAuth($token)
    {
        if (isset($token) && $token != null) {

            $token = explode('.', $token);
            $header = $token[0];
            $payload = $token[1];
            $sign = $token[2];

            //Pega o payload e aplica base64_decode.
            $payloadDecode = base64_decode($token[1]);
            //Aplica o json_decode.
            $payload_data = json_decode($payloadDecode, true);
            //Pega o exp.
            $expired =  $payload_data['exp'];
            //Converte a data de expiraçao para fotmato 2022-11-16 00:02:12.
            $date_expired = date('d/m/Y H:i', $expired);

            $date_today = date('d/m/Y H:i');

            $date1 = strtotime($date_expired);
            $date2 = strtotime($date_today);

            $interval = ($date2 - $date1) / 60;

            $dt1 = DateTime::createFromFormat('d/m/Y H:i', "$date_expired");
            $dt2 = DateTime::createFromFormat('d/m/Y H:i', "$date_today");

            $diference = self::diff_real_minutes($dt1, $dt2);

            //Conferir Assinatura
            $valid = hash_hmac('sha256', $header . "." . $payload, self::$key, true);
            $valid = GenerateToken::base64UrlEncode($valid);

            $is_valid = false;
            $is_expired =  false;

            //First IF, verify token is valid.
            if ($sign === $valid) {
                $is_valid = true;
            }
            //Check if the token has expired. 60 minutes
            if ($diference >= 60) {
                $is_expired = true;
            }
            // dd($is_expired);
            if ($is_valid) {
                if (!$is_expired) {

                    return 200;
                    //return JSONResponse::JSONReturn(true, 200, "Token is valid", null);

                }
                return 401;
                //return JSONResponse::JSONReturn(false, 401, "Token expired", null);

            }

            return false;
        }
    }

    private static function diff_real_minutes(DateTime $dt1, DateTime $dt2)
    {
        return abs($dt1->getTimestamp() - $dt2->getTimestamp()) / 60;
    }
}
