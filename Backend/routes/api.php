<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function(Request $request){
  return 'OK';
});

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'profile']);

    // Projects
    Route::prefix('projects')->group(function () {
        Route::get('/',          [ProjectController::class, 'getAll']);
        Route::get('/{id}',      [ProjectController::class, 'show'])->where('id', '[0-9]+');
        Route::post('/',         [ProjectController::class, 'create']);
        Route::put('/{id}',      [ProjectController::class, 'update'])->where('id', '[0-9]+');
        Route::patch('/{id}/activate', [ProjectController::class, 'activate'])->where('id', '[0-9]+');
        Route::patch('/{id}/archive',  [ProjectController::class, 'archive'])->where('id', '[0-9]+');
        Route::patch('/{id}/restore',  [ProjectController::class, 'restore'])->where('id', '[0-9]+');
    });
});