<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserRoot;
use App\Util\JSONResponse;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    public static function checkCredentials(string $email, string $password)
    {
        try {
            $user = User::where('email', $email)->first() ?? null;

            if ($user) {

                $pass = self::checkPassword($password, $user['password']);

                if ($pass) {
                    return $user;
                }
            }

            return false;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public static function register()
    {
    }

    /**
     * Metodo de valida o passowrd.
     */
    private static function checkPassword($password, $user_password)
    {
        return Hash::check($password, $user_password);
    }
}
