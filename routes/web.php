<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use \Illuminate\Support\Facades\Auth;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\CartController;
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

/*Route::get('/', function () {

    return view('welcome');
});*/

Route::get('/', [WelcomeController::class, 'welcome']);

/*Route::get('/is-authenticated', [WelcomeController::class, 'isAuthenticated']);*/
Route::get('/auth/check', [WelcomeController::class, 'isAuthenticated'])->name('auth.check');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/menu', [WelcomeController::class, 'menu'])->name('menu');
Route::get('/nosotros', [WelcomeController::class, 'about'])->name('about');

Route::get('/producto/{id}', [ProductController::class, 'show'])->name('product.show');

/*Route::get('/auth/check', function() {
    return response()->json(['authenticated' => auth()->check()]);
})->name('auth.check');*/

Route::post('/cart/manage', [CartController::class, 'manage'])->name('cart.manage');
Route::get('/carrito', [CartController::class, 'show'])->middleware('auth')->name('cart.show');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->middleware('auth')->name('cart.updateQuantity');
Route::get('/checkout', [CartController::class, 'checkout'])->middleware('auth')->name('cart.checkout');
Route::post('/checkout/pagar', [CartController::class, 'pagar'])->name('checkout.pagar');


Route::get('/payment/success', [CartController::class, 'success'])->name('payment.success');
Route::get('/payment/failure', [CartController::class, 'failure'])->name('payment.failure');
Route::get('/payment/pending', [CartController::class, 'pending'])->name('payment.pending');