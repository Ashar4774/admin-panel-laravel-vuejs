<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\ClientController;
use App\Http\Controllers\API\v1\InvoiceController;
use App\Http\Controllers\API\v1\StateOfAccountController;
use App\Http\Controllers\API\v1\DashboardController;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login'])->name('login');
//Route::apiResource('clients', ClientController::class);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/dashboard/getInfo', [DashboardController::class, 'index']);
    Route::get('checkAuthStatus', [AuthController::class, 'checkAuthStatus'])->name('checkAuthStatus');
//    Route::post('clients/store', [ClientController::class, 'store']);
    Route::post('clients/import', [ClientController::class, 'import'])->name('clients.import');
    Route::get('clients/state_of_account/{id}', [ClientController::class, 'state_of_account'])->name('clients.state_of_account');
    Route::apiResource('clients', ClientController::class);

    Route::get('invoices/getInvoices', [InvoiceController::class, 'getInvoices'])->name('getInvoices');
    Route::get('invoices/fetchClients', [InvoiceController::class, 'fetchClients'])->name('fetchClients');
    Route::post('invoices/import', [InvoiceController::class, 'import'])->name('invoices.import');
    Route::apiResource('invoices', InvoiceController::class);

    Route::get('/state_of_account/show/{ref_no}', [StateOfAccountController::class, 'show']);
    Route::get('/user_profile', [DashboardController::class, 'user_profile']);
    Route::post('/update_profile_image', [DashboardController::class, 'update_profile_image']);
    Route::post('/update_profile', [DashboardController::class, 'update_profile']);
    Route::post('/update_password', [DashboardController::class, 'update_password']);

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
