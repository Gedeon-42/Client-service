<?php

use App\Http\Controllers\Web\MurugoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login/murugo',[MurugoController::class,'redirectToMurugo'])->name('murugo.login');
Route::get('/murugo/redirect',[MurugoController::class,'redirectToMurugo'])->name('murugo.redirect');
