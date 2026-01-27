<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Todo\TodoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/todos', TodoController::class);
     Route::get('/task',[TaskController::class,'filterTask']);
    Route::get('/tasks/filter',[TaskController::class,'filterBydueDate']);
    Route::get('/task/statistics',[TaskController::class,'getStatistics']);
    Route::post('/tasks/{task}/complete',[TaskController::class,'markCompleted']);
     Route::apiResource('/tasks', TaskController::class);
   
});

