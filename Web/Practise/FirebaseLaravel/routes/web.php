<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[UserController::class,'addUser']);
Route::post('/',[UserController::class,'addUser']);


Route::get('/user',[UserController::class,'viewUser'])->name('viewUser');
Route::delete('/delete/{id}',[UserController::class,'deleteUser'])->name('deleteUser');

