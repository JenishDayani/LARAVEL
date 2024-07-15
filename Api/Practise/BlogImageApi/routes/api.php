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

// User

Route::post('/login',[UserController::class,'login']);
Route::post('/register',[UserController::class,'register']);


// Blog

Route::get('/blogs',[BlogController::class,'viewAllBlog']);
Route::get('/tagblog',[BlogController::class,'viewTagBlog']);

Route::middleware('auth:api')->group(function () {

    // User

    Route::get('/logout',[UserController::class,'logout']);
    Route::put('/user',[UserController::class,'editProfile']);
    Route::post('/userPhoto',[UserController::class,'editUserImage']);
    Route::delete('/userPhoto',[UserController::class,'deleteUserImage']);


    //Blog
    Route::post('/blog',[BlogController::class,'addBlog']);
    Route::get('/blog',[BlogController::class,'viewBlog']);
    Route::put('/blog/{id}',[BlogController::class,'editBlog']);
    Route::post('/blog/{id}',[BlogController::class,'editBlogPhoto']);
    Route::delete('/blog/{id}',[BlogController::class,'deleteBlog']);

});
