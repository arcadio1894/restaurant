<?php

namespace App\Http\Controllers;

use App\Models\CashMovement;
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

    public function getChartDataSale(Request $request)
    {
        $filter = $request->input('filter', 'daily');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Obtener IDs de administradores (WhatsApp)
        $adminIds = User::where('is_admin', 1)->pluck('id');

        $data = [
            'labels' => [],
            'sales' => [] // Array de ventas por fecha
        ];

        // Variables para el total de ventas generales
        $whatsappSalesTotal = 0;
        $webSalesTotal = 0;

        if ($filter === 'daily') {
            $startDate = Carbon::today();
            $endDate = Carbon::today();
            $salesData = $this->getSalesData($startDate, $endDate, $adminIds);

            $data['labels'][] = $startDate->format('d-m-Y');
            $data['sales'][] = $salesData['sales_total'];

            $whatsappSalesTotal = $salesData['whatsapp_sales'];
            $webSalesTotal = $salesData['web_sales'];

        } elseif ($filter === 'weekly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $data['labels'][] = $date->format('d-m-Y');

                $salesData = $this->getSalesData($date, $date, $adminIds);
                $data['sales'][] = $salesData['sales_total'];

                $whatsappSalesTotal += $salesData['whatsapp_sales'];
                $webSalesTotal += $salesData['web_sales'];
            }

        } elseif ($filter === 'monthly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subMonths($i)->startOfMonth();
                $endMonth = $date->copy()->endOfMonth();

                $data['labels'][] = $date->format('m-Y');

                $salesData = $this->getSalesData($date, $endMonth, $adminIds);
                $data['sales'][] = $salesData['sales_total'];

                $whatsappSalesTotal += $salesData['whatsapp_sales'];
                $webSalesTotal += $salesData['web_sales'];
            }

        } elseif ($filter === 'date_range' && $startDate && $endDate) {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);

            while ($startDate <= $endDate) {
                $data['labels'][] = $startDate->format('d-m-Y');

                $salesData = $this->getSalesData($startDate, $startDate, $adminIds);
                $data['sales'][] = $salesData['sales_total'];

                $whatsappSalesTotal += $salesData['whatsapp_sales'];
                $webSalesTotal += $salesData['web_sales'];

                $startDate->addDay();
            }
        } else {
            return response()->json(['error' => 'Invalid filter'], 400);
        }

        // Calcular totales y porcentajes
        $totalSales = $whatsappSalesTotal + $webSalesTotal;
        $whatsappPercentage = $totalSales > 0 ? round(($whatsappSalesTotal / $totalSales) * 100, 2) : 0;
        $webPercentage = $totalSales > 0 ? round(($webSalesTotal / $totalSales) * 100, 2) : 0;

        // Agregar datos de totales SIN afectar el formato del gráfico
        $data['total_whatsapp'] = number_format($whatsappSalesTotal, 2, '.', '');
        $data['total_web'] = number_format($webSalesTotal, 2, '.', '');
        $data['total'] = number_format($totalSales, 2, '.', '');
        $data['whatsapp_percentage'] = number_format($whatsappPercentage, 2, '.', '');
        $data['web_percentage'] = number_format($webPercentage, 2, '.', '');
        $data['total_percentage'] = 100;

        return response()->json($data);
    }

    private function getSalesData($startDate, $endDate, $adminIds)
    {
        /*// Obtener todas las órdenes del rango de fechas
        $whatsappOrders = Order::whereIn('user_id', $adminIds)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('state_annulled', 0)
            ->get(); // Obtener los modelos para acceder al accesor

        $webOrders = Order::whereNotIn('user_id', $adminIds)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('state_annulled', 0)
            ->get();

        // Usar collect()->sum() con función anónima tradicional
        $whatsappSales = $whatsappOrders->sum(function ($order) {
            return $order->amount_pay;
        });

        $webSales = $webOrders->sum(function ($order) {
            return $order->amount_pay;
        });

        return [
            'sales_total' => round($whatsappSales + $webSales, 2), // Total para el gráfico por fecha
            'whatsapp_sales' => $whatsappSales,
            'web_sales' => $webSales
        ];*/
        $orders = Order::with(['cashMovements' => function ($query) {
            $query->where('type', 'sale');
        }])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('state_annulled', 0)
            ->get();

        $whatsappSales = 0;
        $webSales = 0;

        foreach ($orders as $order) {
            $isWhatsapp = in_array($order->user_id, $adminIds->toArray());
            $movements = $order->cashMovements;

            // Buscar si tiene movimiento POS
            $posMovement = $movements->first(function ($m) {
                return $m->subtype === 'pos';
            });

            if ($posMovement) {
                if ($posMovement->regularize) {
                    $amount = $posMovement->amount;
                } else {
                    continue; // No sumar esta orden si POS no está regularizada
                }
            } else {
                // No es POS, tomar el valor original de la orden
                $amount = $order->amount_pay;
            }

            if ($isWhatsapp) {
                $whatsappSales += $amount;
            } else {
                $webSales += $amount;
            }
        }

        return [
            'sales_total' => round($whatsappSales + $webSales, 2),
            'whatsapp_sales' => $whatsappSales,
            'web_sales' => $webSales
        ];
    }

    public function getChartDataCashFlow(Request $request)
    {
        $filter = $request->input('filter', 'daily');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = [
            'labels' => [],
            'incomes' => [], // Ingresos por fecha
            'expenses' => []  // Egresos por fecha
        ];

        // Variables para el total de ingresos y egresos
        $totalIncome = 0;
        $totalExpense = 0;

        if ($filter === 'daily') {
            $startDate = Carbon::today();
            $endDate = Carbon::today();
            $cashData = $this->getCashData($startDate, $endDate);

            $data['labels'][] = $startDate->format('d-m-Y');
            $data['incomes'][] = $cashData['income_total'];
            $data['expenses'][] = $cashData['expense_total'];

            $totalIncome = $cashData['income_total'];
            $totalExpense = $cashData['expense_total'];

        } elseif ($filter === 'weekly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $data['labels'][] = $date->format('d-m-Y');

                $cashData = $this->getCashData($date, $date);
                $data['incomes'][] = $cashData['income_total'];
                $data['expenses'][] = $cashData['expense_total'];

                $totalIncome += $cashData['income_total'];
                $totalExpense += $cashData['expense_total'];
            }

        } elseif ($filter === 'monthly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subMonths($i)->startOfMonth();
                $endMonth = $date->copy()->endOfMonth();

                $data['labels'][] = $date->format('m-Y');

                $cashData = $this->getCashData($date, $endMonth);
                $data['incomes'][] = $cashData['income_total'];
                $data['expenses'][] = $cashData['expense_total'];

                $totalIncome += $cashData['income_total'];
                $totalExpense += $cashData['expense_total'];
            }

        } elseif ($filter === 'date_range' && $startDate && $endDate) {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);

            while ($startDate <= $endDate) {
                $data['labels'][] = $startDate->format('d-m-Y');

                $cashData = $this->getCashData($startDate, $startDate);
                $data['incomes'][] = $cashData['income_total'];
                $data['expenses'][] = $cashData['expense_total'];

                $totalIncome += $cashData['income_total'];
                $totalExpense += $cashData['expense_total'];

                $startDate->addDay();
            }
        } else {
            return response()->json(['error' => 'Invalid filter'], 400);
        }

        // Calcular utilidad
        $profit = $totalIncome - $totalExpense;

        // Agregar datos de totales
        $data['total_income'] = number_format($totalIncome, 2, '.', '');
        $data['total_expense'] = number_format($totalExpense, 2, '.', '');
        $data['profit'] = number_format($profit, 2, '.', '');

        return response()->json($data);
    }

    /**
     * Obtiene los ingresos y egresos en un rango de fechas.
     */
    private function getCashData($startDate, $endDate)
    {
        // Sumar ingresos (type = income) + (type = sale con regularize = 1)
        $incomeTotal = CashMovement::
        /*whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])*/
            whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where(function ($query) {
                $query->where('type', 'income')
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('type', 'sale')->where('regularize', 1);
                    });
            })
            ->sum('amount');

        // Sumar egresos (type = expense)
        $expenseTotal = CashMovement::
        /*whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])*/
            whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('type', 'expense')
            ->sum('amount');

        return [
            'income_total' => $incomeTotal,
            'expense_total' => $expenseTotal
        ];
    }
}
