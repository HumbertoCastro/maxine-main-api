<?php

namespace App\Util;

class JSONResponse {

    public static function JSONReturn ($status, $code, $message, $data)
    {
        return response()->json([
            "status" => $status,
            "code" => $code,
            "message" => $message,
            "data" => $data
        ]);
    }

}
