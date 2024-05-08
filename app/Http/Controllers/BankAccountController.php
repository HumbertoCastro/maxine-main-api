<?php

namespace App\Http\Controllers;

use App\Services\BankAccountService;
use App\Services\TokenService;
use App\Util\JSONResponse;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    private $company_uuid;

    /**
     * Metodo construtor que estrai o company_uuid
     * do token.
     */
    public function __construct(Request $request)
    {
        $header = $request->header('Authorization') ?? null;

        if(!$header)
        {
            return JSONResponse::JSONReturn(false, 401, "Autenticação necessária", null);
        }

        $token = explode(' ', $header)[1];

        $this->company_uuid = TokenService::GetCompanyUuidByToken($token);


    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BankAccountService::GetAll($this->company_uuid);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fiedset = $request->validate([
            'account_number' => 'required|numeric',
            'account_type' => 'required|string',
            'account_holder' => 'required|string',
            'cpf_cnpj' => 'required|string|digits_between:11,14',
            'bank' => 'required|string',
            'agency' => 'required|string',
            'balance' => 'string',
            'company_uuid' => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        return BankAccountService::Register($fiedset);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $account_number)
    {
        return BankAccountService::GetAccountByNumber($this->company_uuid, $account_number);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
