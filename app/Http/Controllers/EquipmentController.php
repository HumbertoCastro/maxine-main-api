<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Services\EquipmentService;
use App\Services\TokenService;
use App\Util\JSONResponse;
use Illuminate\Http\Request;

class EquipmentController extends Controller
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
        return EquipmentService::GetAll($this->company_uuid);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fieldset = $request->validate([
            'category' => 'string|required',
            'name' => 'string|required',
            'description' => 'string|required',
            'unit'=> 'string|required',
            'property' => 'string|required',
            'unitary_value' => 'string|required'
        ]);

        $response = EquipmentService::Register($fieldset, $this->company_uuid);

        return $response;
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
    public function update(Request $request, string $uuid)
    {
        $fieldset = $request->validate([
            'category' => 'string|required',
            'name' => 'string|required',
            'description' => 'string|required',
            'unit'=> 'string|required',
            'property' => 'string|required',
            'unitary_value' => 'string|required'
        ]);

        return EquipmentService::edit($uuid, $fieldset, $this->company_uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $response = EquipmentService::delete($uuid, $this->company_uuid);
        return $response;
    }
}
