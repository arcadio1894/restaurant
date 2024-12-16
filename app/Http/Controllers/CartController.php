<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\CartDetailOption;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderDetailOption;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ShippingDistrict;
use App\Models\UserCoupon;
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

    public function getCartQuantity()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['quantity' => 0]);
        }

        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        $quantity = $cart ? $cart->details->sum('quantity') : 0;

        return response()->json(['quantity' => $quantity]);
    }

    public function manage(Request $request)
    {
        $user = Auth::user();
        $product_id = $request->input('product_id');
        $product_type_id = $request->input('product_type_id'); // Tipo de producto
        $selectedOptions = $request->input('options', []); // Opciones seleccionadas

        // Verificar si el usuario tiene un carrito pendiente
        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$cart) {
            // Crear un nuevo carrito si no existe uno pendiente
            $cart = Cart::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
        }

        // Verificar si el producto con el mismo tipo ya está en el carrito
        $cartDetail = $cart->details()
            ->where('product_id', $product_id)
            ->where('product_type_id', $product_type_id)
            ->first();

        if ($cartDetail) {
            // Si existe, aumentar la cantidad
            $cartDetail->quantity += 1;
            $cartDetail->subtotal = $cartDetail->quantity * $cartDetail->price;
            $cartDetail->save();
            // TODO: Si es combo que cree un detalle mas SI las selecciones y el producto sondiferentes
            // Si son iguales entonces agrega unno mas
            // Si no es combo que haga lo actual
        } else {
            // Si no existe, agregar un nuevo detalle
            $product = Product::find($product_id);
            $cartDetail = $cart->details()->create([
                'product_id' => $product_id,
                'product_type_id' => $product_type_id,
                'quantity' => 1,
                'price' => ProductType::find($product_type_id)->price, // Obtener el precio desde ProductType
                'subtotal' => ProductType::find($product_type_id)->price * 1, // Calcular el subtotal
            ]);
            // Guardar las opciones seleccionadas
            foreach ($selectedOptions as $optionId => $productIds) {
                foreach ((array) $productIds as $productId) {
                    $cartDetail->options()->create([
                        'option_id' => $optionId,
                        'product_id' => $productId,
                    ]);
                }
            }
        }
