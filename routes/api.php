<?php

use App\Http\Controllers\Api\V1\AuthController;
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

// API Version 1
Route::prefix('v1')->group(function () {
    
    // Public routes
    Route::post('/login', [AuthController::class, 'login']);
    
    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        
        // DAR routes
        Route::apiResource('dars', \App\Http\Controllers\Api\V1\DarController::class);
        
        // DAR approval routes
        Route::post('/dars/{id}/approve', [\App\Http\Controllers\Api\V1\DarController::class, 'approve']);
        Route::post('/dars/{id}/reject', [\App\Http\Controllers\Api\V1\DarController::class, 'reject']);
        Route::get('/dars-pending-approvals', [\App\Http\Controllers\Api\V1\DarController::class, 'pendingApprovals']);
    });
});
