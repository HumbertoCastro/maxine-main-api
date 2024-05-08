<?php

namespace App\Http\Controllers;

use App\Services\CostumerService;
use App\Services\UserService;
use App\Util\CheckToken;
use App\Util\JSONResponse;
use App\Util\JWT\CheckToken as JWTCheckToken;
use Illuminate\Http\Request;

class CostumerController extends Controller
{

    private $costumerService;

    public function __construct(CostumerService $costumerService)
    {
        $this->costumerService = $costumerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return JSONResponse::JSONReturn(false, 401, "Não Autenticado.", []);
        }

        $response = $this->costumerService->GetAllCostumers($token);

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fieldset = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'birth_date' => 'required|date',
            'sex' => 'required|string',
            'cpf' => 'required|string',
            'address' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'cep' => 'required|string',
            'uf' => 'required|string',
            'is_active' => 'required|boolean',
            'company_uuid' => 'required|string'
        ]);

        $respone = CostumerService::Register($fieldset);

        return $respone;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        if (!$uuid) {
            return JSONResponse::JSONReturn(false, 500, "UUID necessário!", null);
        }

        $respone = CostumerService::GetCostumerByUuid($uuid);

        return $respone;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {

        $fieldset = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'birth_date' => 'required|date',
            'sex' => 'required|string',
            'cpf' => 'required|numeric',
            'address' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'cep' => 'required|numeric',
            'uf' => 'required|string',
            'is_active' => 'required|boolean',
            'company_uuid' => 'required|string'
        ]);

        $date = str_replace('/', '-', $fieldset['birth_date']);
        $birth_date = date('Y-m-d', strtotime($date));

        $fieldset['birth_date'] = $birth_date;

        return CostumerService::edit($fieldset, $uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
