<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::all();
        return view('coupon.index', compact('coupons'));
    }

    public function getDataCoupons(Request $request, $pageNumber = 1)
    {
        $perPage = 10;
        $name = $request->input('name');

        $query = Coupon::orderBy('id');

        // Aplicar filtros si se proporcionan
        if ($name != "") {
            // Convertir la cadena de búsqueda en un array de palabras clave
            $keywords = explode(' ', $name);

            // Construir la consulta para buscar todas las palabras clave en el campo full_name
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->where('name', 'LIKE', '%' . $keyword . '%');
                }
            });

            // Asegurarse de que todas las palabras clave estén presentes en la descripción
            foreach ($keywords as $keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            }
        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $coupons = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $array = [];

        foreach ( $coupons as $coupon )
        {
            if ( $coupon->status == 'active' )
            {
                $stateText = '<span class="badge bg-success">Activo</span>';
            } else {
                $stateText = '<span class="badge bg-danger">Inactivo</span>';
            }

            array_push($array, [
                "id" => $coupon->id,
                "nombre" => $coupon->name,
                "descripcion" => $coupon->description,
                "precio" => $coupon->amount,
                "porcentaje" => $coupon->percentage,
                "estado" => $stateText,
                "status" => $coupon->status
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

        return ['data' => $array, 'pagination' => $pagination];
    }

    public function create()
    {
        return view('coupon.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'name' => 'required|unique:coupons,name|max:255',
                'description' => 'nullable|string',
                'amount' => 'nullable|numeric',
                'percentage' => 'nullable|numeric'
            ]);

            Coupon::create($request->all());

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cambios guardados con éxito.'], 200);
    }

    public function edit(Coupon $coupon)
    {
        return view('coupon.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|max:255|unique:coupons,name,' . $coupon->id,
                'description' => 'nullable|string',
                'amount' => 'nullable|numeric',
                'percentage' => 'nullable|numeric'
            ]);

            $coupon->update($request->all());

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cambios guardados con éxito.'], 200);

    }

    public function destroy(Coupon $coupon)
    {
        DB::beginTransaction();
        try {

            $coupon->update(['status' => 'inactive']); // Cambiar estado a inactivo

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cupón desactivado con éxito.'], 200);

    }
}
