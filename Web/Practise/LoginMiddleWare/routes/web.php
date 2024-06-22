<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('/');

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('check_user');
// Route::get('/admin/home', [App\Http\Controllers\HomeController::class, 'adminHome'])->name('AdminHome')->middleware('check_user');

Route::middleware(['auth', 'check_user:user'])->group(function () {
    Route::get('/home',[HomeController::class,'index'])->name('home');
});

Route::middleware(['auth', 'check_user:editor'])->group(function () {
    Route::get('/editor/home', [HomeController::class,'editorHome'])->name('EditorHome');
});

Route::middleware(['auth', 'check_user:admin'])->group(function () {
    Route::get('/admin/home', [HomeController::class,'adminHome'])->name('AdminHome');
});