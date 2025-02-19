<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DataGeneral;
use App\Models\Department;
use App\Models\District;
use App\Models\Motivo;
use App\Models\Product;
use App\Models\Province;
use App\Models\Slider;
use App\Models\Submotivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function welcome()
    {
        $categories = Category::with('products')
            ->where('visible', 1)
            ->where('enable_status', 1)
            ->get();

        // Obtener los IDs de las categorías visibles
        $categoryIds = $categories->pluck('id')->toArray();

        // Orden deseado (sin incluir la categoría excluida manualmente)
        $order = [1, 2, 5, 3, 7, 6];

        // Ajustar el orden para considerar solo categorías visibles
        $order = array_filter($order, function ($id) use ($categoryIds) {
            return in_array($id, $categoryIds);
        });

        // Obtener los productos habilitados que pertenecen a las categorías seleccionadas
        $products = Product::with('category:id,name')
            ->where('enable_status', 1)
            ->whereIn('category_id', $categoryIds) // Solo productos en las categorías visibles
            ->orderByRaw('FIELD(category_id, ' . implode(',', $order) . ')') // Categorías en orden específico
            ->orderBy('category_id') // Ordenar categorías no listadas
            ->orderBy('id') // Ordenar por ID como criterio secundario
            ->get();

        $slidersSmalls = Slider::where('size', 's')->where('active', 1)->orderBy('order', 'asc')->get();
        $slidersLarges = Slider::where('size', 'l')->where('active', 1)->orderBy('order', 'asc')->get();

        return view('welcome', compact('categories', 'products', 'slidersSmalls', 'slidersLarges'));
    }

    public function isAuthenticated()
    {
        return response()->json([
            'authenticated' => auth()->check(),
            'user_id' => auth()->check() ? auth()->id() : null,
            ]
        );
    }

    public function menu()
    {
        // Obtener las categorías visibles (incluyendo las soft-deleted si es necesario)
        $categories = Category::with('products')
            ->where('visible', 1)
            ->where('enable_status', 1)
            ->get();

        // Obtener los productos habilitados que pertenecen a las categorías seleccionadas
        $categoryIds = $categories->pluck('id'); // Obtener los IDs de las categorías visibles

        $products = Product::whereIn('category_id', $categoryIds) // Filtrar productos por las categorías seleccionadas
        ->where('enable_status', 1) // Solo productos habilitados
        ->get();

        return view('welcome.menu', compact('categories', 'products'));
    }

    public function about()
    {
        return view('welcome.about');
    }

    public function goToDashboard()
    {
        $status = DataGeneral::getValue('status_store');
        //dd($status);
        return view('welcome.dashboard', compact('status'));
    }

    public function getRegisteredUsers()
    {
        // Obtener los teléfonos desde la base de datos
        $phones = DB::table('addresses')->pluck('phone')->toArray();

        $uniquePhones = [];

        foreach ($phones as $phone) {
            // Eliminar espacios en blanco
            $phone = str_replace(' ', '', $phone); // ✅ Elimina TODOS los espacios

            // Si el número comienza con "+XX", eliminarlo (donde XX son dos dígitos)
            $phone = preg_replace('/^\+\d{2}/', '', $phone);

            // Guardar solo teléfonos únicos
            $uniquePhones[$phone] = true;
        }

        // Devolver la cantidad de usuarios únicos
        return response()->json(['registeredUsers' => count($uniquePhones)]);
    }

    public function getTopClients()
    {
        // Obtener los teléfonos y nombres desde la tabla addresses
        $addresses = DB::table('addresses')->select('phone', 'first_name', 'last_name')->get();

        $clientsData = [];

        foreach ($addresses as $address) {
            // Eliminar espacios internos
            $phone = str_replace(' ', '', $address->phone);

            // Si el número comienza con "+XX", eliminar el código de país
            $phone = preg_replace('/^\+\d{2}/', '', $phone);

            // Crear clave única para cada cliente
            $key = $phone;

            // Si ya existe en el array, aumentar el contador de pedidos
            if (isset($clientsData[$key])) {
                $clientsData[$key]['orders'] += 1;
            } else {
                // Si es la primera vez que se encuentra este número, inicializar
                $clientsData[$key] = [
                    'phone' => $phone,
                    'first_name' => $address->first_name,
                    'last_name' => $address->last_name,
                    'orders' => 1
                ];
            }
        }

        // Convertir el array a colección y ordenar por pedidos descendente
        $clients = collect($clientsData)->sortByDesc('orders')->values();

        // Si no hay datos, retornar estructura vacía
        if ($clients->isEmpty()) {
            return response()->json(['clients' => []]);
        }

        // Obtener el máximo de pedidos para calcular porcentajes
        $maxOrders = $clients->first()['orders'];

        // Calcular el porcentaje de cada cliente basado en el mayor número de pedidos
        $clients = $clients->map(function ($client) use ($maxOrders) {
            $client['percentage'] = round(($client['orders'] / $maxOrders) * 100, 1);
            return $client;
        });

        return response()->json([
            'clients' => $clients,
            'maxOrders' => $maxOrders
        ]);
    }
}
