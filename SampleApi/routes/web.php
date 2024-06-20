<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
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
    return view('Home');
})->name('home');



// Route::get('/books',[BookController::class,'index2'])->name('view');
// Route::get('/books/{id}',[BookController::class,'show']);
// Route::get('/books/custom/{bookName}',[BookController::class,'customview']);
// // Route::post('/books',[BookController::class,'store']);
// Route::put('/books/{id}',[BookController::class,'update']);
// Route::delete('/books/{id}',[BookController::class,'destroy']);

// Route::get('addbook',[BookController::class,'store2'])->name('addbook');
// Route::post('addbook',[BookController::class,'store2'])->name('addbook');
// Route::get('deletebook',[BookController::class,'store2'])->name('deletebook');
// Route::post('deletebook',[BookController::class,'store2'])->name('deletebook');