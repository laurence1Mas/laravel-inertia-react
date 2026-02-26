<?php

use App\Http\Controllers\Api\TodoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/todos/completed', [TodoController::class, 'completed']);
    Route::get('/todos/pending', [TodoController::class, 'pending']);
    Route::apiResource('todos', TodoController::class);
});
