<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use App\Services\TokenService;
use App\Util\JSONResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
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
        return SupplierService::GetAll($this->company_uuid);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fildset = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'cnpj' => 'required|numeric',
            'address' => 'required|string',
            'number' => 'required|numeric',
            'district' => 'required|string',
            'city' => 'required|string',
            'uf' => 'required|string',
            'cep' => 'required|numeric',
            'observation' => 'string',
            'company_uuid' => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        $response = SupplierService::Register($fildset);

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return SupplierService::GetSupplierByNameOrCnpj($this->company_uuid, $id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $fildset = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'cnpj' => 'required|numeric',
            'address' => 'required|string',
            'number' => 'required|numeric',
            'district' => 'required|string',
            'city' => 'required|string',
            'uf' => 'required|string',
            'cep' => 'required|numeric',
            'observation' => 'string',
            'company_uuid' => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        return SupplierService::edit($fildset, $uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
