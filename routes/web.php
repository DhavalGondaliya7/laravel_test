<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProductController;

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
    return redirect("login");
});

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); 
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::match(['get', 'post'],'forgot-password', [AuthController::class, 'forgot_password'])->name('forgot.password');

Route::get('/reset-password/{token}',[AuthController::class,'reset_password'])->name('reset.password.token');
Route::post('/reset-password',[AuthController::class,'reset_password'])->name('reset.password');

Route::match(['get', 'post'],'product', [ProductController::class, 'index'])->name('product');
Route::get('/product/update/{id}',[ProductController::class,'setup'])->name('product.update');
Route::get('product/create', [ProductController::class, 'setup'])->name('product.create');
Route::post('product/commit', [ProductController::class, 'commit'])->name('product.commit');
Route::post('product/delete', [ProductController::class, 'delete'])->name('product.delete');
