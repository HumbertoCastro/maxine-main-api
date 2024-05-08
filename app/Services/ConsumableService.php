<?php

namespace App\Services;

use App\Models\Consumable;
use App\Util\JSONResponse;

class ConsumableService
{

    public static function Register($epi, $company_uuid)
    {
        try {
            $number = str_replace(',', '.', str_replace('.', '', $epi['unitary_value']));


            $epi = Consumable::create([
                'uuid' => md5(uniqid(rand() . "", true)),
                'category' => $epi['category'],
                'name' => $epi['name'],
                'description' => $epi['description'],
                'unit' => $epi['unit'],
                'unitary_value' => $number,
                'company_uuid' => $company_uuid
            ]);

            if ($epi) {
                return JSONResponse::JSONReturn(true, 200, "Consumivel cadastrado com sucesso.", $epi);
            }

            return JSONResponse::JSONReturn(false, 401, "Nenhum Consumivel cadastrado.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function GetAll($company_uuid)
    {
        try {
            $consumables = Consumable::where('company_uuid', $company_uuid)->get() ?? null;

            if($consumables) {
                return JSONResponse::JSONReturn(true, 200, "Lista de consumíveis.", $consumables);
            }

            return JSONResponse::JSONReturn(false, 401, "Nenhum consumível cadastrado.", null);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function edit($uuid, $data, $company_uuid)
    {
        try {

            $response = Consumable::where('company_uuid', $company_uuid)
                ->where('uuid', $uuid)->update($data);

            if($response) {
                return JSONResponse::JSONReturn(true, 200, "Consumivel editado com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 401, "Ocorreu algum erro ao editar o Consumivel, tente novamente.", null);

            } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function delete($uuid, $company_uuid)
    {
        try {
            $response = Consumable::where('company_uuid', $company_uuid)
                ->where('uuid', $uuid)
                ->delete();

            if ($response) {
                return JSONResponse::JSONReturn(true, 200, "Consumivel excluída com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 404, "Consumivel não encontrada ou não pôde ser excluída.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }
}
