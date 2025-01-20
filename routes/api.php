<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\ClientController;
use App\Http\Controllers\API\v1\InvoiceController;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login'])->name('login');
//Route::apiResource('clients', ClientController::class);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('checkAuthStatus', [AuthController::class, 'checkAuthStatus'])->name('checkAuthStatus');
//    Route::post('clients/store', [ClientController::class, 'store']);
    Route::post('clients/import', [ClientController::class, 'import']);
    Route::apiResource('clients', ClientController::class);

    Route::get('invoices/fetchClients', [InvoiceController::class, 'fetchClients'])->name('fetchClients');
    Route::apiResource('invoices', InvoiceController::class);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
