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
        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('coupons.create');
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
        return view('coupons.edit', compact('coupon'));
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
