<?php

namespace App\Services;

use App\Models\PaymentMethod;
use App\Util\JSONResponse;

class PaymentMethodService {

    public static function Register($data, String $company_uuid)
    {
        if(self::CheckMethodExists($company_uuid, $data['name'])) {
            return JSONResponse::JSONReturn(401, false, "Método de pagamento já registrado.", null);
        }

        try {

            $method = PaymentMethod::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'company_uuid' => $company_uuid
            ]);

            if($method) {
                return JSONResponse::JSONReturn(200, true, "Método de pagamento registrado com sucesso.", $method);
            }

            return JSONResponse::JSONReturn(401, false, "Ocoreu algum erro ao registra o método de pagamento, tente novamente.", null);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(500, false, "Erro.", throw $th);

        }
    }

    public static function GetMethods($company_uuid)
    {
        try {
            $methods = PaymentMethod::where('company_uuid', $company_uuid)->get() ?? null;

            if($methods) {
                return JSONResponse::JSONReturn(true, 200, "Métodos de pagamentos cadastrados.", $methods);
            }

            return JSONResponse::JSONReturn(401, false, "Nã tem métodos de pagamento registrados.", []);

        } catch (\Throwable $th) {
            return JSONResponse::JSONReturn(500, false, "Erro.", throw $th);
        }
    }

    private static function CheckMethodExists($company_uuid, $name)
    {
        $response = PaymentMethod::where('company_uuid', $company_uuid)
                        ->where('name', $name)->first() ?? null;

        if(!$response) {
            return false;
        }

        return true;
    }


}
