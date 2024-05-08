<?php

namespace App\Http\Controllers;

use App\Services\TokenService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
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
        return UserService::GetUsers($this->company_uuid);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fieldset = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|string',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'roles' => 'required|numeric',
            'is_admin' => 'required|boolean',
            'is_active' => 'required|boolean',
            'company_uuid' => 'required|string'
        ]);

        $imageName = null;
        //Verifica se existe a imagem.
        if (array_key_exists('photo_url', $fieldset)) {
            $imageName = time() . '.' . $fieldset['photo_url']->extension();
            $upload = $request->photo_url->storeAs('public/photo_url', $imageName);
            $imageName = "/storage/photo_url/$imageName";
        }

        print('before');
        // $i = Storage::url('companyLogos/1670630935.png');

        $response = UserService::registerUser($fieldset, $imageName);

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
    public function update(Request $request)
    {
        $fieldset = $request->validate([
            'uuid' => 'required|string',
            'is_active' => 'required|numeric',
            'roles' => 'required|numeric',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);
        return UserService::edit($fieldset);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public static function getUserRootByEmail($email): JsonResponse
    {

        $response = UserService::getUserByEmail($email);

        return $response;
    }

    public function getUserInfo($email)
    {
        return UserService::getUserInfo($email);
    }
}