/*
        // Validar que $cartDetail esté correctamente definido
        if (!$cartDetail) {
            return response()->json(['error' => 'No se pudo crear el detalle del carrito.'], 500);
        }*/



        return response()->json(['redirect' => route('cart.show')]);
    }

    public function manage2(Request $request)
    {
        $user = Auth::user();
        $product_id = $request->input('product_id');

        // Verificar si el usuario tiene un carrito pendiente
        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$cart) {
            // Crear un nuevo carrito si no existe uno pendiente
            $cart = Cart::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
        }

        $product = Product::find($product_id);

        // Verificar si el producto con el mismo tipo ya está en el carrito
        $cartDetail = $cart->details()
            ->where('product_id', $product_id)
            ->first();

        if ($cartDetail) {
            // Si existe, aumentar la cantidad
            $cartDetail->quantity += 1;
            $cartDetail->subtotal = $cartDetail->quantity * $cartDetail->price;
            $cartDetail->save();
            // TODO: Si es combo que cree un detalle mas SI las selecciones y el producto sondiferentes
            // Si son iguales entonces agrega unno mas
            // Si no es combo que haga lo actual
        } else {
            // Si no existe, agregar un nuevo detalle
            $product = Product::find($product_id);
            $cartDetail = $cart->details()->create([
                'product_id' => $product_id,
                'product_type_id' => null,
                'quantity' => 1,
                'price' => $product->price_default, // Obtener el precio desde ProductType
                'subtotal' => $product->price_default * 1, // Calcular el subtotal
            ]);

        }

        return response()->json(['message' => "Producto ".$product->full_name." agregado al carrito de compras."], 200);
    }

    public function manage3(Request $request)
    {
        $user = Auth::user();
        $product_id = $request->input('product_id');

        // Verificar si el usuario tiene un carrito pendiente
        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$cart) {
            // Crear un nuevo carrito si no existe uno pendiente
            $cart = Cart::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
        }

        $product = Product::find($product_id);

        // Verificar si el producto con el mismo tipo ya está en el carrito
        $cartDetail = $cart->details()
            ->where('product_id', $product_id)
            ->first();

        if ($cartDetail) {
            // Si existe, aumentar la cantidad
            $cartDetail->quantity += 1;
            $cartDetail->subtotal = $cartDetail->quantity * $cartDetail->price;
            $cartDetail->save();
            // TODO: Si es combo que cree un detalle mas SI las selecciones y el producto sondiferentes
            // Si son iguales entonces agrega unno mas
            // Si no es combo que haga lo actual
        } else {
            // Si no existe, agregar un nuevo detalle
            $product = Product::find($product_id);
            $cartDetail = $cart->details()->create([
                'product_id' => $product_id,
                'product_type_id' => null,
                'quantity' => 1,
                'price' => $product->price_default, // Obtener el precio desde ProductType
                'subtotal' => $product->price_default * 1, // Calcular el subtotal
            ]);

        }

        return response()->json(['redirect' => route('cart.show')]);
    }

    public function show()
    {
        $user = Auth::user();
        //$cart = Cart::with(['details.options.option', 'details.options.product'])->find($cartId);
        /*$cart = Cart::with(['details.options.option', 'details.options.product'])->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();*/
        /*return view('product.cart', compact('cart'));*/
        return view('product.cart2');
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

        /*$cart = Cart::with('details.product')->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();*/

        $payment_methods = PaymentMethod::active()->get();

        /* if (!$cart || $cart->details->isEmpty()) {
            return redirect()->route('home');
        }
        */
        if ( isset($user) )
        {
            $defaultAddress = Address::where('user_id', Auth::id())
                ->where('is_default', true)
                ->first();
        } else {
            $defaultAddress = null;
        }

        $districts = ShippingDistrict::all();

        /*return view('product.checkout', compact('cart', 'payment_methods', 'defaultAddress', 'districts'));*/
        return view('product.checkout2', compact( 'payment_methods', 'defaultAddress', 'districts'));
    }

    public function pagar( CheckoutRequest $request )
    {
        DB::beginTransaction();

        try {
            // Validar los datos y guardarlos
            $validatedData = $request->validated();

            $shippingAddressId = null;

            // Verificar si 'save-info' está marcado como "on"
            //if ($request->has('save-info') && $request->input('save-info') === 'on') {
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
            //}

            //$cart = Cart::findOrFail($validatedData['cart_id']);
            $cart = json_decode($request->input('cart'), true);
            $totalAmount = $this->getTotalCart($cart);

            // Obtener el distrito seleccionado y su costo de envío
            $districtId = $request->input('district');
            if (!$districtId) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Debe seleccionar un distrito para el envío.']);
            }

            $district = ShippingDistrict::findOrFail($districtId);
            $shippingCost = $district->shipping_cost;


            // Verificar el cupón
            $couponName = $request->input('coupon_name');
            $coupon = Coupon::where('name', $couponName)->first();

            $discountAmount = 0;

            if ($coupon) {
                // Verificar si el cupón ya ha sido utilizado por el usuario
                $userCoupon = UserCoupon::where('user_id', auth()->id())
                    ->where('coupon_id', $coupon->id)
                    ->first();

                if ($userCoupon) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'El cupón ya ha sido utilizado.']);
                }

                // Lógica para calcular el descuento
                if ($coupon->type == 'total') {
                    // Validar que no haya productos de categoría 'combo' (category_id = 3)
                    /*$hasCombo = $cart->details->contains(function ($detail) {
                        return $detail->product->category_id == 3;
                    });*/

                    $hasCombo = $this->hasCombo($cart);

                    if ($hasCombo) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'El cupón no se puede aplicar a carritos que contengan combos.',
                        ]);
                    }

                    // Aplicar descuento al total
                    if ($coupon->amount != 0) {
                        $discountAmount = $coupon->amount;
                    } elseif ($coupon->percentage != 0) {
                        $discountAmount = ($coupon->percentage / 100) * $cart->total_cart;
                    }
                } elseif ($coupon->type == 'detail') {
                    // Verificar si todos los productos son de categoría 'combo' (category_id = 3)
                    /*$onlyCombos = $cart->details->every(function ($detail) {
                        return $detail->product->category_id == 3;
                    });*/
                    $onlyCombos = $this->onlyCombos($cart);

                    if ($onlyCombos) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'El cupón no se puede aplicar porque solo hay combos en el pedido.',
                        ]);
                    }

                    // Filtrar los detalles que no sean de categoría 'combo' (category_id = 3)
                    /*$eligibleDetails = $cart->details->filter(function ($detail) {
                        return $detail->product->category_id != 3;
                    });

                    // Buscar el detalle elegible con el subtotal más alto
                    $maxDetail = $eligibleDetails->sortByDesc('subtotal')->first();*/
                    $maxDetail = $this->getMaxDetail($cart);
                    if ($maxDetail) {
                        if ($coupon->amount != 0) {
                            $discountAmount = $coupon->amount;
                        } elseif ($coupon->percentage != 0) {
                            $discountAmount = ($coupon->percentage / 100) * $maxDetail->subtotal;
                        }
                    }
                }
            }

            // Crear la orden
            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_address_id' => $shippingAddressId,
                'billing_address_id' => $shippingAddressId,
                'total_amount' => $totalAmount,
                'status' => 'created',
                'payment_method_id' => $validatedData['paymentMethod'],
                'amount_shipping' => $shippingCost,
                'shipping_district_id' => $districtId,
                'observations' => $cart->observations
            ]);

            // Crear los detalles de la orden basados en el carrito
            foreach ($cart->details as $cartItem) {
                // Crear el detalle de la orden
                $orderDetail = OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_type_id' => $cartItem->product_type_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->quantity * $cartItem->price,
                ]);

                // Guardar las opciones relacionadas con este detalle
                if (!empty($cartItem->options)) {
                    foreach ($cartItem->options as $option) {
                        OrderDetailOption::create([
                            'order_detail_id' => $orderDetail->id, // Relación con el detalle de la orden
                            'option_id' => $option->id,           // ID de la opción seleccionada
                            'product_id' => $option->product_id,  // ID del producto asociado a la opción
                        ]);
                    }
                }
            }

            $cart->status = 'completed';
            $cart->save();

            // Guardamos la relación del cupón con el usuario, solo si se aplicó
            if ($coupon) {
                UserCoupon::create([
                    'user_id' => auth()->id(),
                    'coupon_id' => $coupon->id,
                    'discount_amount' => $discountAmount,
                    'order_id' => $order->id, // Asociar al pedido
                ]);
            }


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
                    $data = [
                        'nameUser' => $order->user->name,
                        'dateOperation' => $order->created_at->format('d M Y, g:i a'),
                        'order' => "ORDEN - ".$order->id
                    ];

                    $telegramController = new TelegramController();
                    $telegramController->sendNotification('process', $data);

                    DB::commit();

                    return response()->json(['success' => true, 'message' => 'Pago realizado con POS', 'redirect_url' => route('orders.index')]);

                case 'efectivo':
                    // Lógica para pago en efectivo
                    $order->payment_amount = $request->input('cashAmount');
                    $order->save();

                    $data = [
                        'nameUser' => $order->user->name,
                        'dateOperation' => $order->created_at->format('d M Y, g:i a'),
                        'order' => "ORDEN - ".$order->id
                    ];

                    $telegramController = new TelegramController();
                    $telegramController->sendNotification('process', $data);

                    DB::commit();

                    return response()->json(['success' => true, 'message' => 'Orden creada. Pago en efectivo pendiente', 'redirect_url' => route('orders.index')]);

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

                        $data = [
                            'nameUser' => $order->user->name,
                            'dateOperation' => $order->created_at->format('d M Y, g:i a'),
                            'order' => "ORDEN - ".$order->id
                        ];

                        $telegramController = new TelegramController();
                        $telegramController->sendNotification('process', $data);

                        DB::commit();

                        return response()->json(['success' => true, 'message' => 'Pago realizado con Yape/Plin', 'redirect_url' => route('orders.index')]);
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

    public function pagarOriginal( CheckoutRequest $request )
    {
        DB::beginTransaction();

        try {
            // Validar los datos y guardarlos
            $validatedData = $request->validated();

            $shippingAddressId = null;

            // Verificar si 'save-info' está marcado como "on"
            //if ($request->has('save-info') && $request->input('save-info') === 'on') {
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
            //}

            $cart = Cart::findOrFail($validatedData['cart_id']);
            $totalAmount = $cart->total_cart;

            // Obtener el distrito seleccionado y su costo de envío
            $districtId = $request->input('district');
            if (!$districtId) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Debe seleccionar un distrito para el envío.']);
            }

            $district = ShippingDistrict::findOrFail($districtId);
            $shippingCost = $district->shipping_cost;


            // Verificar el cupón
            $couponName = $request->input('coupon_name');
            $coupon = Coupon::where('name', $couponName)->first();

            $discountAmount = 0;

            if ($coupon) {
                // Verificar si el cupón ya ha sido utilizado por el usuario
                $userCoupon = UserCoupon::where('user_id', auth()->id())
                    ->where('coupon_id', $coupon->id)
                    ->first();

                if ($userCoupon) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'El cupón ya ha sido utilizado.']);
                }

                // Lógica para calcular el descuento
                if ($coupon->type == 'total') {
                    // Validar que no haya productos de categoría 'combo' (category_id = 3)
                    $hasCombo = $cart->details->contains(function ($detail) {
                        return $detail->product->category_id == 3;
                    });

                    if ($hasCombo) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'El cupón no se puede aplicar a carritos que contengan combos.',
                        ]);
                    }

                    // Aplicar descuento al total
                    if ($coupon->amount != 0) {
                        $discountAmount = $coupon->amount;
                    } elseif ($coupon->percentage != 0) {
                        $discountAmount = ($coupon->percentage / 100) * $cart->total_cart;
                    }
                } elseif ($coupon->type == 'detail') {
                    // Verificar si todos los productos son de categoría 'combo' (category_id = 3)
                    $onlyCombos = $cart->details->every(function ($detail) {
                        return $detail->product->category_id == 3;
                    });

                    if ($onlyCombos) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'El cupón no se puede aplicar porque solo hay combos en el pedido.',
                        ]);
                    }

                    // Filtrar los detalles que no sean de categoría 'combo' (category_id = 3)
                    $eligibleDetails = $cart->details->filter(function ($detail) {
                        return $detail->product->category_id != 3;
                    });

                    // Buscar el detalle elegible con el subtotal más alto
                    $maxDetail = $eligibleDetails->sortByDesc('subtotal')->first();

                    if ($maxDetail) {
                        if ($coupon->amount != 0) {
                            $discountAmount = $coupon->amount;
                        } elseif ($coupon->percentage != 0) {
                            $discountAmount = ($coupon->percentage / 100) * $maxDetail->subtotal;
                        }
                    }
                }
            }

            // Crear la orden
            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_address_id' => $shippingAddressId,
                'billing_address_id' => $shippingAddressId,
                'total_amount' => $totalAmount,
                'status' => 'created',
                'payment_method_id' => $validatedData['paymentMethod'],
                'amount_shipping' => $shippingCost,
                'shipping_district_id' => $districtId,
                'observations' => $cart->observations
            ]);

            // Crear los detalles de la orden basados en el carrito
            foreach ($cart->details as $cartItem) {
                // Crear el detalle de la orden
                $orderDetail = OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_type_id' => $cartItem->product_type_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->quantity * $cartItem->price,
                ]);

                // Guardar las opciones relacionadas con este detalle
                if (!empty($cartItem->options)) {
                    foreach ($cartItem->options as $option) {
                        OrderDetailOption::create([
                            'order_detail_id' => $orderDetail->id, // Relación con el detalle de la orden
                            'option_id' => $option->id,           // ID de la opción seleccionada
                            'product_id' => $option->product_id,  // ID del producto asociado a la opción
                        ]);
                    }
                }
            }

            $cart->status = 'completed';
            $cart->save();

            // Guardamos la relación del cupón con el usuario, solo si se aplicó
            if ($coupon) {
                UserCoupon::create([
                    'user_id' => auth()->id(),
                    'coupon_id' => $coupon->id,
                    'discount_amount' => $discountAmount,
                    'order_id' => $order->id, // Asociar al pedido
                ]);
            }


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
                    $data = [
                        'nameUser' => $order->user->name,
                        'dateOperation' => $order->created_at->format('d M Y, g:i a'),
                        'order' => "ORDEN - ".$order->id
                    ];

                    $telegramController = new TelegramController();
                    $telegramController->sendNotification('process', $data);

                    DB::commit();

                    return response()->json(['success' => true, 'message' => 'Pago realizado con POS', 'redirect_url' => route('orders.index')]);

                case 'efectivo':
                    // Lógica para pago en efectivo
                    $order->payment_amount = $request->input('cashAmount');
                    $order->save();

                    $data = [
                        'nameUser' => $order->user->name,
                        'dateOperation' => $order->created_at->format('d M Y, g:i a'),
                        'order' => "ORDEN - ".$order->id
                    ];

                    $telegramController = new TelegramController();
                    $telegramController->sendNotification('process', $data);

                    DB::commit();

                    return response()->json(['success' => true, 'message' => 'Orden creada. Pago en efectivo pendiente', 'redirect_url' => route('orders.index')]);

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

                        $data = [
                            'nameUser' => $order->user->name,
                            'dateOperation' => $order->created_at->format('d M Y, g:i a'),
                            'order' => "ORDEN - ".$order->id
                        ];

                        $telegramController = new TelegramController();
                        $telegramController->sendNotification('process', $data);

                        DB::commit();

                        return response()->json(['success' => true, 'message' => 'Pago realizado con Yape/Plin', 'redirect_url' => route('orders.index')]);
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

    public function applyCouponOriginal(Request $request)
    {
        $code = $request->input('code');
        $cartId = $request->input('cart_id');
        $districtId = $request->input('district');  // El distrito seleccionado
        $cart = Cart::find($cartId);

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito no existe.',
                'new_total' => number_format($cart->total_cart, 2) // Devolver el total sin descuento
            ]);
        }

        // Obtener el costo de envío dependiendo del distrito
        $shippingCost = 0; // Costo por defecto
        if ($districtId) {
            $district = ShippingDistrict::find($districtId); // Buscar el distrito
            if ($district) {
                $shippingCost = $district->shipping_cost; // Suponiendo que cada distrito tiene un campo 'shipping_cost'
            }
        }

        // Obtener el total con el envío
        $totalWithShipping = $cart->total_cart + $shippingCost;

        // Buscar el cupón
        $coupon = Coupon::where('name', $code)->where('status', 'active')->first();

        if (!$coupon || $code == "") {
            return response()->json([
                'success' => false,
                'message' => 'El código de promoción no es válido o está inactivo.',
                'new_total' => number_format($totalWithShipping, 2), // Devolver el total con envío sin descuento
                'coupon_name' => '' // Limpiar el nombre del cupón
            ]);
        }

        // Validar si el cupón ya ha sido usado
        $userCoupon = UserCoupon::where('user_id', auth()->id())
            ->where('coupon_id', $coupon->id)
            ->first();

        if ($userCoupon) {
            return response()->json([
                'success' => false,
                'message' => 'El cupón ya ha sido utilizado.',
                'new_total' => number_format($totalWithShipping, 2), // Devolver el total con envío sin descuento
                'coupon_name' => '' // Limpiar el nombre del cupón
            ]);
        }

        // Cálculo del descuento
        $total = $totalWithShipping; // Considerar el total con envío
        $discount = 0;

        /*if ($coupon->type == 'total') {
            // Si el cupón aplica al total
            if ($coupon->amount != 0) {
                $discount = $coupon->amount;
            } elseif ($coupon->percentage != 0) {
                $discount = $total * ($coupon->percentage / 100);
            }
        } elseif ($coupon->type == 'detail') {
            // Si el cupón aplica a un detalle
            $maxDetail = $cart->details->sortByDesc('subtotal')->first();

            if ($maxDetail) {
                if ($coupon->amount != 0) {
                    $discount = $coupon->amount;
                } elseif ($coupon->percentage != 0) {
                    $discount = $maxDetail->subtotal * ($coupon->percentage / 100);
                }
            }
        }*/
        if ($coupon->type == 'total') {
            // Validar que no haya productos de categoría 'combo' (category_id = 3)
            $hasCombo = $cart->details->contains(function ($detail) {
                return $detail->product->category_id == 3;
            });

            if ($hasCombo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El cupón no se puede aplicar a carritos que contengan combos.',
                    'new_total' => number_format($totalWithShipping, 2), // Devolver el total con envío sin descuento
                    'coupon_name' => '' // Limpiar el nombre del cupón
                ]);
            }

            // Aplicar el descuento al total
            if ($coupon->amount != 0) {
                $discount = $coupon->amount;
            } elseif ($coupon->percentage != 0) {
                $discount = $total * ($coupon->percentage / 100);
            }
        } elseif ($coupon->type == 'detail') {
            // Verificar si todos los productos son de categoría 'combo' (category_id = 3)
            $onlyCombos = $cart->details->every(function ($detail) {
                return $detail->product->category_id == 3;
            });

            if ($onlyCombos) {
                return response()->json([
                    'success' => false,
                    'message' => 'El cupón no se puede aplicar porque solo hay combos en el pedido.',
                    'new_total' => number_format($totalWithShipping, 2), // Devolver el total con envío sin descuento
                    'coupon_name' => '' // Limpiar el nombre del cupón
                ]);
            }



            // Filtrar los detalles que no sean de categoría 'combo' (category_id = 3)
            $eligibleDetails = $cart->details->filter(function ($detail) {
                return $detail->product->category_id != 3;
            });

            // Buscar el detalle elegible con el subtotal más alto
            $maxDetail = $eligibleDetails->sortByDesc('subtotal')->first();

            if ($maxDetail) {
                if ($coupon->amount != 0) {
                    $discount = $coupon->amount;
                } elseif ($coupon->percentage != 0) {
                    $discount = $maxDetail->subtotal * ($coupon->percentage / 100);
                }
            }
        }

        // Si el descuento es mayor que el total, ajustamos el descuento
        if ($discount > $total) {
            $discount = $total;
        }

        $newTotal = $total - $discount;

        return response()->json([
            'success' => true,
            'code_name' => $coupon->name,
            'discount_display' => '-S/ ' . number_format($discount, 2),
            'new_total' => number_format($newTotal, 2),
            'message' => 'Código aplicado. No borre el código.',
            'coupon_id' => $coupon->id,
            'district' => $districtId, // Devolver el distrito
        ]);
    }

    public function calculateShipping(Request $request)
    {
        //dd($request->all());
        $districtId = $request->district_id;
        $cart = $request->input('cart');
        $couponName = $request->coupon_name;

        // Obtener el costo de envío (0 si no hay distrito)
        $shippingCost = 0;
        if ($districtId) {
            $district = ShippingDistrict::find($districtId);
            if (!$district) {
                return response()->json(['success' => false, 'message' => 'Distrito no válido.'], 400);
            }
            $shippingCost = $district->shipping_cost;
        }

        // Calcular el subtotal del carrito
        //$cart = Cart::with('details')->find($cartId);
        if (!$cart || !is_array($cart)) {
            return response()->json(['success' => false, 'message' => 'Carrito no encontrado o inválido.'], 400);
        }

        // Hallar el total del carrito
        //$total = $cart->total_cart;
        $total = $this->getTotalCart($cart);
        //dd($total);
        $discount = 0;

        // Calcular el descuento si hay un cupón
        if ($couponName) {
            $coupon = Coupon::where('name', $couponName)->first();

            /*if ($coupon) {
                if ($coupon->type == 'total') {
                    if ($coupon->amount != 0) {
                        $discount = $coupon->amount;
                    } elseif ($coupon->percentage != 0) {
                        $discount = $total * ($coupon->percentage / 100);
                    }
                } elseif ($coupon->type == 'detail') {
                    $maxDetail = $cart->details->sortByDesc('subtotal')->first();

                    if ($maxDetail) {
                        if ($coupon->amount != 0) {
                            $discount = $coupon->amount;
                        } elseif ($coupon->percentage != 0) {
                            $discount = $maxDetail->subtotal * ($coupon->percentage / 100);
                        }
                    }
                }
            }*/
            // Lógica para calcular el descuento
            if ($coupon->type == 'total') {
                // Validar que no haya productos de categoría 'combo' (category_id = 3)
                /*$hasCombo = $cart->details->contains(function ($detail) {
                    return $detail->product->category_id == 3;
                });*/
                $hasCombo = $this->hasCombo($cart);

                if ($hasCombo) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'El cupón no se puede aplicar a carritos que contengan combos.',
                    ]);
                }

                // Aplicar descuento al total
                if ($coupon->amount != 0) {
                    $discount = $coupon->amount;
                } elseif ($coupon->percentage != 0) {
                    $discount = ($coupon->percentage / 100) * $cart->total_cart;
                }
            } elseif ($coupon->type == 'detail') {
                // Verificar si todos los productos son de categoría 'combo' (category_id = 3)
                /*$onlyCombos = $cart->details->every(function ($detail) {
                    return $detail->product->category_id == 3;
                });*/

                $onlyCombos = $this->onlyCombos($cart);

                if ($onlyCombos) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'El cupón no se puede aplicar porque solo hay combos en el pedido.',
                    ]);
                }

                // Filtrar los detalles que no sean de categoría 'combo' (category_id = 3)
                /*$eligibleDetails = $cart->details->filter(function ($detail) {
                    return $detail->product->category_id != 3;
                });*/

                // Buscar el detalle elegible con el subtotal más alto
                //$maxDetail = $eligibleDetails->sortByDesc('subtotal')->first();
                $maxDetail = $this->getMaxDetail($cart);

                if ($maxDetail) {
                    if ($coupon->amount != 0) {
                        $discount = $coupon->amount;
                    } elseif ($coupon->percentage != 0) {
                        $discount = ($coupon->percentage / 100) * $maxDetail['subtotal'];
                    }
                }
            }
        }

        // Ajustar el descuento si es mayor que el total
        if ($discount > $total) {
            $discount = $total;
        }

        // Calcular el nuevo total
        $newTotal = $total - $discount + $shippingCost;

        return response()->json([
            'success' => true,
            'shipping_cost' => (float)$shippingCost,
            'new_total' => (float)$newTotal,
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $cart = json_decode($request->input('cart'), true); // Decodificar cart
        $districtId = $request->input('district');
        $code = $request->input('code');

        if (!$cart || !is_array($cart)) {
            return response()->json(['success' => false, 'message' => 'Carrito no encontrado o inválido.'], 400);
        }

        // Obtener el costo de envío dependiendo del distrito
        $shippingCost = 0; // Costo por defecto
        if ($districtId) {
            $district = ShippingDistrict::find($districtId); // Buscar el distrito
            if ($district) {
                $shippingCost = $district->shipping_cost; // Suponiendo que cada distrito tiene un campo 'shipping_cost'
            }
        }

        // Obtener el total con el envío
        $total = $this->getTotalCart($cart);
        $totalWithShipping = $total + $shippingCost;

        // Buscar el cupón
        $coupon = Coupon::where('name', $code)->where('status', 'active')->first();

        if (!$coupon || $code == "") {
            return response()->json([
                'success' => false,
                'message' => 'El código de promoción no es válido o está inactivo.',
                'new_total' => number_format($totalWithShipping, 2), // Devolver el total con envío sin descuento
                'coupon_name' => '' // Limpiar el nombre del cupón
            ]);
        }

        // Validar si el cupón ya ha sido usado
        $userCoupon = UserCoupon::where('user_id', auth()->id())
            ->where('coupon_id', $coupon->id)
            ->first();

        if ($userCoupon) {
            return response()->json([
                'success' => false,
                'message' => 'El cupón ya ha sido utilizado.',
                'new_total' => number_format($totalWithShipping, 2), // Devolver el total con envío sin descuento
                'coupon_name' => '' // Limpiar el nombre del cupón
            ]);
        }

        // Cálculo del descuento
        $total = $totalWithShipping; // Considerar el total con envío
        $discount = 0;

        /*if ($coupon->type == 'total') {
            // Si el cupón aplica al total
            if ($coupon->amount != 0) {
                $discount = $coupon->amount;
            } elseif ($coupon->percentage != 0) {
                $discount = $total * ($coupon->percentage / 100);
            }
        } elseif ($coupon->type == 'detail') {
            // Si el cupón aplica a un detalle
            $maxDetail = $cart->details->sortByDesc('subtotal')->first();

            if ($maxDetail) {
                if ($coupon->amount != 0) {
                    $discount = $coupon->amount;
                } elseif ($coupon->percentage != 0) {
                    $discount = $maxDetail->subtotal * ($coupon->percentage / 100);
                }
            }
        }*/
        if ($coupon->type == 'total') {
            // Validar que no haya productos de categoría 'combo' (category_id = 3)
            /*$hasCombo = $cart->details->contains(function ($detail) {
                return $detail->product->category_id == 3;
            });*/
            $hasCombo = $this->hasCombo($cart);

            if ($hasCombo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El cupón no se puede aplicar a carritos que contengan combos.',
                    'new_total' => number_format($totalWithShipping, 2), // Devolver el total con envío sin descuento
                    'coupon_name' => '' // Limpiar el nombre del cupón
                ]);
            }

            // Aplicar el descuento al total
            if ($coupon->amount != 0) {
                $discount = $coupon->amount;
            } elseif ($coupon->percentage != 0) {
                $discount = $total * ($coupon->percentage / 100);
            }
        } elseif ($coupon->type == 'detail') {
            // Verificar si todos los productos son de categoría 'combo' (category_id = 3)
            /*$onlyCombos = $cart->details->every(function ($detail) {
                return $detail->product->category_id == 3;
            });*/

            $onlyCombos = $this->onlyCombos($cart);

            if ($onlyCombos) {
                return response()->json([
                    'success' => false,
                    'message' => 'El cupón no se puede aplicar porque solo hay combos en el pedido.',
                    'new_total' => number_format($totalWithShipping, 2), // Devolver el total con envío sin descuento
                    'coupon_name' => '' // Limpiar el nombre del cupón
                ]);
            }



            // Filtrar los detalles que no sean de categoría 'combo' (category_id = 3)
            $eligibleDetails = $cart->details->filter(function ($detail) {
                return $detail->product->category_id != 3;
            });

            // Buscar el detalle elegible con el subtotal más alto
            $maxDetail = $this->getMaxDetail($cart);

            if ($maxDetail) {
                if ($coupon->amount != 0) {
                    $discount = $coupon->amount;
                } elseif ($coupon->percentage != 0) {
                    $discount = ($coupon->percentage / 100) * $maxDetail['subtotal'];
                }
            }
        }

        // Si el descuento es mayor que el total, ajustamos el descuento
        if ($discount > $total) {
            $discount = $total;
        }

        $newTotal = $total - $discount;

        return response()->json([
            'success' => true,
            'code_name' => $coupon->name,
            'discount_display' => '-S/ ' . number_format($discount, 2),
            'new_total' => number_format($newTotal, 2),
            'message' => 'Código aplicado. No borre el código.',
            'coupon_id' => $coupon->id,
            'district' => $districtId, // Devolver el distrito
        ]);
    }

    function getTotalCart($cart)
    {
        $total = 0;

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $total += $product->price_default * $item['quantity'];
            }
        }

        return $total; // Retorna el total del carrito
    }

    function hasCombo($cart)
    {
        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->category_id == 3) {
                return true;
            }
        }
        return false;
    }

    function onlyCombos($cart)
    {
        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->category_id != 3) {
                return false; // Si hay al menos un producto que no es combo, retornamos false
            }
        }
        return true; // Todos los productos son combos
    }

    function getMaxDetail($cart)
    {
        $maxDetail = null;
        $maxSubtotal = 0;

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product && $product->category_id != 3) {
                $subtotal = $product->price * $item['quantity'];
                if ($subtotal > $maxSubtotal) {
                    $maxSubtotal = $subtotal;
                    $maxDetail = [
                        'product_id' => $item['product_id'],
                        'subtotal' => $subtotal,
                    ];
                }
            }
        }

        return $maxDetail; // Retorna el producto con el mayor subtotal elegible
    }

    public function calculateShippingOriginal(Request $request)
    {
        $districtId = $request->district_id;
        $cartId = $request->cart_id;
        $couponName = $request->coupon_name;

        // Obtener el costo de envío (0 si no hay distrito)
        $shippingCost = 0;
        if ($districtId) {
            $district = ShippingDistrict::find($districtId);
            if (!$district) {
                return response()->json(['success' => false, 'message' => 'Distrito no válido.'], 400);
            }
            $shippingCost = $district->shipping_cost;
        }

        // Calcular el subtotal del carrito
        $cart = Cart::with('details')->find($cartId);
        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Carrito no encontrado.'], 400);
        }

        $total = $cart->total_cart;
        $discount = 0;

        // Calcular el descuento si hay un cupón
        if ($couponName) {
            $coupon = Coupon::where('name', $couponName)->first();

            /*if ($coupon) {
                if ($coupon->type == 'total') {
                    if ($coupon->amount != 0) {
                        $discount = $coupon->amount;
                    } elseif ($coupon->percentage != 0) {
                        $discount = $total * ($coupon->percentage / 100);
                    }
                } elseif ($coupon->type == 'detail') {
                    $maxDetail = $cart->details->sortByDesc('subtotal')->first();

                    if ($maxDetail) {
                        if ($coupon->amount != 0) {
                            $discount = $coupon->amount;
                        } elseif ($coupon->percentage != 0) {
                            $discount = $maxDetail->subtotal * ($coupon->percentage / 100);
                        }
                    }
                }
            }*/
            // Lógica para calcular el descuento
            if ($coupon->type == 'total') {
                // Validar que no haya productos de categoría 'combo' (category_id = 3)
                $hasCombo = $cart->details->contains(function ($detail) {
                    return $detail->product->category_id == 3;
                });

                if ($hasCombo) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'El cupón no se puede aplicar a carritos que contengan combos.',
                    ]);
                }

                // Aplicar descuento al total
                if ($coupon->amount != 0) {
                    $discount = $coupon->amount;
                } elseif ($coupon->percentage != 0) {
                    $discount = ($coupon->percentage / 100) * $cart->total_cart;
                }
            } elseif ($coupon->type == 'detail') {
                // Verificar si todos los productos son de categoría 'combo' (category_id = 3)
                $onlyCombos = $cart->details->every(function ($detail) {
                    return $detail->product->category_id == 3;
                });

                if ($onlyCombos) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'El cupón no se puede aplicar porque solo hay combos en el pedido.',
                    ]);
                }

                // Filtrar los detalles que no sean de categoría 'combo' (category_id = 3)
                $eligibleDetails = $cart->details->filter(function ($detail) {
                    return $detail->product->category_id != 3;
                });

                // Buscar el detalle elegible con el subtotal más alto
                $maxDetail = $eligibleDetails->sortByDesc('subtotal')->first();

                if ($maxDetail) {
                    if ($coupon->amount != 0) {
                        $discount = $coupon->amount;
                    } elseif ($coupon->percentage != 0) {
                        $discount = ($coupon->percentage / 100) * $maxDetail->subtotal;
                    }
                }
            }
        }

        // Ajustar el descuento si es mayor que el total
        if ($discount > $total) {
            $discount = $total;
        }

        // Calcular el nuevo total
        $newTotal = $total - $discount + $shippingCost;

        return response()->json([
            'success' => true,
            'shipping_cost' => (float)$shippingCost,
            'new_total' => (float)$newTotal,
        ]);
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

            // Eliminar los options
            $options = CartDetailOption::where('cart_detail_id', $detail->id)->get();

            foreach ( $options as $option )
            {
                $option->delete();
            }

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

    public function saveObservation( Request $request, $cart_id )
    {
        DB::beginTransaction();
        try {
            $cart = Cart::find($cart_id);
            $cart->observations = $request->get('observation'); // Cambiar estado a inactivo
            $cart->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Observación guardad con éxito.'], 200);
    }
}
