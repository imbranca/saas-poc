<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function(Request $request){
  return 'OK';
});

Route::get('/projects', [ProjectController::class, 'getAll'])
->middleware('auth:sanctum');

Route::get('/projects/{id}', [ProjectController::class, 'show']);

Route::post('/projects', [ProjectController::class, 'create'])
->middleware('auth:sanctum');

Route::put('/projects/{id}', [ProjectController::class, 'update'])
->middleware('auth:sanctum');

Route::patch('/projects/{id}/activate', [ProjectController::class, 'activate'])
->middleware('auth:sanctum');

Route::patch('/projects/{id}/archive', [ProjectController::class, 'archive'])
->middleware('auth:sanctum');

Route::patch('/projects/{id}/restore', [ProjectController::class, 'restore'])
->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'profile'])->middleware('auth:sanctum');;