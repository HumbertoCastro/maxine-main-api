<?php

namespace App\Services;

use App\Models\Tax;
use App\Util\JSONResponse;

class TaxService {

    public static function Register($tax, $company_uuid)
    {
        try {
            $number = str_replace(',', '.', $tax['aliquot']);

            $tax = Tax::create([
                'uuid' => md5(uniqid(rand() . "", true)),
                'type' => $tax['type'],
                'acronym' => $tax['acronym'],
                'name' => $tax['name'],
                'description' => $tax['description'],
                'aliquot' => $number,
                'company_uuid' => $company_uuid
            ]);

            if ($tax) {
                return JSONResponse::JSONReturn(true, 200, "Imposto cadastrado com sucesso.", $tax);
            }

            return JSONResponse::JSONReturn(false, 401, "Erro ao cadastrar Imposto.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function GetAll($company_uuid)
    {
        try {
            $logistic = Tax::where('company_uuid', $company_uuid)->get() ?? null;

            if($logistic) {
                return JSONResponse::JSONReturn(true, 200, "Lista de Impostos.", $logistic);
            }

            return JSONResponse::JSONReturn(false, 401, "Nenhum Imposto cadastrado.", null);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function edit($uuid, $data, $company_uuid)
    {
        try {

            $response = Tax::where('company_uuid', $company_uuid)
                ->where('uuid', $uuid)->update($data);

            if($response) {
                return JSONResponse::JSONReturn(true, 200, "Imposto editado com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 401, "Ocorreu algum erro ao editar o Imposto, tente novamente.", null);

            } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function delete($uuid, $company_uuid)
    {
        try {
            $response = Tax::where('company_uuid', $company_uuid)
                ->where('uuid', $uuid)
                ->delete();

            if ($response) {
                return JSONResponse::JSONReturn(true, 200, "Imposto excluído com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 404, "Imposto não encontrado ou não pôde ser excluído.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }
}
