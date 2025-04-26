<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;

Route::post('/submit-task', [TaskController::class, 'submitTask']);
Route::get('/task-status/{id}', [TaskController::class, 'getStatus']);
Route::get('/task-result/{id}', [TaskController::class, 'getResult']);