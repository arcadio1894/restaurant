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
use \App\Http\Controllers\SliderController;
use \App\Http\Controllers\CashRegisterController;
use \App\Http\Controllers\FacturaController;
use App\Http\Controllers\OrdersChartController;
use \App\Http\Controllers\ReclamacionController;
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

Route::get('/', [WelcomeController::class, 'welcome'])->name('welcome');

/*Route::get('/is-authenticated', [WelcomeController::class, 'isAuthenticated']);*/
Route::get('/auth/check', [WelcomeController::class, 'isAuthenticated'])->name('auth.check');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/menu', [WelcomeController::class, 'menu'])->name('menu');
Route::get('/nosotros', [WelcomeController::class, 'about'])->name('about');

Route::get('/reclamaciones', [ReclamacionController::class, 'reclamaciones'])->name('reclamaciones');
Route::get('/provincias/{departmentId}', [ReclamacionController::class, 'getProvinces']);
Route::get('/distritos/{provinceId}', [ReclamacionController::class, 'getDistricts']);
Route::get('/submotivos/{motivoId}', [ReclamacionController::class, 'getSubmotivos']);
Route::post('/reclamaciones/store', [ReclamacionController::class, 'store'])->name('reclamaciones.store');

Route::get('/estado/reclamos', [ReclamacionController::class, 'estadoReclamos'])->name('estado-reclamos');
Route::post('/consultar-estado-reclamo', [ReclamacionController::class, 'consultarEstado'])->name('reclamos.consultar');

/*Route::get('/producto/{id}', [ProductController::class, 'show'])->name('product.show');*/
Route::get('/producto/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/products/fill-slugs', [ProductController::class, 'fillSlugs']);
/*Route::get('/auth/check', function() {
    return response()->json(['authenticated' => auth()->check()]);
})->name('auth.check');*/
Route::get('/personaliza/tu/pizza', [ProductController::class, 'customPizza'])->name('product.custom');

Route::post('/cart/manage', [CartController::class, 'manage'])->name('cart.manage');
Route::post('/cart/manage/direct', [CartController::class, 'manage2'])->name('cart.manage2');
Route::post('/cart/manage/adicional', [CartController::class, 'manage3'])->name('cart.manage3');
Route::get('/carrito', [CartController::class, 'show'])/*->middleware('auth')*/->name('cart.show');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])/*->middleware('auth')*/->name('cart.updateQuantity');
Route::get('/cart/quantity', [CartController::class, 'getCartQuantity'])->name('cart.quantity');

Route::get('/products/{id_product}/{product_type_id}', [ProductController::class, 'getProduct'])->name('products.get');

Route::get('/checkout', [CartController::class, 'checkout'])/*->middleware('auth')*/->name('cart.checkout');
Route::get('/checkout/v2', [CartController::class, 'checkout2'])/*->middleware('auth')*/->name('cart.checkout.v2');
Route::post('/checkout/pagar', [CartController::class, 'pagar'])
    /*->middleware('throttle:checkout')*/
    ->name('checkout.pagar');
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

Route::post('/save/custom/product', [CartController::class, 'saveCustomProduct']);

