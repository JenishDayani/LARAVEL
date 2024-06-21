<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\TaxController;

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


Route::middleware('auth:api')->group(function (){

    // User

    Route::post('/logout',[UserController::class,'logout']);
    Route::get('/users',[UserController::class,'viewUsers']);
    Route::get('/user',[UserController::class,'viewUser']);
    Route::put('/user',[UserController::class,'editUser']);
    Route::post('/user/image',[UserController::class,'editUserImage']);
    Route::delete('/user',[UserController::class,'deleteUser']);


    // Address

    Route::get('/address',[AddressController::class,'viewAddress']);
    Route::post('/address',[AddressController::class,'addAddress']);



    // Product 

    Route::post('/product',[ProductController::class,'addProduct']);
    Route::put('/product/{id}',[ProductController::class,'editProduct']);
    Route::post('/product/{id}',[ProductController::class,'editProductPhoto']);
    Route::delete('/product/{id}',[ProductController::class,'deleteProduct']);
    

    // WatchList

    Route::get('/watchlist',[WatchlistController::class,'viewWatch']);
    Route::post('/watchlist/{id}',[WatchlistController::class,'addWatch']);
    Route::delete('/watchlist/{id}',[WatchlistController::class,'deleteWatch']);



    // Tax

    Route::post('/tax',[TaxController::class,'addTax']);
    Route::put('/tax',[TaxController::class,'editTax']);
    
    
    
    // Order

    Route::post('/order',[OrderController::class,'createOrder']);
    
    Route::get('/order',[OrderController::class,'ViewOrder']);
    

    // Offers

    Route::get('/offer',[OfferController::class,'viewOffer']);
    Route::post('/offer',[OfferController::class,'addOffer']);
    
    
});

Route::post('/user',[UserController::class,'addUser']);
Route::post('/login',[UserController::class,'login']);

Route::get('/product',[ProductController::class,'viewProduct']);
Route::get('/tax',[TaxController::class,'viewTax']);

