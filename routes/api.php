<?php

use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\MurugoController;
use App\Http\Controllers\Api\Todo\TodoController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

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

Route::get('/login/murugo',[MurugoController::class,'redirectToMurugo'])->name('murugo.login');
Route::get('/murugo/redirect',[MurugoController::class,'redirectToMurugo'])->name('murugo.redirect');
Route::post('/authenticate-user', [MurugoController::class, 'loginWithMurugo']);

Route::post('/send-notification',[NotificationController::class,'sendNotification']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/todos', TodoController::class);
    Route::get('/task/statistics',[TaskController::class,'getStatistics']);
    Route::post('/tasks/{task}/complete',[TaskController::class,'markCompleted']);
 Route::apiResource('/tasks', TaskController::class);
   
});
    
