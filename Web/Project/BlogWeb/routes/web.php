<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\BlogController;

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

// Route::get('/', function () {
//     return view('welcome');
// });



// User

Route::get('/',[UserController::class,'index'])->name('Home');
Route::get('/login',[UserController::class,'login'])->name('Login');
Route::post('/login',[UserController::class,'login'])->name('Login');
Route::get('/logout',[UserController::class,'logout'])->name('Logout');
Route::get('/profile',[UserController::class,'viewUserProfile'])->name('UserViewProfile');
Route::get('/profile/edit',[UserController::class,'editUserProfile'])->name('UserEditProfile');
Route::post('/profile/edit',[UserController::class,'editUserProfile'])->name('UserEditProfile');



// Admin


Route::get('/admin', [AdminController::class,'home'])->name('AdminHome');
Route::post('/register', [AdminController::class,'createUser'])->name('CreateUser');
Route::get('/register', [AdminController::class,'createUser'])->name('CreateUser');
Route::get('/admin/profile', [AdminController::class,'viewProfile'])->name('AdminViewProfile');
Route::post('/admin/profile', [AdminController::class,'viewProfile'])->name('AdminViewProfile');


// Blog


Route::post('/admin/blogCreate', [BlogController::class,'createBlog'])->name('CreateBlog');
Route::get('/admin/blogCreate', [BlogController::class,'createBlog'])->name('CreateBlog');
Route::get('/admin/viewBlog', [BlogController::class,'adminViewBlog'])->name('AdminViewBlog');
Route::get('/admin/editBlog/{id}', [BlogController::class,'adminEditBlog'])->name('EditBlog');
Route::post('/admin/editBlog/{id}', [BlogController::class,'adminEditBlog'])->name('EditBlog');
Route::get('/admin/deleteBlog/{id}', [BlogController::class,'adminDeleteBlog'])->name('DeleteBlog');
Route::post('/admin/deleteBlog/{id}', [BlogController::class,'adminDeleteBlog'])->name('DeleteBlog');


Route::get('/likeBlogView', [BlogController::class,'likeBlogView'])->name('LikeBlogView');
Route::get('/likeBlog/{id}', [BlogController::class,'likeBlog'])->name('LikeBlog');
Route::post('/likeBlog/{id}', [BlogController::class,'likeBlog'])->name('LikeBlog');
Route::get('/Blog/{tag}', [BlogController::class,'tagBlog'])->name('TagBlog');


// Mail 


Route::get('/mail', [MailController::class,'index'])->name('mail');


// Route::middleware('auth:api')->group(function () {
//     // Route::get('/hello',[UserController::class,'index']);
// });

