<?php

namespace App\Services;

use App\Models\Equipment;
use App\Util\formatedValueToDecimal;
use App\Util\JSONResponse;

class EquipmentService {

    public static function Register($equipment, $company_uuid)
    {
        // $decimalValue = formatedValueToDecimal::formatNumber($equipment['unitary_value']);
        $number = str_replace(',', '.', str_replace('.', '', $equipment['unitary_value']));

        try {
            $equipment = Equipment::create([
                'uuid' => md5(uniqid(rand() . "", true)),
                'category' => $equipment['category'],
                'name' => $equipment['name'],
                'description' => $equipment['description'],
                'unit' => $equipment['unit'],
                'property' => $equipment['property'],
                'unitary_value' => $number,
                'company_uuid' => $company_uuid
            ]);

            if($equipment) {
                return JSONResponse::JSONReturn(true, 200, "Equipamento cadastrada com sucesso.", $equipment);
            }

            return JSONResponse::JSONReturn(false, 401, "Erro ao cadastrar equipamento", null);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function GetAll($company_uuid)
    {
        try {
            $equipments = Equipment::where('company_uuid', $company_uuid)->get() ?? null;

            if($equipments) {
                return JSONResponse::JSONReturn(true, 200, "Lista de equipamentos.", $equipments);
            }

            return JSONResponse::JSONReturn(false, 401, "Nenhum equipamento cadastrado.", null);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function edit($uuid, $data, $company_uuid)
    {
        try {

            $response = Equipment::where('company_uuid', $company_uuid)
                ->where('uuid', $uuid)->update($data);

            if($response) {
                return JSONResponse::JSONReturn(true, 200, "Equipamento editado com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 401, "Ocorreu algum erro ao editar o Equipemento, tente novamente.", null);

            } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    public static function delete($uuid, $company_uuid)
    {
        try {
            $response = Equipment::where('company_uuid', $company_uuid)
                ->where('uuid', $uuid)
                ->delete();

            if ($response) {
                return JSONResponse::JSONReturn(true, 200, "Equipamento excluído com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 404, "Equipamento não encontrado ou não pôde ser excluído.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

}
