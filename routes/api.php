<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\EpiController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LaborController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ReleasesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function () {
    Route::get('user', 'index');
    Route::patch('user', 'update');
    Route::get('login-info/{email}', 'getLoginInfo');
    Route::post('user', 'store');
    Route::post('user-root', 'storeUserRoot');
    Route::get('user-root/{email}', 'getUserRootByEmail');

});

Route::controller(AuthController::class)->group(function () {
    Route::post('auth', 'login');
    Route::get('user-root/{email}', 'getUserRootByEmail');
});

Route::middleware(AuthMiddleware::class)->controller(UserController::class)->group(function () {
    Route::get('user-info/{email}', 'getUserInfo');
} );

Route::controller(CompanyController::class)->group(function () {
    Route::post('company', 'store');
    // Route::get('company/{email}', 'getCompanyByEmail');
    Route::get('company/{uuid}', 'show');

});

Route::middleware(AuthMiddleware::class)->controller(CostumerController::class)->group(function () {
    Route::post('costumer', 'store');
    Route::get('costumer', 'index');
    Route::get('costumer/{uuid}', 'show');
    Route::patch('costumer/{uuid}', 'update');
});

Route::middleware(AuthMiddleware::class)->controller(SupplierController::class)->group(function () {
    Route::post('supplier', 'store');
    Route::get('supplier', 'index');
    Route::get('supplier/{id}', 'show');
    Route::patch('supplier/{uuid}', 'update');
});

Route::middleware(AuthMiddleware::class)->controller(BankAccountController::class)->group(function () {
    Route::post('bank-account', 'store');
    Route::get('bank-account', 'index');
    Route::get('bank-account/{account_number}', 'show');
});

Route::middleware(AuthMiddleware::class)->controller(PaymentMethodController::class)->group(function () {
    Route::post('payment-method', 'store');
    Route::get('payment-method', 'index');
    Route::get('payment-method/{account_number}', 'show');
});

Route::middleware(AuthMiddleware::class)->controller(ReleasesController::class)->group(function () {
    Route::post('releases/income', 'income');
    Route::post('releases/expense', 'expense');
    Route::get('releases', 'index');
    Route::get('releases/{account_number}', 'show');
    Route::get('releases/get/data-accounts-methods', 'getDataAccountsAndMethods');
});

Route::middleware(AuthMiddleware::class)->controller(LaborController::class)->group(function () {
    Route::post('labor', 'store');
    Route::get('labor', 'index');
    Route::patch('labor/{uuid}', 'update');
    Route::delete('labor/{uuid}', 'destroy');
});

Route::middleware(AuthMiddleware::class)->controller(EquipmentController::class)->group(function () {
    Route::post('equipment', 'store');
    Route::get('equipment', 'index');
    Route::patch('equipment/{uuid}', 'update');
    Route::delete('equipment/{uuid}', 'destroy');
});

Route::middleware(AuthMiddleware::class)->controller(EpiController::class)->group(function () {
    Route::post('epi', 'store');
    Route::get('epi', 'index');
    Route::patch('epi/{uuid}', 'update');
    Route::delete('epi/{uuid}', 'destroy');
});

Route::middleware(AuthMiddleware::class)->controller(ConsumableController::class)->group(function () {
    Route::post('consumable', 'store');
    Route::get('consumable', 'index');
    Route::patch('consumable/{uuid}', 'update');
    Route::delete('consumable/{uuid}', 'destroy');
});

Route::middleware(AuthMiddleware::class)->controller(LogisticController::class)->group(function () {
    Route::post('logistic', 'store');
    Route::get('logistic', 'index');
    Route::patch('logistic/{uuid}', 'update');
    Route::delete('logistic/{uuid}', 'destroy');
});

Route::middleware(AuthMiddleware::class)->controller(TaxController::class)->group(function () {
    Route::post('tax', 'store');
    Route::get('tax', 'index');
    Route::patch('tax/{uuid}', 'update');
    Route::delete('tax/{uuid}', 'destroy');
});
