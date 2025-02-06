<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class OrdersChartController extends Controller
{
    public function getChartData(Request $request)
    {
        $filter = $request->input('filter', 'daily');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $adminIds = User::where('is_admin', 1)->pluck('id');

        if ($filter === 'daily') {
            $startDate = Carbon::today();
            $endDate = Carbon::today();
            $data = $this->getOrdersData($startDate, $endDate, $adminIds);
            $data['labels'] = [$startDate->format('Y-m-d')]; // Agregar la fecha al array de etiquetas
            $data['whatsapp'] = [$data['whatsapp']]; // Asegúrate de que whatsapp esté en un array
            $data['web'] = [$data['web']]; // Asegúrate de que web esté en un array
        } elseif ($filter === 'weekly') {
            $data = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $data['labels'][] = $date->format('Y-m-d');
                $orders = $this->getOrdersData($date, $date, $adminIds);
                $data['whatsapp'][] = $orders['whatsapp'];
                $data['web'][] = $orders['web'];
            }
        } elseif ($filter === 'monthly') {
            $data = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subMonths($i)->startOfMonth();
                $endMonth = $date->copy()->endOfMonth();
                $data['labels'][] = $date->format('Y-m');
                $orders = $this->getOrdersData($date, $endMonth, $adminIds);
                $data['whatsapp'][] = $orders['whatsapp'];
                $data['web'][] = $orders['web'];
            }
        } elseif ($filter === 'date_range' && $startDate && $endDate) {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
            $data = [];
            while ($startDate <= $endDate) {
                $data['labels'][] = $startDate->format('Y-m-d');
                $orders = $this->getOrdersData($startDate, $startDate, $adminIds);
                $data['whatsapp'][] = $orders['whatsapp'];
                $data['web'][] = $orders['web'];
                $startDate->addDay();
            }
        } else {
            return response()->json(['error' => 'Invalid filter'], 400);
        }

        // Calcular el total de whatsapp y web
        $totalWhatsapp = array_sum($data['whatsapp']);
        $totalWeb = array_sum($data['web']);
        $totalOrders = $totalWhatsapp + $totalWeb;

        // Calcular los porcentajes
        $whatsappPercentage = $totalOrders > 0 ? round(($totalWhatsapp / $totalOrders) * 100, 2) : 0;
        $webPercentage = $totalOrders > 0 ? round(($totalWeb / $totalOrders) * 100, 2) : 0;

        // Agregar los totales y porcentajes al array de datos
        $data['total_whatsapp'] = $totalWhatsapp;
        $data['total_web'] = $totalWeb;
        $data['total'] = $totalOrders;
        $data['whatsapp_percentage'] = $whatsappPercentage;
        $data['web_percentage'] = $webPercentage;
        $data['total_percentage'] = 100;

        return response()->json($data);
    }

    public function getChartDataPromo(Request $request)
    {
        $filter = $request->input('filter', 'daily');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($filter === 'daily') {
            $startDate = Carbon::today();
            $endDate = Carbon::today();
        } elseif ($filter === 'weekly') {
            $startDate = Carbon::today()->subDays(6);
            $endDate = Carbon::today();
        } elseif ($filter === 'monthly') {
            $startDate = Carbon::today()->subMonths(1)->startOfMonth();
            $endDate = Carbon::today()->endOfMonth();
        } elseif ($filter === 'date_range_promo' && $startDate && $endDate) {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
        } else {
            return response()->json(['error' => 'Invalid filter'], 400);
        }

        // Obtener todas las órdenes en el rango de fechas
        $orders = Order::whereDate('created_at', '>=', $startDate)  // Mayor o igual a la fecha de inicio
                        ->whereDate('created_at', '<=', $endDate)    // Menor o igual a la fecha de fin
                        ->where('state_annulled', 0)->pluck('id');

        // Obtener los cupones usados en las órdenes del rango
        $usedCoupons = UserCoupon::whereIn('order_id', $orders)
            ->selectRaw('coupon_id, COUNT(*) as count')
            ->groupBy('coupon_id')
            ->pluck('count', 'coupon_id');

        // Obtener todos los cupones (incluso los no usados)
        $allCoupons = Coupon::select('id', 'name')->get();

        // Calcular totales y porcentajes
        $totalCouponsUsed = $usedCoupons->sum(); // Suma total de todos los usos de cupones

        $data = ['coupons' => []];

        foreach ($allCoupons as $coupon) {
            $count = $usedCoupons[$coupon->id] ?? 0; // Si no está en `usedCoupons`, se usa 0
            $percentage = ($totalCouponsUsed > 0) ? round(($count / $totalCouponsUsed) * 100, 2) : 0;

            $data['coupons'][] = [
                'code' => $coupon->name,
                'count' => $count,
                'percentage' => $percentage
            ];
        }

        return response()->json($data);
    }

    private function getOrdersData($startDate, $endDate, $adminIds)
    {
        return [
            'whatsapp' => Order::whereIn('user_id', $adminIds)
                ->whereDate('created_at', '>=', $startDate)  // Mayor o igual a la fecha de inicio
                ->whereDate('created_at', '<=', $endDate)    // Menor o igual a la fecha de fin
                ->where('state_annulled', 0)
                ->count(),

            'web' => Order::whereNotIn('user_id', $adminIds)
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->where('state_annulled', 0)
                ->count()
        ];
    }
}
