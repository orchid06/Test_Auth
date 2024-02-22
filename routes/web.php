<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

//Auth::routes();

Route::view('/login','auth.login')->name('login');

Route::view('/register', 'auth.register')->name('register');

Route::post('/create', [UserController::class,'create'])->name('create');

Route::post('/check', [UserController::class, 'check'])->name('check');

Route::post('/logout', [UserController::class,'logout'])->name('logout');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/' , [ProductController::class, 'index'])->name('index');

Route::post('/store' , [ProductController::class, 'store'])->name('product.store');

Route::get('/search' , [ProductController::class, 'search'])->name('product.search');

Route::post('/update/{id}' , [ProductController::class, 'update'])->name('product.update');

Route::get('/delete/{id}' , [ProductController::class, 'delete'])->name('product.delete');

Route::post('/additem/{id}', [ProductController::class, 'cart'])->name('product.cart');

Route::get('/cart/' , [ProductController::class, 'cartPage'])->name('cart.page');

Route::get('/cart/delete/{id}', [ProductController::class, 'cartProductDelete'])->name('cartProduct.delete');

Route::post('cart/QtyUpdate/{product_id}', [ProductController::class, 'cartQtyUpdate'])->name('cartQty.update');

Route::get('cart/purchased', [ProductController::class, 'purchased'])->name('product.purchased');

Route::get('/product/{id}' , [ProductController::class, 'productDetails'])->name('product.page');
