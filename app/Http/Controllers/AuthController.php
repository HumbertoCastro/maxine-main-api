<?php

use App\Services\AuthService;
use App\Util\JSONResponse;
use App\Util\JWT\GenerateToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $fieldset = $request->validate([
                'email' => 'required|string',
                'password' => 'required|string'
            ]);

            $response = AuthService::checkCredentials($fieldset['email'], $fieldset['password']);

            if ($response) {
                $token = GenerateToken::CreateToken(
                    $response['name'],
                    $response['email'],
                    $response['roles'],
                    $response['is_admin'],
                    $response['is_root']
                );

                return response()->json([
                    'status' => true,
                    'code' => 200,
                    'message' => "Usuário logado com sucesso.",
                    'data' => [
                        'user' => $response,
                        'token' => $token
                    ]
                ]);
            }

            return JSONResponse::JSONReturn(false, 401, "E-mail ou senha inválidos.", []);
        } catch (\Exception $e) {
            Log::error("Error in AuthController@login: " . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return JSONResponse::JSONReturn(false, 500, "Internal Server Error.", []);
        }
    }
}
