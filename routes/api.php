<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SugestaoController;
use Illuminate\Support\Facades\Route;



Route::post('/register', [AuthController::class, 'register']);
Route::get('/update/{id}',[AuthController::class,'updateUser']);
Route::put('/update/user/{id}',[AuthController::class,'update']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->put('/update-password', [AuthController::class, 'updatePassword']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/sugestoes/{id}', [SugestaoController::class, 'sugerirRefeicao']);
Route::middleware('auth:sanctum')->get('/informacoes/{id}', [AuthController::class, 'getInfo']);
