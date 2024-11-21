<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MercadoPago\Item;
use MercadoPago\Payment;
use MercadoPago\Preference;
use MercadoPago\SDK;

class CartController extends Controller
{
    public function __construct()
    {
        //SDK::setAccessToken(env('MERCADO_PAGO_ACCESS_TOKEN'));
        /*SDK::setAccessToken(env('MERCADO_PAGO_ACCESS_TOKEN_PRO'));*/
    }

    public function manage(Request $request)
    {
        $user = Auth::user();
        $product_id = $request->input('product_id');

        // Verificar si el usuario tiene un carrito pendiente
        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$cart) {
            // No existe un carrito pendiente; verificamos si hay uno en proceso
            $processingCart = Cart::where('user_id', $user->id)
                ->where('status', 'processing')
                ->first();

            if ($processingCart) {
                return response()->json(['message' => 'Ya tienes un carrito en proceso.'], 403);
            }

            // Si no hay en proceso, creamos uno nuevo
            $cart = Cart::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
        }

        // Verificar si el producto ya está en el carrito
        $cartDetail = $cart->details()->where('product_id', $product_id)->first();

        if (!$cartDetail) {
            // Agregar el producto al carrito si no existe
            $producto = Product::find($product_id);
            $cart->details()->create([
                'product_id' => $product_id,
                'quantity' => 1,
                'price' => $producto->unit_price,
                'subtotal' => $producto->unit_price*1
            ]);
        }

        //TODO: Agregue uno mas si le doy aun producto que existe

