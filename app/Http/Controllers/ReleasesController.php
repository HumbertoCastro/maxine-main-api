<?php

namespace App\Http\Controllers;

use App\Services\ReleasesService;
use App\Services\TokenService;
use App\Util\JSONResponse;
use Illuminate\Http\Request;

class ReleasesController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

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

    public function income(Request $request)
    {
        $fieldset = $request->validate([
            'account_uuid' => 'required|string',
            'amount' => 'required|string',
            'type' => 'required|string',
            'description' => 'string',
            'user_uuid' => 'required|string',
            'payment_method_uuid' => 'required|string'
        ]);

        return ReleasesService::RegisterIncome($this->company_uuid ,$fieldset);

    }

    public function expense(Request $request)
    {
        $fieldset = $request->validate([
            'account_uuid' => 'required|string',
            'amount' => 'required|string',
            'type' => 'required|string',
            'description' => 'string',
            'user_uuid' => 'required|string',
            'payment_method_uuid' => 'required|string'
        ]);

        return ReleasesService::RegisterExpense($this->company_uuid, $fieldset);

    }

    public function getDataAccountsAndMethods()
    {
        return ReleasesService::GetBankAccountsAndPaymenthMethodsByUuid($this->company_uuid);
    }
}
