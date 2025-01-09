<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\AuthController;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function(){
    Route::get('checkAuthStatus', [AuthController::class, 'checkAuthStatus'])->name('checkAuthStatus');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
