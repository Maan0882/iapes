<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/intern/apply', [AuthController::class, 'submitApplication']);
//Route::post('/intern/login', [AuthController::class, 'loginIntern']);
Route::post('/admin/login', [AuthController::class, 'loginAdmin']);

// Protected Routes (Require Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Admin Only Routes (You will eventually want middleware to restrict this to Admins only)
    // Route::post('/admin/interns/{id}/approve', [AdminController::class, 'approveIntern']);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
