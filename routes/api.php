<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AnswerController;

// Роуты для аутентификации
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Защищённые роуты (только для аутентифицированных пользователей)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/student/tasks', [TaskController::class, 'index']);
    Route::get('/student/tasks/{id}', [TaskController::class, 'show']);
    Route::post('/tasks/{taskId}/check-answer', [AnswerController::class, 'checkAnswer']);


    Route::middleware('admin')->group(function () {
        Route::apiResource('/groups', GroupController::class);
        Route::apiResource('/students', StudentController::class);
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::put('/tasks/{id}', [TaskController::class, 'update']);
        Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

    });
});
