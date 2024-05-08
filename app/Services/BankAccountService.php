<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Util\JSONResponse;

class BankAccountService
{

    public static function Register($data)
    {
        $check = self::CheckNumberAccount($data['company_uuid'], $data['account_number']);

        if ($check) {
            return JSONResponse::JSONReturn(false, 401, "Conta já cadastrada", null);
        }

        try {
            $account = BankAccount::create([
                'account_number' => $data['account_number'],
                'account_type' => $data['account_type'],
                'account_holder' => $data['account_holder'],
                'cpf_cnpj' => $data['cpf_cnpj'],
                'bank' => $data['bank'],
                'agency' => $data['agency'],
                'balance' => $data['balance'],
                'company_uuid' => $data['company_uuid'],
                'is_active' => $data['is_active']
            ]);

            if ($account) {
                return JSONResponse::JSONReturn(true, 200, "Conta cadastrada com sucesso.", $account);
            }

            return JSONResponse::JSONReturn(false, 401, "Ocorreu alum erro ao cadastrar a conta, tente novamente.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Error", throw $th);
        }
    }

    public static function GetAll($company_uuid)
    {
        try {
            $accounts = BankAccount::where('company_uuid', $company_uuid)->get() ?? null;

            if ($accounts) {
                return JSONResponse::JSONReturn(true, 200, "Contas cadastradas", $accounts);
            }

            return JSONResponse::JSONReturn(false, 200, "Não ha contas cadastradas", null);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Error", throw $th);
        }
    }

    public static function GetAccountByNumber($company_uuid, $account_number)
    {
        try {
            $account = BankAccount::where('company_uuid', $company_uuid)
                        ->where('account_number', $account_number)->first() ?? null;

            if ($account) {
                return JSONResponse::JSONReturn(true, 200, "Dados da conta", $account);
            }

            return JSONResponse::JSONReturn(false, 200, "Conta bancáira não encontrada", null);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Error", throw $th);
        }
    }

    private static function CheckNumberAccount(string $company_uuid, $account_number)
    {
        $check = BankAccount::where('company_uuid', $company_uuid)
            ->where('account_number', $account_number)->first() ?? null;

        if (!$check) {
            return false;
        }

        return true;
    }
}
