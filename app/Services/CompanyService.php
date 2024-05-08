<?php

namespace App\Services;

use App\Models\Company;
use App\Util\JSONResponse;

class CompanyService {

    public static function RegisterCompany($company)
    {
        //verifica se já existe email cadastrado.
        if(self::CheckEmail($company['email'])) {
            return JSONResponse::JSONReturn(false, 401, "E-mail já cadastrado.", null);
        }

        if(self::checkCpfCnpjExists($company['cpf_cnpj']))
        {
            return JSONResponse::JSONReturn(false, 401, "CPF ou CNPJ já cadastrado", null);
        }

        try {
            $company = Company::create([
                'uuid' => md5(uniqid(rand() . "", true)),
                'company' => $company['company'],
                'email' => $company['email'],
                'logo_url' => $company['logo_url'],
                'cpf_cnpj' => $company['cpf_cnpj'],
                'is_active' => $company['is_active']
            ]);

            if($company) {
                return JSONResponse::JSONReturn(true, 200, "Empresa cadastrada com sucesso.", $company);
            }

            return JSONResponse::JSONReturn(False, 401, "Erro ao cadastrar empresa, tente novamente", null);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(False, 500, "Erro do sistema", $th);
        }

    }

    public static function CheckEmail(string $email)
    {
        $checked = Company::where('email', $email)-> first() ?? null;

        if(!$checked) {
            return false;
        }

        return true;
    }

    public static function checkCpfCnpjExists($cpfCnpj)
    {
        // Verifica se existe um registro com o CPF/CNPJ fornecido na tabela companies
        $company = Company::where('cpf_cnpj', $cpfCnpj)->first();

        if ($company) {
            return true; // Retorna verdadeiro se o CPF/CNPJ existir na tabela companies
        } else {
            return false; // Retorna falso se o CPF/CNPJ não existir na tabela companies
        }
    }

    public static function GetCompanyByUuid($uuid)
    {
        try {

            $company =  Company::where('uuid', $uuid)->first() ?? null;

            if($company) {
                return JSONResponse::JSONReturn(true, 200, "Dados da empresa", $company);
            }

            return JSONResponse::JSONReturn(false, 401, "Erro ao buscar empresa", null);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(False, 500, "Erro do sistema", $th);
        }
    }

}
