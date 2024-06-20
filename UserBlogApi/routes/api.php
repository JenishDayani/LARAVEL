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

Route::middleware('auth:api')->group(function () {

    // User

    Route::get('/user',[UserController::class,'viewUser']);
    Route::put('/user',[UserController::class,'editUser']);
    Route::delete('/user',[UserController::class,'deleteUser']);
    Route::post('/logout',[UserController::class,'logout']);

    Route::post('/user/image',[UserController::class,'editUserImage']);

    //Blog

    Route::post('/blog',[BlogController::class,'addBlog']);
    Route::get('/blog',[BlogController::class,'viewBlog']);
    Route::put('/blog/{id}',[BlogController::class,'editBlog']);
    Route::delete('/delete/{id}',[BlogController::class,'deleteBlog']);
    
    Route::post('/blog/{id}/image',[BlogController::class,'editBlogImage']);



    Route::get('/blogUser',[BlogController::class,'blogUser']);


});





Route::post('/user',[UserController::class,'addUser']);
Route::get('/users',[UserController::class,'viewAllUsers']);
Route::post('/login',[UserController::class,'login']);


// Route::get('/user',[UserController::class,'viewUser']);




Route::get('/het',[UserController::class,'Het']);