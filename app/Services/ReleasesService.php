<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\PaymentMethod;
use App\Models\Releases;
use App\Util\JSONResponse;
use Illuminate\Support\Facades\DB;

class ReleasesService
{

    /**
     * Método que registra o lançamento.
     */
    public static function RegisterIncome($company_uuid, $data)
    {
        if (!self::CheckPaymentMethodExists($data['payment_method_uuid'])) {
            return JSONResponse::JSONReturn(false, 401, "Método de pagamento não registrado", null);
        }

        try {
            $income = Releases::create([
                'uuid' => md5(uniqid(rand() . "", true)),
                'account_uuid' => $data['account_uuid'],
                'amount' => $data['amount'],
                'type' => $data['type'],
                'description' => $data['description'],
                'company_uuid' => $company_uuid,
                'user_uuid' => $data['user_uuid'],
                'payment_method_uuid' => $data['payment_method_uuid']
            ]);

            if ($income) {
                $make_deposit = self::MakeADepositIntoTheAccount($company_uuid, $data);

                if ($make_deposit) {
                    return JSONResponse::JSONReturn(true, 200, "Lençamento registrado com sucesso.", $income);
                }

                return JSONResponse::JSONReturn(true, 200, "Lençamento registrado com sucesso.", $income);
            }

            return JSONResponse::JSONReturn(false, 401, "Lençamento registrado com sucesso.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    /**
     * Método que faz a retirada da conta.
     */
    public static function RegisterExpense($company_uuid, $data)
    {
        if (!self::CheckPaymentMethodExists($data['payment_method_uuid'])) {
            return JSONResponse::JSONReturn(false, 401, "Método de pagamento não registrado", null);
        }

        try {
            $income = Releases::create([
                'uuid' => md5(uniqid(rand() . "", true)),
                'account_uuid' => $data['account_uuid'],
                'amount' => $data['amount'],
                'type' => $data['type'],
                'description' => $data['description'],
                'company_uuid' => $company_uuid,
                'user_uuid' => $data['user_uuid'],
                'payment_method_uuid' => $data['payment_method_uuid']
            ]);

            if ($income) {
                $make_deposit = self::MakeAWithdrawalFromTheAccount($company_uuid, $data);

                if ($make_deposit) {
                    return JSONResponse::JSONReturn(true, 200, "Lençamento registrado com sucesso.", $income);
                }

                return JSONResponse::JSONReturn(true, 200, "Lençamento registrado com sucesso.", $income);
            }

            return JSONResponse::JSONReturn(false, 401, "Lençamento registrado com sucesso.", null);
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    private static function CheckPaymentMethodExists($payment_method_uuid)
    {
        try {
            $checked = PaymentMethod::where('uuid', $payment_method_uuid)->first() ?? null;

            if (!$checked) {
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro", $th->getMessage());
        }
    }

    /**
     * Método que faz o deposito na conta bancaria selecionada
     */
    private static function MakeADepositIntoTheAccount($company_uuid, $data)
    {
        try {
            $response = BankAccount::where('company_uuid', $company_uuid)
                ->where('uuid', $data['account_uuid'])
                ->increment('balance', $data['amount']);

            if ($response !== false) {
                return true;
            }

            return false;
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro ao fazer depósito na conta bancária.", $th->getMessage());
        }
    }

    /**
     * Método que retorna dados das contas bancárias e metodos de pagamentosm
     * para montar a tela de lançamentos e retiradas.
     */
    public static function GetBankAccountsAndPaymenthMethodsByUuid($company_uuid)
    {
        $accounts = BankAccount::where('company_uuid', $company_uuid)->get() ?? null;
        $methods = PaymentMethod::where('company_uuid', $company_uuid)->get() ?? null;

        if($accounts && $methods)  {

            $response = ['accounts' => $accounts, 'methods' => $methods];

            return JSONResponse::JSONReturn(true, 200, "Contas e metodos", $response);
        }
    }

    /**
     * Método que faz o saque e atualiza o saldo da conta bancária.
     */
    private static function MakeAWithdrawalFromTheAccount($company_uuid, $data)
    {
        $check_balance = self::CheckSufficientBalanceForWithdrawal($data['account_uuid'], $company_uuid, $data['amount']);

        try {
            $response = BankAccount::where('company_uuid', $company_uuid)
                ->where('uuid', $data['account_uuid'])
                ->decrement('balance', $data['amount']);

            if ($response !== false) {
                return true;
            }

            return false;
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro ao fazer saque na conta bancária.", $th->getMessage());
        }
    }

    /**
     * Método que verifica se a saldo suficiente na conta para o saque.
     */
    private static function CheckSufficientBalanceForWithdrawal($account_uuid, $company_uuid, $amount)
    {
        try {
            $account = BankAccount::where('company_uuid', $company_uuid)
                ->where('uuid', $account_uuid)
                ->first() ?? null;

            if ($account) {
                // Verifica se o saldo disponível é suficiente para o saque
                if ($account->balance >= $amount) {
                    return true; // Saldo suficiente para o saque
                } else {
                    return false; // Saldo insuficiente para o saque
                }
            }

            return false; // Conta bancária não encontrada
        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(false, 500, "Erro ao verificar saldo na conta bancária.", $th->getMessage());
        }
    }
}
