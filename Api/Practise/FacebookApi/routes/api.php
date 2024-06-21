<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacebookPostsController;
use App\Http\Controllers\FacebookController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/posts',[FacebookPostsController::class,'createPost']);
Route::get('/posts',[FacebookPostsController::class,'seePosts']);
Route::delete('/posts/{id}',[FacebookPostsController::class,'deletePosts']);
Route::get('/posts/{id}',[FacebookPostsController::class,'seePost']);
Route::post('/posts/{id}/comments',[FacebookController::class,'createComment']);
Route::get('/posts/{id}/comments',[FacebookController::class,'viewComment']);
Route::get('/posts/{id}/comments/count',[FacebookController::class,'countComment']);