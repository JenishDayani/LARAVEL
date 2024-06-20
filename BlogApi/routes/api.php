<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



// User
Route::get('/user',[UserController::class,'viewUsers']);
Route::get('/user123',[UserController::class,'viewUsers123']);
Route::post('/user',[UserController::class,'addUser']);
Route::get('/user/{id}',[UserController::class,'viewUser']);
Route::put('/user/{id}',[UserController::class,'editUser']);
Route::delete('/user/{id}',[UserController::class,'deleteUser']);

// Blog
Route::post('/blog/{id}',[BlogController::class,'addBlog']);
Route::get('/blog/{id}',[BlogController::class,'viewBlog']);
Route::put('/blog/{id}',[BlogController::class,'editBlog']);
Route::delete('/blog/{id}',[BlogController::class,'deleteBlog']);

Route::get('/blogUser',[UserController::class,'blogUser']);
Route::get('/userBlog',[UserController::class,'userBlog']);


//Image
Route::get('/image',[BlogController::class,'imageStore']);

