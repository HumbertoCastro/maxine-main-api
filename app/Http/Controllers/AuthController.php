<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Util\JSONResponse;
use App\Util\JWT\GenerateToken;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function login(Request $request)
    {
        $fieldset = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $response = AuthService::checkCredentials($fieldset['email'], $fieldset['password']);

        if($response) {
            $token = GenerateToken::CreateToken($response['name'], $response['email'], $response['roles'], $response['is_admin'], $response['is_root']);

            return response()->json([
                'status' => true,
                'code' => 200,
                'messagem' => "Usuário logado com sucesso.",
                'data' => [
                    'user' => $response,
                    'token' => $token
                ]
            ]);
        }

        return JSONResponse::JSONReturn(false, 401, "E-mail ou senha inválidos.", []);
    }
}
