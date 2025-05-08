<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SmartLockController;
use App\Http\Controllers\Api\VerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);

    // Properties
    Route::apiResource('properties', PropertyController::class);
    
    // Tenants
    Route::apiResource('tenants', TenantController::class);
    
    // Documents
    Route::apiResource('documents', DocumentController::class);
    Route::get('documents/{document}/download', [DocumentController::class, 'download']);
    
    // Payments
    Route::apiResource('payments', PaymentController::class);
    Route::post('payments/{payment}/process', [PaymentController::class, 'process']);
    
    // Smart Locks
    Route::apiResource('smart-locks', SmartLockController::class);
    Route::post('smart-locks/{smartLock}/grant-access', [SmartLockController::class, 'grantAccess']);
    Route::post('smart-locks/{smartLock}/revoke-access', [SmartLockController::class, 'revokeAccess']);
    Route::get('smart-locks/{smartLock}/access-logs', [SmartLockController::class, 'accessLogs']);
    
    // Verifications
    Route::apiResource('verifications', VerificationController::class);
    Route::post('verifications/{verification}/verify', [VerificationController::class, 'verify']);

    Route::prefix('properties/{property}/locks')->group(function () {
        Route::get('/', [SmartLockController::class, 'index']);
        Route::post('/', [SmartLockController::class, 'store']);
        Route::get('/{lock}', [SmartLockController::class, 'show']);
        Route::put('/{lock}', [SmartLockController::class, 'update']);
        Route::delete('/{lock}', [SmartLockController::class, 'destroy']);
        Route::post('/{lock}/control', [SmartLockController::class, 'control']);
        Route::get('/{lock}/logs', [SmartLockController::class, 'logs']);
    });
});
