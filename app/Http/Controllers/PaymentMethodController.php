<?php

namespace App\Http\Controllers;

use App\Services\CostumerService;
use App\Services\PaymentMethodService;
use App\Services\TokenService;
use App\Util\JSONResponse;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    private $company_uuid;

    /**
     * Metodo construtor que extrai o company_uuid
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
        return PaymentMethodService::GetMethods($this->company_uuid);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fildset = $request->validate([
            'name' => 'required|string',
            'description' => 'string'
        ]);

        return PaymentMethodService::Register($fildset, $this->company_uuid);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
