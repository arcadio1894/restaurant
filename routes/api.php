<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Order;
use \App\Http\Controllers\OrderController;
use \App\Http\Controllers\DistributorController;
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

Route::get('/orders', function () {
    return response()->json(Order::with('user', 'payment_method')->orderBy('created_at', 'desc')->get());
});

Route::get('/order/{id}', [OrderController::class, 'show']);

/*Route::post('/orders/update', function (Request $request) {
    $order = Order::find($request->id);
    if ($order) {
        $order->status = $request->status;
        $order->save();
    }
    return response()->json(['message' => 'Orden actualizada']);
});*/

Route::post('/orders/update', [OrderController::class, 'updateStatus']); // Actualizar solo el estado
Route::post('/orders/update-time', [OrderController::class, 'updateTime']); // Actualizar tiempo y estado
Route::get('/distributors', [DistributorController::class, 'index']);
Route::post('/orders/update-distributor', [OrderController::class, 'updateDistributor']);
