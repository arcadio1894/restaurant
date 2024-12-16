<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use \Illuminate\Support\Facades\Auth;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\CartController;
use \App\Http\Controllers\OrderController;
use \App\Http\Controllers\TelegramController;
use \App\Http\Controllers\CouponController;
use \App\Http\Controllers\BusinessController;
use \App\Http\Controllers\PrintController;
use \App\Http\Controllers\TypeController;
use \App\Http\Controllers\CategoryController;
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
Route::post('/cart/manage/direct', [CartController::class, 'manage2'])->name('cart.manage2');
Route::post('/cart/manage/adicional', [CartController::class, 'manage3'])->name('cart.manage3');
Route::get('/carrito', [CartController::class, 'show'])/*->middleware('auth')*/->name('cart.show');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])/*->middleware('auth')*/->name('cart.updateQuantity');
Route::get('/cart/quantity', [CartController::class, 'getCartQuantity'])->name('cart.quantity');

Route::get('/products/{id}', [ProductController::class, 'getProduct'])->name('products.get');

Route::get('/checkout', [CartController::class, 'checkout'])/*->middleware('auth')*/->name('cart.checkout');
Route::post('/checkout/pagar', [CartController::class, 'pagar'])->name('checkout.pagar');
Route::post('/checkout/crear-preferencia', [CartController::class, 'crearPreferencia'])->name('checkout.crearPreferencia');
Route::delete('/cart/delete-detail/{id}', [CartController::class, 'deleteDetail'])->name('cart.detail.delete');
Route::post('/cart/save-observation/{id}', [CartController::class, 'saveObservation'])->name('cart.save.observation');


Route::get('/apply-coupon', [CartController::class, 'applyCoupon'])->name('apply.coupon');
Route::post('/checkout/shipping', [CartController::class, 'calculateShipping']);


Route::get('/payment/success', [CartController::class, 'success'])->name('payment.success');
Route::get('/payment/failure', [CartController::class, 'failure'])->name('payment.failure');
Route::get('/payment/pending', [CartController::class, 'pending'])->name('payment.pending');

Route::get('/pago-exitoso', [CartController::class, 'pagoExitoso'])->name('pago.exitoso');
Route::get('/pago-fallido', [CartController::class, 'pagoFallido'])->name('pago.fallido');
Route::get('/pago-pendiente', [CartController::class, 'pagoPendiente'])->name('pago.pendiente');

Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
Route::get('/get/orders/{page}', [OrderController::class, 'getOrders']);

Route::get('/api/business-hours', [BusinessController::class, 'getBusinessHours']);


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
        Route::get('/editar/producto/{id}', [ProductController::class, 'edit'])
            ->name('product.edit');
        Route::post('/update/product/', [ProductController::class, 'update'])
            ->name('product.update');
        Route::post('/delete/product/', [ProductController::class, 'delete'])
            ->name('product.delete');

        // Mostrar listado de cupones
        Route::get('/coupons', [CouponController::class, 'index'])
            ->name('coupons.index');
        Route::get('/get/data/coupons/{page}', [CouponController::class, 'getDataCoupons']);
        Route::get('/coupons/create', [CouponController::class, 'create'])
            ->name('coupons.create');
        Route::post('/coupons', [CouponController::class, 'store'])
            ->name('coupons.store');
        Route::get('/coupons/{coupon}', [CouponController::class, 'show'])
            ->name('coupons.show');
        Route::get('/coupons/{coupon}/edit', [CouponController::class, 'edit'])
            ->name('coupons.edit');
        Route::put('/coupons/{coupon}', [CouponController::class, 'update'])
            ->name('coupons.update');
        Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])
            ->name('coupons.destroy');

        // Mostrar listado de types
        Route::get('/types', [TypeController::class, 'index'])
            ->name('types.index');
        Route::get('/get/data/types/{page}', [TypeController::class, 'getDataTypes']);
        Route::get('/types/create', [TypeController::class, 'create'])
            ->name('types.create');
        Route::post('/types', [TypeController::class, 'store'])
            ->name('types.store');
        Route::get('/types/{type}', [TypeController::class, 'show'])
            ->name('types.show');
        Route::get('/types/{type}/edit/', [TypeController::class, 'edit'])
            ->name('types.edit');
        Route::post('/types/{type}/update', [TypeController::class, 'update'])
            ->name('types.update');
        Route::post('/types/destroy', [TypeController::class, 'destroy'])
            ->name('types.destroy');

        // Mostrar Categorías
        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');
        Route::get('/get/data/categories/{page}', [CategoryController::class, 'getDataCategories']);
        Route::get('/categories/create', [CategoryController::class, 'create'])
            ->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])
            ->name('categories.store');
        Route::get('/categories/{category}', [CategoryController::class, 'show'])
            ->name('categories.show');
        Route::get('/categories/{category}/edit/', [CategoryController::class, 'edit'])
            ->name('categories.edit');
        Route::post('/categories/{category}/update', [CategoryController::class, 'update'])
            ->name('categories.update');
        Route::post('/categories/destroy', [CategoryController::class, 'destroy'])
            ->name('categories.destroy');
    });
});

Route::get('/telegram/send', [TelegramController::class, 'sendMessage']);

Route::post('/dashboard/toggle-store-status', [BusinessController::class, 'toggleStoreStatus']);

Route::post('/dashboard/print', [PrintController::class, 'imprimir']);
Route::post('/print/order/{order_id}', [PrintController::class, 'printOrder']);
Route::get('/imprimir/recibo/{id}', [PrintController::class, 'generarRecibo']);
Route::get('/imprimir/comanda/{id}', [PrintController::class, 'generarComanda']);