<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [TodoController::class, 'index'])->name('todos.index');
    Route::get('/todos/completed', [TodoController::class, 'completed'])->name('todos.completed');
    Route::get('/todos/pending', [TodoController::class, 'pending'])->name('todos.pending');
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
});

require __DIR__.'/auth.php';
