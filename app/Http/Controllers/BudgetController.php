<?php

namespace App\Http\Controllers;

use App\Services\TokenService;
use App\Util\JSONResponse;
use Illuminate\Http\Request;

class BudgetController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fieldset = $request->validate([
            'budget_name' => 'string|required',
            'budget_labor' => 'json|required',
            'budget_equipment' => 'json|required',
            'budget_epi' => 'json|required',
            'budget_consumable' => 'json|required',
            'budget_logistic' => 'json|required',
            'budget_tax' => 'json|required',
            'subtotal_itens' => 'string|required',
            'safety_margin' => 'string|required',
            'central_administration' => 'string|required',
            'negotiation' => 'string|required',
            'markup' => 'string|required',
            'total_value' => 'string|required',
            'total_tax' => 'string|required',
            'total_budget' => 'string|required'
        ]);
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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}
