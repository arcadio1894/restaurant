<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Events\OrderStatusUpdated;
use App\Mail\OrderStatusEmail;
use App\Models\Address;
use App\Models\Order;
use App\Models\ShippingDistrict;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        // Obtiene los pedidos con paginación (10 pedidos por página, puedes ajustarlo)
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        // Retorna la vista con los pedidos paginados
        return view('order.index', compact('orders'));
    }

    public function indexKanban()
    {
        return view('kanban.index');
    }

    public function getOrders()
    {

    }

    public function indexAdmin()
    {
        $registros = Order::all();

        $arrayYears = $registros->pluck('created_at')->map(function ($date) {
            return Carbon::parse($date)->format('Y');
        })->unique()->toArray();

        $arrayYears = array_values($arrayYears);

        return view('order.list', compact('arrayYears'));

    }

    public function indexAdminAnnulled()
    {
        $registros = Order::all();

        $arrayYears = $registros->pluck('created_at')->map(function ($date) {
            return Carbon::parse($date)->format('Y');
        })->unique()->toArray();

        $arrayYears = array_values($arrayYears);

        return view('order.listAnnulled', compact('arrayYears'));

    }

    public function getOrdersAdmin(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $code = $request->input('code');
        $year = $request->input('year');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        if ( $startDate == "" || $endDate == "" )
        {
            $query = Order::where('state_annulled', 0)->orderBy('created_at', 'DESC');
        } else {
            $fechaInicio = Carbon::createFromFormat('d/m/Y', $startDate);
            $fechaFinal = Carbon::createFromFormat('d/m/Y', $endDate);

            $query = Order::whereDate('created_at', '>=', $fechaInicio)
                ->whereDate('created_at', '<=', $fechaFinal)
                ->where('state_annulled', 0)
                ->orderBy('created_at', 'DESC');
        }

        // Aplicar filtros si se proporcionan
        if ($code != "") {
            $query->where('id', 'LIKE', '%'.$code.'%');

        }

        if ($year != "") {
            $query->whereYear('created_at', $year);

        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $orders = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $arrayGuides = [];

        foreach ( $orders as $order )
        {
            $direccion = Address::find($order->shipping_address_id);
            $distrito = ShippingDistrict::find($order->shipping_district_id);
            array_push($arrayGuides, [
                "id" => $order->id,
                "code" => "ORDEN - ".$order->id,
                "date" => ($order->created_at != null) ? $order->formatted_created_date : "",
                "date_delivery" => ($order->created_at != null) ? $order->formatted_date : "",
                "customer" => $direccion->first_name." ".$direccion->last_name,
                "phone" => $direccion->phone,
                "address" => $direccion->address_line. " - ".( (!isset($distrito)) ? 'N/A':$distrito->name),
                "latitude" => $direccion->latitude,
                "longitude" => $direccion->longitude,
                "total" => $order->amount_pay,
                "method" => ($order->payment_method_id == null) ? 'Sin método de pago':$order->payment_method->name ,
                "state" => $order->status_name,
                "data_payment" => $order->data_payment,
            ]);
        }

        $pagination = [
            'currentPage' => (int)$pageNumber,
            'totalPages' => (int)$totalPages,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'totalRecords' => $totalFilteredRecords,
            'totalFilteredRecords' => $totalFilteredRecords
        ];

        return ['data' => $arrayGuides, 'pagination' => $pagination];
    }

    public function getOrdersAnnulledAdmin(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $code = $request->input('code');
        $year = $request->input('year');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        if ( $startDate == "" || $endDate == "" )
        {
            $query = Order::where('state_annulled', 1)->orderBy('created_at', 'DESC');
        } else {
            $fechaInicio = Carbon::createFromFormat('d/m/Y', $startDate);
            $fechaFinal = Carbon::createFromFormat('d/m/Y', $endDate);

            $query = Order::whereDate('created_at', '>=', $fechaInicio)
                ->whereDate('created_at', '<=', $fechaFinal)
                ->where('state_annulled', 1)
                ->orderBy('created_at', 'DESC');
        }

        // Aplicar filtros si se proporcionan
        if ($code != "") {
            $query->where('id', 'LIKE', '%'.$code.'%');

        }

        if ($year != "") {
            $query->whereYear('created_at', $year);

        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $orders = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $arrayGuides = [];

        foreach ( $orders as $order )
        {
            $direccion = Address::find($order->shipping_address_id);
            $distrito = ShippingDistrict::find($order->shipping_district_id);
            array_push($arrayGuides, [
                "id" => $order->id,
                "code" => "ORDEN - ".$order->id,
                "date" => ($order->created_at != null) ? $order->formatted_created_date : "",
                "date_delivery" => ($order->created_at != null) ? $order->formatted_date : "",
                "customer" => $direccion->first_name." ".$direccion->last_name,
                "phone" => $direccion->phone,
                "address" => $direccion->address_line. " - ".( (!isset($distrito)) ? 'N/A':$distrito->name),
                "latitude" => $direccion->latitude,
                "longitude" => $direccion->longitude,
                "total" => $order->amount_pay,
                "method" => ($order->payment_method_id == null) ? 'Sin método de pago':$order->payment_method->name ,
                "state" => $order->status_name,
                "data_payment" => $order->data_payment,
            ]);
        }

        $pagination = [
            'currentPage' => (int)$pageNumber,
            'totalPages' => (int)$totalPages,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'totalRecords' => $totalFilteredRecords,
            'totalFilteredRecords' => $totalFilteredRecords
        ];

        return ['data' => $arrayGuides, 'pagination' => $pagination];
    }

    public function changeIOrderState($order_id, $state)
    {
        DB::beginTransaction();
        try {

            $order = Order::find($order_id);
            $order->status = $state;
            $order->save();

            $order2 = Order::find($order_id);

            // Obtener el correo electrónico según la lógica.
            $email = $this->getEmailForOrder($order2);

            if ($email) {
                // Enviar correo al cliente con el estado de la orden.
                Mail::to($email)->send(new OrderStatusEmail($order2));
                Log::info('Correo enviado a: ' . $email . ' con el estado de la orden: ' . $state);
            } else {
                Log::warning('No se encontró un correo electrónico para enviar el estado de la orden.');
            }

            Log::info('Emitiendo evento para la orden:', $order2->toArray());
            broadcast(new OrderStatusUpdated($order2));

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            Log::error('Error cambiando estado de la orden: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Cambio de estado realizado con éxito'], 200);
    }

    public function anularOrder($order_id)
    {
        DB::beginTransaction();
        try {

            $order = Order::find($order_id);
            $order->state_annulled = 1;
            $order->save();

            Log::info('Emitiendo evento para la orden:', $order->toArray());

            DB::commit();

            return response()->json(['message' => 'Orden anulada con éxito'], 200);

        } catch ( \Throwable $e ) {
            DB::rollBack();
            Log::error('Error anulando la orden: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 422);
        }

    }

    public function activarOrder($order_id)
    {
        DB::beginTransaction();
        try {

            $order = Order::find($order_id);
            $order->state_annulled = 0;
            $order->save();

            Log::info('Emitiendo evento para la orden:', $order->toArray());

            DB::commit();

            return response()->json(['message' => 'Orden anulada con éxito'], 200);

        } catch ( \Throwable $e ) {
            DB::rollBack();
            Log::error('Error anulando la orden: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 422);
        }

    }

    private function getEmailForOrder($order)
    {
        // Obtener shipping_address si existe
        $shippingAddress = $order->shipping_address;

        if ($shippingAddress && !empty($shippingAddress->email)) {
            return $shippingAddress->email; // Usar el email del shipping_address si está disponible.
        }

        // Obtener email del usuario asociado a la orden si no hay shipping_address o su email está vacío.
        $user = $order->user;
        if ($user && !empty($user->email)) {
            return $user->email;
        }

        // Si no hay correo en shipping_address ni en el usuario, retornar null.
        return null;
    }

    public function getOrderDetails($orderId)
    {

        $order = Order::with([
            'details.productType.type',
            'details.product',
            'details.options.option',
            'details.options.product',
        ])->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        $details = $order->details->map(function ($detail) {
            return [
                'pizza_name' => $detail->product->full_name,
                'quantity' => $detail->quantity,
                'type' => ($detail->product_type_id == null) ? 'N/A': $detail->productType->type->name,
                'size' => ($detail->product_type_id == null) ? 'N/A': $detail->productType->type->size,
                'ingredients' => ($detail->product->ingredients == null) ? 'N/A':$detail->product->ingredients,
                'options' => $detail->options->map(function ($option) {
                    return [
                        'product_name' => ($option->product->full_name == null) ? 'N/A': $option->product->full_name,
                    ];
                }),
            ];
        });

        return response()->json(['details' => $details], 200);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        // Buscar la orden en la base de datos
        $order = Order::find($id);

        // Verificar si la orden existe
        if (!$order) {
            return response()->json(['error' => 'Orden no encontrada'], 404);
        }

        // Devolver la orden en formato JSON
        return response()->json($order);
    }

    public function updateTime(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id',
            'estimated_time' => 'required|integer|min:1',
            'status' => 'required|string'
        ]);

        $order = Order::findOrFail($request->id);
        $order->estimated_time = $request->estimated_time; // Guardar el tiempo estimado
        $order->date_processing = Carbon::now('America/Lima');
        $order->status = $request->status; // Cambiar estado a "processing"
        $order->save();

        return response()->json([
            'message' => 'Tiempo estimado actualizado correctamente',
            'order' => $order
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id',
            'status' => 'required|string'
        ]);

        $order = Order::findOrFail($request->id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'Estado actualizado correctamente',
            'order' => $order
        ]);
    }

    public function updateDistributor(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id',
            'status' => 'required|string|in:shipped',
            'distributor_id' => 'required|exists:distributors,id'
        ]);

        $order = Order::findOrFail($request->id);
        $order->status = $request->status;
        $order->distributor_id = $request->distributor_id;
        $order->save();

        // 🚀 Retornar la orden actualizada para su renderización
        return response()->json($order);
    }

    public function edit(Order $order)
    {
        //
    }

    public function update(Request $request, Order $order)
    {
        //
    }

    public function destroy(Order $order)
    {
        //
    }

    public function generarOrder()
    {
        $orden = Order::find(70);

        broadcast(new OrderCreated($orden));
    }
}
