<?php

namespace App\Services;

use App\Models\Supplier;
use App\Util\JSONResponse;

class SupplierService
{

    public static function Register($data)
    {
        $check_cnpj = self::CheckCnpj($data['company_uuid'], $data['cnpj']);

        if ($check_cnpj) {
            return JSONResponse::JSONReturn(false, 401, "CNPJ já cadastrado.", null);
        }

        try {
            $supplier = Supplier::create([
                'uuid' => md5(uniqid(rand() . "", true)),
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'cnpj' => $data['cnpj'],
                'address' => $data['address'],
                'number' => $data['number'],
                'district' => $data['district'],
                'city' => $data['city'],
                'uf' => $data['uf'],
                'cep' => $data['cep'],
                'observation' => $data['observation'],
                'company_uuid' => $data['company_uuid'],
                'is_active' => $data['is_active']
            ]);

            if ($supplier) {
                return JSONResponse::JSONReturn(true, 200, "Fornecedor cadastrado com sucesso", $supplier);
            }

            return JSONResponse::JSONReturn(false, 401, "Ocorreu algum erro ao cadastrar o fornecedor, tente novamente.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Internal Server Error", throw $th);
        }
    }

    public static function GetAll($compnay_uuid)
    {
        try {
            $suppliers = Supplier::where('company_uuid', $compnay_uuid)->get() ?? null;

            if ($suppliers) {
                return JSONResponse::JSONReturn(true, 200, "Lista de fornecedores", $suppliers);
            }

            return JSONResponse::JSONReturn(true, 200, "Não há fornecedores cadastrados.", []);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Internal Server Error", throw $th);
        }
    }

    public static function GetSupplierByNameOrCnpj($company_uuid, $keyword)
    {
        try {
            $supplier = Supplier::where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%$keyword%")
                    ->orWhere('cnpj', $keyword);
            })
                ->where('company_uuid', $company_uuid) // Adicione o filtro company_uuid, se necessário
                ->first() ?? null;

            if($supplier) {
                return JSONResponse::JSONReturn(true, 200, "Resultado", $supplier);
            }

            return JSONResponse::JSONReturn(true, 200, "Não há fornecedor cadastrado.", []);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Internal Server Error", throw $th);
        }
    }

    public static function edit($supplier, $uuid)
    {
        try {

            $response = Supplier::where('company_uuid', $supplier['company_uuid'])
                ->where('uuid', $uuid)->update($supplier);

            if ($response) {
                return JSONResponse::JSONReturn(true, 200, "Cliente editado com sucesso.", null);
            }

            return JSONResponse::JSONReturn(false, 401, "Ocorreu algum erro ao editar o cliente, tente novamente.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Error", $th->getMessage());
        }
    }

    private static function CheckCnpj($compnay_uuid, $cnpj)
    {
        $checked = Supplier::where('company_uuid', $compnay_uuid)
            ->where('cnpj', $cnpj)->first() ?? null;

        if (!$checked) {
            return false;
        }
        return true;
    }
}