Route::middleware('auth')->group(function (){
    Route::prefix('dashboard')->group(function (){
        Route::get('/principal', [WelcomeController::class, 'goToDashboard'])->name('dashboard.principal');

        // TODO: Rutas de Orders (Pedidos Admin)
        Route::get('/listado/pedidos/', [OrderController::class, 'indexAdmin'])
            ->name('orders.list');
        Route::get('/listado/pedidos/anulados/', [OrderController::class, 'indexAdminAnnulled'])
            ->name('orders.list.annulled');
        Route::get('/get/data/orders/{numberPage}', [OrderController::class, 'getOrdersAdmin']);
        Route::get('/get/data/orders/annulled/{numberPage}', [OrderController::class, 'getOrdersAnnulledAdmin']);
        Route::post('/change/order/state/{order}/{state}', [OrderController::class, 'changeIOrderState']);
        Route::post('/anular/order/{order}', [OrderController::class, 'anularOrder']);
        Route::post('/activar/order/{order}', [OrderController::class, 'activarOrder']);
        Route::get('/orders/{orderId}/details', [OrderController::class, 'getOrderDetails']);

        // TODO: Rutas de Mantenedor de Productos (Productos Admin)
        Route::get('/listado/productos/', [ProductController::class, 'indexAdmin'])
            ->name('products.list');
        Route::get('/get/data/products/{numberPage}', [ProductController::class, 'getDataProducts']);

        Route::get('/listado/productos/eliminados/', [ProductController::class, 'indexAdminDeleted'])
            ->name('products.list.deleted');
        Route::get('/get/data/products/deleted/{numberPage}', [ProductController::class, 'getDataProductsDeleted']);


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

        Route::post('/destroy/product/{id}', [ProductController::class, 'destroy'])
            ->name('product.destroy');
        Route::post('/desactivar/producto/{id}', [ProductController::class, 'desactivar'])
            ->name('product.desactivar');

        Route::post('/reactivar/product/{id}', [ProductController::class, 'reactivar'])
            ->name('product.reactivar');

        // Mostrar listado de cupones
        Route::get('/coupons', [CouponController::class, 'index'])
            ->name('coupons.index');
        Route::get('/get/data/coupons/{page}', [CouponController::class, 'getDataCoupons']);
        Route::get('/coupons/create', [CouponController::class, 'create'])
            ->name('coupons.create');
        Route::post('/coupons/store', [CouponController::class, 'store'])
            ->name('coupons.store');
        Route::get('/coupons/{coupon}/edit', [CouponController::class, 'edit'])
            ->name('coupons.edit');
        Route::post('/coupons/update', [CouponController::class, 'update'])
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

        // Rutas Sliders
        Route::get('/sliders', [SliderController::class, 'index'])
            ->name('sliders.index');
        Route::get('/get/all/sliders', [SliderController::class, 'getSliders']);
        Route::post('/sliders/destroy', [SliderController::class, 'destroy'])
            ->name('sliders.destroy');
        Route::get('/sliders/create', [SliderController::class, 'create'])
            ->name('sliders.create');
        Route::post('/sliders', [SliderController::class, 'store'])
            ->name('sliders.store');
        Route::get('/editar/imagen/slider/{slider}', [SliderController::class, 'edit'])
            ->name('sliders.edit');
        Route::post('/sliders/update', [SliderController::class, 'update'])
            ->name('sliders.update');

        //Rutas de la caja
        Route::get('/ver/caja/{type}', [CashRegisterController::class, 'indexCashRegister'])
            ->name('index.cashRegister');
        Route::post('open/cashRegister', [CashRegisterController::class, 'openCashRegister'])
            ->name('open.cashRegister');
        Route::post('close/cashRegister', [CashRegisterController::class, 'closeCashRegister'])
            ->name('close.cashRegister');
        Route::post('income/cashRegister', [CashRegisterController::class, 'incomeCashRegister'])
            ->name('income.cashRegister');
        Route::post('expense/cashRegister', [CashRegisterController::class, 'expenseCashRegister'])
            ->name('expense.cashRegister');
        Route::post('regularize/cashRegister', [CashRegisterController::class, 'regularizeCashRegister'])
            ->name('regularize.cashRegister');
        Route::get('/get/data/movements/V2/{numberPage}', [CashRegisterController::class, 'getDataMovements']);

        Route::post('/factura/generar/{id}', [FacturaController::class, 'generarComprobante']);
        Route::get('/factura/imprimir/{id}', [FacturaController::class, 'descargarComprobante']);

        // Rutas de graficos
        Route::get('/orders/chart-data', [OrdersChartController::class, 'getChartData']);
        Route::get('/promos/chart-data', [OrdersChartController::class, 'getChartDataPromo']);

        // Rutas de reclamos
        Route::get('/reclamos/activos', [ReclamacionController::class, 'index'])->name('reclamos.index');
        Route::get('/reclamos/finalizados', [ReclamacionController::class, 'delete'])->name('reclamos.delete');
        Route::get('/get/data/reclamos/{page}', [ReclamacionController::class, 'getDataReclamos']);
        Route::get('/reclamo/{id}/revisar', [ReclamacionController::class, 'show'])->name('reclamos.show');

        Route::post('/reclamos/respuesta', [ReclamacionController::class, 'guardarRespuesta'])->name('reclamos.guardarRespuesta');
    });
});

Route::get('/telegram/send', [TelegramController::class, 'sendMessage']);

Route::post('/dashboard/toggle-store-status', [BusinessController::class, 'toggleStoreStatus']);

Route::post('/dashboard/print', [PrintController::class, 'imprimir']);
/*Route::post('/print/order/{order_id}', [PrintController::class, 'printOrder']);*/
Route::get('/imprimir/recibo/{id}', [PrintController::class, 'generarRecibo']);
Route::get('/imprimir/comanda/{id}', [PrintController::class, 'generarComanda']);

Route::get('/products/initialize-days', [ProductController::class, 'initializeProductDays']);