<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/send-verification', [AuthController::class, 'sendVerification']);
Route::get('/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('/submit', [AuthController::class, 'submitApplication']);
Route::post('/intern/apply', [AuthController::class, 'submitApplication']);





