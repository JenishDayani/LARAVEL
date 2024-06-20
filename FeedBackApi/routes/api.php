<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedBackController;

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

Route::post('/feedback',[FeedBackController::class,'store']);
Route::get('/feedback',[FeedBackController::class,'view']);
Route::get('/feedback/asc',[FeedBackController::class,'viewAscending']);
Route::get('/feedback/desc',[FeedBackController::class,'viewDescending']);