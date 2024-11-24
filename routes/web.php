<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use \Illuminate\Support\Facades\Auth;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\CartController;
use \App\Http\Controllers\OrderController;
use \App\Http\Controllers\TelegramController;
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
Route::post('/checkout/crear-preferencia', [CartController::class, 'crearPreferencia'])->name('checkout.crearPreferencia');
Route::delete('/cart/delete-detail/{id}', [CartController::class, 'deleteDetail'])->name('cart.detail.delete');

Route::get('/payment/success', [CartController::class, 'success'])->name('payment.success');
Route::get('/payment/failure', [CartController::class, 'failure'])->name('payment.failure');
Route::get('/payment/pending', [CartController::class, 'pending'])->name('payment.pending');

Route::get('/pago-exitoso', [CartController::class, 'pagoExitoso'])->name('pago.exitoso');
Route::get('/pago-fallido', [CartController::class, 'pagoFallido'])->name('pago.fallido');
Route::get('/pago-pendiente', [CartController::class, 'pagoPendiente'])->name('pago.pendiente');

Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
Route::get('/get/orders/{page}', [OrderController::class, 'getOrders']);


Route::middleware('auth')->group(function (){
    Route::prefix('dashboard')->group(function (){
        Route::get('/principal', [WelcomeController::class, 'goToDashboard'])->name('dashboard.principal');

        // TODO: Rutas de Orders (Pedidos Admin)
        Route::get('/listado/pedidos/', [OrderController::class, 'indexAdmin'])
            ->name('orders.list');
        Route::get('/get/data/orders/{numberPage}', [OrderController::class, 'getOrdersAdmin']);
        Route::post('/change/order/state/{order}/{state}', [OrderController::class, 'changeIOrderState']);
        Route::get('/orders/{orderId}/details', [OrderController::class, 'getOrderDetails']);

        // TODO: Rutas de Mantenedor de Productos (Productos Admin)
        Route::get('/listado/productos/', [ProductController::class, 'indexAdmin'])
            ->name('products.list');
        Route::get('/get/data/products/{numberPage}', [ProductController::class, 'getDataProducts']);
        Route::get('/crear/producto/', [ProductController::class, 'create'])
            ->name('product.create');
        Route::post('/save/product/', [ProductController::class, 'store'])
            ->name('product.store');
        Route::get('/editar/producto/', [ProductController::class, 'edit'])
            ->name('product.edit');
        Route::post('/update/product/', [ProductController::class, 'update'])
            ->name('product.update');

    });
});

Route::get('/telegram/send', [TelegramController::class, 'sendMessage']);
