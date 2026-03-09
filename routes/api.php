<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public Routes
Route::post('/intern/apply', [AuthController::class, 'submitApplication']);
// //Route::post('/intern/login', [AuthController::class, 'loginIntern']);


// Public Routes
// Route::post('/intern/apply', [AuthController::class, 'submitApplication']);
//Route::post('/intern/login', [AuthController::class, 'loginIntern']);
//Route::post('/admin/login', [AuthController::class, 'loginAdmin']);



