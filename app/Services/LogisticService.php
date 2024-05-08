<?php

namespace App\Services;

use App\Models\Logistic;
use App\Util\JSONResponse;

class LogisticService {

    public static function Register($logistic, $company_uuid)
    {
        try {
            $number = str_replace(',', '.', str_replace('.', '', $logistic['unitary_value']));


            $logistic = Logistic::create([
                'uuid' => md5(uniqid(rand() . "", true)),
                'group' => $logistic['group'],
                'category' => $logistic['category'],
                'name' => $logistic['name'],
                'description' => $logistic['description'],
                'unit' => $logistic['unit'],
                'unitary_value' => $number,
                'company_uuid' => $company_uuid
            ]);

            if ($logistic) {
                return JSONResponse::JSONReturn(true, 200, "Logistica cadastrada com sucesso.", $logistic);
            }

            return JSONResponse::JSONReturn(false, 401, "Erro ao cadastrar logistica.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function GetAll($company_uuid)
    {
        try {
            $logistic = Logistic::where('company_uuid', $company_uuid)->get() ?? null;

            if($logistic) {
                return JSONResponse::JSONReturn(true, 200, "Lista de Logisticas.", $logistic);
            }

            return JSONResponse::JSONReturn(false, 401, "Nenhum Logistica cadastrado.", null);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function edit($uuid, $data, $company_uuid)
    {
        try {

            $response = Logistic::where('company_uuid', $company_uuid)
                ->where('uuid', $uuid)->update($data);

            if($response) {
                return JSONResponse::JSONReturn(true, 200, "Logistica editado com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 401, "Ocorreu algum erro ao editar a Logistica, tente novamente.", null);

            } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function delete($uuid, $company_uuid)
    {
        try {
            $response = Logistic::where('company_uuid', $company_uuid)
                ->where('uuid', $uuid)
                ->delete();

            if ($response) {
                return JSONResponse::JSONReturn(true, 200, "Logistica excluída com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 404, "Logistica não encontrada ou não pôde ser excluída.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

}
