<?php

namespace App\Services;

use App\Models\Epi;
use App\Util\JSONResponse;

class EpiService
{

    public static function Register($epi, $company_uuid)
    {
        try {
            $number = str_replace(',', '.', str_replace('.', '', $epi['unitary_value']));


            $epi = Epi::create([
                'uuid' => md5(uniqid(rand() . "", true)),
                'category' => $epi['category'],
                'name' => $epi['name'],
                'description' => $epi['description'],
                'unit' => $epi['unit'],
                'property' => $epi['property'],
                'unitary_value' => $number,
                'company_uuid' => $company_uuid
            ]);

            if ($epi) {
                return JSONResponse::JSONReturn(true, 200, "EPI cadastrada com sucesso.", $epi);
            }

            return JSONResponse::JSONReturn(false, 401, "Nenhum EPI cadastrado.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function GetAll($company_uuid)
    {
        try {
            $logistic = Epi::where('company_uuid', $company_uuid)->get() ?? null;

            if($logistic) {
                return JSONResponse::JSONReturn(true, 200, "Lista de EPIs.", $logistic);
            }

            return JSONResponse::JSONReturn(false, 401, "Nenhum EPI cadastrado.", null);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function edit($uuid, $data, $company_uuid)
    {
        try {

            $response = Epi::where('company_uuid', $company_uuid)
                ->where('uuid', $uuid)->update($data);

            if($response) {
                return JSONResponse::JSONReturn(true, 200, "EPI editado com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 401, "Ocorreu algum erro ao editar o EPI, tente novamente.", null);

            } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function delete($uuid, $company_uuid)
    {
        try {
            $response = Epi::where('company_uuid', $company_uuid)
                ->where('uuid', $uuid)
                ->delete();

            if ($response) {
                return JSONResponse::JSONReturn(true, 200, "EPI excluído com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 404, "EPI não encontrado ou não pôde ser excluído.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }
}