        return response()->json(['redirect' => route('cart.show')]);
    }

    public function show()
    {
        $user = Auth::user();

        $cart = Cart::with('details.product')->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();
        return view('product.cart', compact('cart'));
    }

    public function updateQuantity(Request $request)
    {
        $detail = CartDetail::find($request->detail_id);

        if ($detail) {
            $detail->quantity = $request->quantity;
            $detail->subtotal = $detail->quantity * $detail->product->unit_price;
            $detail->save();

            // Recalcular subtotal, taxes y total del carrito
            $cart = $detail->cart;
            $subtotalCart = $cart->subtotal_cart;
            $taxesCart = $cart->taxes_cart;
            $totalCart = $cart->total_cart;

            return response()->json([
                'success' => true,
                'subtotal_cart' => number_format($subtotalCart, 2),
                'taxes_cart' => number_format($taxesCart, 2),
                'total_cart' => number_format($totalCart, 2),
                'detail_subtotal' => number_format($detail->subtotal, 2)
            ]);
        }

        return response()->json(['success' => false], 400);
    }

    public function checkout()
    {
        $user = Auth::user();

        $cart = Cart::with('details.product')->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        $payment_methods = PaymentMethod::active()->get();

        if (!$cart || $cart->details->isEmpty()) {
            return redirect()->route('home');
        }

        $defaultAddress = Address::where('user_id', Auth::id())
            ->where('is_default', true)
            ->first();

        return view('product.checkout', compact('cart', 'payment_methods', 'defaultAddress'));
    }

    public function pagar( CheckoutRequest $request )
    {
        DB::beginTransaction();

        try {
            // Validar los datos y guardarlos
            $validatedData = $request->validated();

            $shippingAddressId = null;

            // Verificar si 'save-info' está marcado como "on"
            if ($request->has('save-info') && $request->input('save-info') === 'on') {
                $isDefault = true;
                $existingDefaultAddress = Address::where('user_id', auth()->id())->where('is_default', true)->first();
                if ($existingDefaultAddress) {
                    $isDefault = false;
                }

                // Crear la nueva dirección
                $address = Address::create([
                    'user_id' => auth()->id(),
                    'type' => 'shipping',
                    'phone' => $validatedData['phone'],
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'address_line' => $validatedData['address'],
                    'reference' => $request->input('reference', ''),
                    'city' => '',
                    'state' => '',
                    'postal_code' => '',
                    'country' => '',
                    'is_default' => $isDefault
                ]);

                $shippingAddressId = $address->id;
            }

            $cart = Cart::findOrFail($validatedData['cart_id']);
            $totalAmount = $cart->total_cart;

            // Crear la orden
            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_address_id' => $shippingAddressId,
                'billing_address_id' => $shippingAddressId,
                'total_amount' => $totalAmount,
                'status' => 'created',
                'payment_method_id' => $validatedData['paymentMethod']
            ]);

            // Crear los detalles de la orden basados en el carrito
            foreach ($cart->details as $cartItem) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->quantity * $cartItem->price
                ]);
            }

            $cart->status = 'completed';
            $cart->save();

            $method_payment = PaymentMethod::find($validatedData['paymentMethod']);

            // Selección del método de pago
            switch ($method_payment->code) {
                /*case 'mercado_pago':
                    // Procesar pago con Mercado Pago
                    $payment = new Payment();
                    $payment->transaction_amount = $totalAmount;
                    $payment->token = $request->input('token');
                    $payment->description = "Compra en tienda";
                    $payment->installments = 1;
                    $payment->payer = ["email" => $validatedData['email']];
                    $payment->payment_method_id = "visa";

                    $payment->save();

                    if ($payment->status == 'approved') {
                        $order->status = 'paid';
                        $cart->status = 'completed';
                        $cart->save();
                        $order->save();
                        DB::commit();

                        return response()->json(['success' => true, 'message' => 'Pago con Mercado Pago exitoso']);
                    } else {
                        DB::rollBack();
                        return response()->json(['success' => false, 'message' => 'Pago con Mercado Pago rechazado']);
                    }*/

                case 'pos':
                    // Lógica para pago POS (Ej. Marca la orden como pagada con POS)
                    //$order->status = 'paid';
                    //$order->save();
                    //$cart->status = 'proc';
                    //$cart->save();
                    DB::commit();

                    return response()->json(['success' => true, 'message' => 'Pago realizado con POS', 'redirect_url' => route('home')]);

                case 'efectivo':
                    // Lógica para pago en efectivo
                    $order->payment_amount = $request->input('cashAmount');
                    $order->save();
                    DB::commit();

                    return response()->json(['success' => true, 'message' => 'Orden creada. Pago en efectivo pendiente', 'redirect_url' => route('home')]);

                case 'yape_plin':
                    // Lógica para pago con Yape o Plin
                    $operationCode = $request->input('operationCode');
                    if ($operationCode) {
                        //$order->status = 'paid';
                        //$order->save();
                        //$cart->status = 'completed';
                        //$cart->save();
                        $order->payment_code = $request->input('operationCode');
                        $order->save();
                        DB::commit();

                        return response()->json(['success' => true, 'message' => 'Pago realizado con Yape/Plin', 'redirect_url' => route('home')]);
                    } else {
                        DB::rollBack();
                        return response()->json(['success' => false, 'message' => 'Falta el código de operación para Yape/Plin']);
                    }

                default:
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Método de pago no válido']);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al procesar el checkout. Inténtelo nuevamente.',
                'error' => $e->getMessage(),
            ], 420);
        }
    }

    public function crearPreferencia()
    {
        // Crea un objeto de preferencia
        $preference = new Preference();

        // Crea un ítem en la preferencia
        $item = new Item();
        $item->title = 'Mi producto';
        $item->quantity = 1;
        $item->unit_price = 100.00;
        $preference->items = array($item);

        // URL de retorno
        $preference->back_urls = array(
            "success" => "https://www.fuegoymasa.com/pago-exitoso",
            "failure" => "https://www.fuegoymasa.com/pago-fallido",
            "pending" => "https://www.fuegoymasa.com/pago-pendiente"
        );
        $preference->auto_return = "approved";

        $preference->save();

        return response()->json(['id' => $preference->id]);
    }

    public function pagoExitoso(Request $request)
    {
        // Lógica para pagos exitosos
        return view('pagos.exitoso', ['data' => $request->all()]);
    }

    public function pagoFallido(Request $request)
    {
        // Lógica para pagos fallidos
        return view('pagos.fallido', ['data' => $request->all()]);
    }

    public function pagoPendiente(Request $request)
    {
        // Lógica para pagos pendientes
        return view('pagos.pendiente', ['data' => $request->all()]);
    }

    public function deleteDetail($id)
    {
        DB::beginTransaction(); // Iniciar la transacción

        try {
            // Buscar el detalle del carrito
            $detail = CartDetail::findOrFail($id);

            // Obtener el carrito asociado
            $cart = $detail->cart;

            // Eliminar el detalle
            $detail->delete();

            // Verificar si quedan detalles en el carrito
            if ($cart->details()->count() === 0) {
                // Si no quedan detalles, eliminar el carrito
                $cart->delete();

                DB::commit(); // Confirmar la transacción

                return response()->json([
                    'status' => 'cart_deleted', // Indica que el carrito fue eliminado
                    'message' => 'El carrito está vacío y fue eliminado.',
                ]);
            }

            // Calcular los nuevos totales del carrito
            /*$subtotal = $cart->details->sum(function ($d) {
                return $d->subtotal;
            });

            $taxes = $subtotal * 0.18; // Ejemplo: suponiendo un 18% de impuestos
            $total = $subtotal + $taxes;
            $count = $cart->details->count();*/

            $subtotalCart = $cart->subtotal_cart;
            $taxesCart = $cart->taxes_cart;
            $totalCart = $cart->total_cart;

            DB::commit(); // Confirmar la transacción

            // Retornar una respuesta indicando que el detalle fue eliminado
            return response()->json([
                'status' => 'detail_deleted',
                'message' => 'El detalle fue eliminado.',
                'cart' => [
                    'subtotal' => number_format($subtotalCart, 2),
                    'taxes' => number_format($taxesCart, 2),
                    'total' => number_format($totalCart, 2),
                    'count' => $cart->details->count(),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error

            // Registrar el error (opcional, para monitoreo)
            //\Log::error('Error al eliminar el detalle del carrito: ' . $e->getMessage());

            // Retornar una respuesta de error
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error al eliminar el detalle del carrito. Intenta de nuevo más tarde.',
            ], 500);
        }
    }
}
