<?php

namespace App\Services;

use App\Models\Labor;
use App\Util\JSONResponse;

class LaborService
{

    public static function Register($labor, $company_uuid)
    {
        try {

            $labor = Labor::create([
                'uuid' => md5(uniqid(rand() . "", true)),
                'office' => $labor['office'],
                'departament' => $labor['departament'],
                'wage' => $labor['wage'],
                'healthiness' => $labor['healthiness'],
                'type' => $labor['type'],
                'company_uuid' => $company_uuid
            ]);

            if ($labor) {
                return JSONResponse::JSONReturn(true, 200, "Mão de obra cadastrada com sucesso.", $labor);
            }

            return JSONResponse::JSONReturn(false, 401, "Erro ao cadastrar mão de obra", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function GetAll($company_uuid)
    {
        try {

            $labor = Labor::where('company_uuid', $company_uuid)->get() ?? null;

            if ($labor) {
                return JSONResponse::JSONReturn(true, 200, "Lista de Mão de Obra Cadastradas", $labor);
            }

            return JSONResponse::JSONReturn(false, 401, "Erro ao buscar lista", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function edit($uuid, $labor, $company_uuid)
    {
        try {

            $response = Labor::where('company_uuid', $company_uuid)
                ->where('uuid', $uuid)->update($labor);

            if ($response) {
                return JSONResponse::JSONReturn(true, 200, "Mão de Obra editado com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 401, "Ocorreu algum erro ao editar Mão de Obra, tente novamente.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function delete($uuid, $company_uuid)
    {
        try {
            $response = Labor::where('company_uuid', $company_uuid)
                ->where('uuid', $uuid)
                ->delete();

            if ($response) {
                return JSONResponse::JSONReturn(true, 200, "Mão de Obra excluída com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 404, "Mão de Obra não encontrada ou não pôde ser excluída.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }
}
