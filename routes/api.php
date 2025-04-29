<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);

    Route::apiResource('campaigns', CampaignController::class);
    Route::get('campaigns/search', [CampaignController::class, 'search']);

    Route::apiResource('donations', DonationController::class);

    Route::get('users/{user}/campaigns', [UserController::class, 'campaigns']);
    Route::get('users/{user}/donations', [UserController::class, 'donations']);
}); 