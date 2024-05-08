<?php

namespace App\Services;

use App\Models\Costumer;
use App\Util\JSONResponse;

class CostumerService
{
    private $company_uuid;

    public function __construct()
    {
    }

    public static function Register($costumer)
    {
        //formata a data para padrão americano.
        $date = str_replace('/', '-', $costumer['birth_date']);
        $birth_date = date('Y-m-d', strtotime($date));

        $check_cpf = self::veryifyCpfExixts($costumer['company_uuid'], $costumer['cpf']);

        if ($check_cpf) {
            return JSONResponse::JSONReturn(false, 401, "CPF já cadastrado", null);
        }

        try {
            $costumer =  Costumer::create([
                'uuid' => md5(uniqid(rand() . "", true)),
                'name' => $costumer['name'],
                'email' => $costumer['email'],
                'phone' => $costumer['phone'],
                'birth_date' => $birth_date,
                'sex' => $costumer['sex'],
                'cpf' => $costumer['cpf'],
                'address' => $costumer['address'],
                'district' => $costumer['district'],
                'city' => $costumer['city'],
                'cep' => $costumer['cep'],
                'uf' => $costumer['uf'],
                'is_active' => $costumer['is_active'],
                'company_uuid' => $costumer['company_uuid']
            ]);

            if ($costumer) {
                return JSONResponse::JSONReturn(true, 200, "Cliente cadastrado com sucesso.", $costumer);
            }

            // return $costumer->toArray();
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro ao cadastrar cliente", $th);
        }
    }

    public function GetAllCostumers($token)
    {
        $this->company_uuid = TokenService::GetCompanyUuidByToken($token);

        try {
            $costumers = Costumer::where('company_uuid', $this->company_uuid)->get() ?? null;

            if ($costumers) {
                return JSONResponse::JSONReturn(true, 200, "Clientes Cadastrados", $costumers);
            }
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Error Costumer", throw $th);
        }
    }

    public static function GetCostumerByUuid($uuid)
    {
        try {
            // $costumer = Costumer::where('uuid', $uuid)->where('company_uuid', $company_uuid)->first() ?? null;
            $costumer = Costumer::where('uuid', $uuid)->first() ?? null;

            if ($costumer) {
                return JSONResponse::JSONReturn(true, 200, "Cliente por uuid.", $costumer);
            }

            return JSONResponse::JSONReturn(true, 401, "Cliente não encontrado.", $costumer);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Error, get costumer by UUID.", throw $th);
        }
    }

    private static function veryifyCpfExixts($company_uuid, $cpf)
    {
        try {
            $response = Costumer::where('company_uuid', $company_uuid)
                ->where('cpf', $cpf)->first() ?? null;
            if ($response) {
                return true;
            }

            return false;
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Error", $th->getMessage());
        }
    }

    public static function edit($costumer, $uuid)
    {
        // dd($costumer);
        try {

            $response = Costumer::where('company_uuid', $costumer['company_uuid'])
                ->where('uuid', $uuid)->update($costumer);

            if ($response) {
                return JSONResponse::JSONReturn(true, 200, "Cliente editado com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 401, "Ocorreu algum erro ao editar o cliente, tente novamente.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Error", $th->getMessage());
        }
    }
}
