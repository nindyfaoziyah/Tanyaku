<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DatasetController;
use App\Http\Controllers\Api\DatasetUploadController;

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:10,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::post('/datasets', [DatasetUploadController::class, 'store']);

    Route::get('/datasets', [DatasetController::class, 'index']);
    Route::get('/datasets/{id}', [DatasetController::class, 'show']);
    Route::get('/datasets/{id}/records', [DatasetController::class, 'records']);
});

    Route::get('/datasets', [DatasetController::class, 'index']);
    Route::get('/datasets/{id}', [DatasetController::class, 'show']);
    Route::get('/datasets/{id}/records', [
        DatasetController::class,
        'records',
    ]);
    Route::post('/datasets', [
        DatasetUploadController::class,
        'store',
    ]);
  
    