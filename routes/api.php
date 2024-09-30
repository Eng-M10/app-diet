<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SugestaoController;
use Illuminate\Support\Facades\Route;



Route::post('/register', [AuthController::class, 'register']);
Route::put('/update/user/{id}',[AuthController::class,'update']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/sugestoes/{id}', [SugestaoController::class, 'sugerirRefeicao']);
