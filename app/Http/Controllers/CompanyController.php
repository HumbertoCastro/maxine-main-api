<?php

namespace App\Http\Controllers;

use App\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
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

        $fieldsetd = $request->validate([
            'company' => 'required|string',
            'email' => 'required|email',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cpf_cnpj' => 'required|string|digits_between:11,14',
            'is_active' => 'required|boolean'
        ]);

        $reponse = CompanyService::RegisterCompany($fieldsetd);

        return $reponse;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        return CompanyService::GetCompanyByUuid($uuid);
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
