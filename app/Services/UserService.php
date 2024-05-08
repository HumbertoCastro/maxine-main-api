<?php

namespace App\Services;

use App\Models\User;
use App\Util\JSONResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public static function registerUser($user, $photo_url = null)
    {
        $validate_email = self::checkEmail($user['email']);

        if (!$validate_email) {
            try {
                $user = User::create([
                    'uuid' => md5(uniqid(rand() . "", true)),
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'password' => Hash::make($user['password']),
                    'photo_url' => $photo_url,
                    'roles' => $user['roles'],
                    'is_admin' => $user['is_admin'],
                    'is_active' => $user['is_active'],
                    'company_uuid' => $user['company_uuid']
                ]);
                print('teste');

                if ($user) {
                    return JSONResponse::JSONReturn(true, 200, "Usuário cadastrado com sucesso.", $user);
                }
            } catch (\Throwable $th) {
                return JSONResponse::JSONReturn(false, 500, "Error", $th);
            }
        }

        return JSONResponse::JSONReturn(false, 401, "E-mail ja cadastrado.", null);
    }

    public static function edit($user)
    {
        try {
            $response = User::where('uuid', $user['uuid'])->update($user);
            if ($response) {
                return JSONResponse::JSONReturn(true, 200, "Cliente editado com sucesso.", null);
            }
            return JSONResponse::JSONReturn(false, 401, "Ocorreu algum erro ao editar o cliente, tente novamente.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Error", $th->getMessage());
        }
    }

    /**
     * Método que retorna o dado do usuario pelo e-mail,
     * uso externo.
     */
    public static function getUserByEmail($email)
    {
        try {
            $user = User::where("email", $email)->firstOrFail() ?? null;

            if ($user) {

                return JSONResponse::JSONReturn(true, 200, "Dados do usuário.", $user);

            }

        } catch (ModelNotFoundException $exception) {

            return JSONResponse::JSONReturn(false, 401, "Usuário não encontrado.", $exception);
        }
    }

    /**
     * Método que verifica se já existe o E-mail cadastrado.
     */
    public static function checkEmail(string $email)
    {
        $user_email = User::where('email', $email)->first() ?? null;

        if ($user_email) {
            return true;
        }
        return false;
    }

    /**
     * Metodo que retorna os dados do usuário logado,
     * num Array.
     * Uso interno.
     */
    public static function GetUserDataByEmail($email)
    {
        try {
            $user = User::where("email", $email)->firstOrFail() ?? null;

            if ($user) {
                return $user;
            }

            return null;

        } catch (ModelNotFoundException $exception) {
            return $exception;
        }
    }

    /**
     * Método que retorna uma lista de usuários referentes ao company_uuid.
     */
    public static function GetUsers($company_uuid)
    {
        try {
            $users = User::where('company_uuid', $company_uuid)->get() ?? null;

            if($users) {
                return JSONResponse::JSONReturn(true, 200, "Lista de usuários", $users);
            }

            return JSONResponse::JSONReturn(true, 200, "Não existem usuários cadastrados.", []);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Error", $th->getMessage());
        }
    }

    public static function GetUserInfo($email)
    {
        try {
            $user = User::where("email", $email)->firstOrFail() ?? null;

            if ($user) {
                return JSONResponse::JSONReturn(true, 200, "Dados do usuário logado", $user);
            }

            return JSONResponse::JSONReturn(false, 401, "Usuário inexistente.", null);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }
}
