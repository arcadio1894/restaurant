<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function getOrdersAdmin(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $code = $request->input('code');
        $year = $request->input('year');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        if ( $startDate == "" || $endDate == "" )
        {
            $query = Order::orderBy('created_at', 'DESC');
        } else {
            $fechaInicio = Carbon::createFromFormat('d/m/Y', $startDate);
            $fechaFinal = Carbon::createFromFormat('d/m/Y', $endDate);

            $query = Order::whereDate('created_at', '>=', $fechaInicio)
                ->whereDate('created_at', '<=', $fechaFinal)
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
            array_push($arrayGuides, [
                "id" => $order->id,
                "code" => "ORDEN - ".$order->id,
                "date" => ($order->created_at != null) ? $order->formatted_date : "",
                "total" => $order->total_amount,
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

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Cambio de estado realizado con éxito'], 200);
    }

    public function getOrderDetails($orderId)
    {
        $order = Order::with(['details.productType.type', 'details.product'])->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        $details = $order->details->map(function ($detail) {
            return [
                'pizza_name' => $detail->product->full_name,
                'type' => $detail->productType->type->name,
                'size' => ($detail->productType->type->size == null) ? 'N/A': $detail->productType->type->size,
                'ingredients' => ($detail->product->ingredients == null) ? 'N/A':$detail->product->ingredients,
            ];
        });

        return response()->json(['details' => $details], 200);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Order $order)
    {
        //
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
}
