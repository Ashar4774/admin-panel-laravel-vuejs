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
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('checkAuthStatus', [AuthController::class, 'checkAuthStatus'])->name('checkAuthStatus');
//    Route::post('clients/store', [ClientController::class, 'store']);
    Route::post('clients/import', [ClientController::class, 'import']);
    Route::get('clients/state_of_account/{id}', [ClientController::class, 'state_of_account']);
    Route::apiResource('clients', ClientController::class);

    Route::get('invoices/fetchClients', [InvoiceController::class, 'fetchClients'])->name('fetchClients');
    Route::post('invoices/import', [InvoiceController::class, 'import']);
    Route::apiResource('invoices', InvoiceController::class);

    Route::get('/state_of_account/show/{ref_no}', [StateOfAccountController::class, 'show']);

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
