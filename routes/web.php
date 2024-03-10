<?php

use App\Http\Controllers\AdminController;
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

// Route::get('/', function () {
//     return view('home');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/Dashboard', [ProductController::class, 'index'])->name('index');
Route::view('/', 'login');

Route::prefix('user')->name('user.')->group(function () {

    Route::middleware(['guest'])->group(function () {

        Route::view('/login', 'dashboard.user.login')->name('login');
        Route::view('/register', 'dashboard.user.register')->name('register');
        Route::post('/create', [UserController::class, 'create'])->name('create');
    });

    Route::post('/user/login', [UserController::class, 'check'])->name('check');
    Route::post('/{id}/toggle-active', [UserController::class, 'toggleActive'])->name('toggleActive');


    Route::middleware(['auth','user.verified'])->group(function () {

        Route::get('/home', [UserController::class, 'index'])->name('index');
        Route::post('/logout', [UserController::class, 'logout'])->name('logout');
        Route::post('/cart', [UserController::class, 'userCart'])->name('cart');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware(['guest:admin'])->group(function () {

        Route::view('/login', 'dashboard.admin.login')->name('login');
        Route::post('/check', [AdminController::class, 'check'])->name('check');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/home', [AdminController::class, 'index'])->name('index');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::get('/products', [AdminController::class, 'productView'])->name('products');
        Route::get('/users', [AdminController::class, 'index'])->name('users');
        Route::post('/userCreate', [AdminController::class, 'userCreate'])->name('userCreate');
    });

    Route::get('/usercart/{id}', [AdminController::class, 'viewCart'])->name('viewCart');
    Route::post('/userUpdate/{id}', [AdminController::class, 'userUpdate'])->name('userUpdate');
    Route::get('/userDelete/{id}', [AdminController::class, 'userDelete'])->name('userDelete');
});

Route::name('product.')->group(function () {

    Route::post('/store', [ProductController::class, 'store'])->name('store');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::post('/update/{id}', [ProductController::class, 'update'])->name('update');
    Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('delete');
    Route::post('/additem/{id}', [ProductController::class, 'addToCart'])->name('addToCart');
    Route::get('/product/{id}', [ProductController::class, 'productDetails'])->name('page');
});

Route::name('cart.')->group(function () {

    Route::get('/cart/', [ProductController::class, 'cartIndex'])->name('index');
    Route::get('/cart/delete/{id}', [ProductController::class, 'cartProductDelete'])->name('productDelete');
    Route::post('cart/QtyUpdate/{product_id}', [ProductController::class, 'cartQtyUpdate'])->name('qtyUpdate');
});

Route::get('cart/purchased', [ProductController::class, 'purchased'])->name('product.purchased');
