<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\District;
use App\Models\Province;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('shop.index', compact('departments'));
    }

    public function getDataShops(Request $request, $pageNumber = 1)
    {
        $perPage = 10;

        $name = $request->input('name');
        $department = $request->input('department');
        $province = $request->input('province');
        $district = $request->input('district');
        $status = $request->input('status');

        $query = Shop::orderBy('id');

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

        if ($department != "") {
            $query->where('department_id', $department);
        }

        if ($province != "") {
            $query->where('province_id', $province);
        }

        if ($district != "") {
            $query->where('district_id', $district);
        }

        if ($status != "") {
            $query->where('status', $status);
        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $shops = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $array = [];

        foreach ( $shops as $shop )
        {
            if ( $shop->status == 'active' )
            {
                $statusText = '<span class="badge bg-success p-1">ACTIVA</span>';
            } else {
                $statusText = '<span class="badge bg-danger p-1">INACTIVA</span>';
            }

            array_push($array, [
                "id" => $shop->id,
                "name" => $shop->name,
                "owner" => $shop->owner->name,
                "phone" => $shop->phone,
                "email" => $shop->email,
                "address" => $shop->address,
                "department" => $shop->department->name,
                "province" => $shop->province->name,
                "district" => $shop->district->name,
                "status" => $shop->status,
                "statusText" => $statusText
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
        $departments = Department::all();
        $users = User::where('is_admin', 1)->get();
        return view('shop.create', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'name' => 'required|string|unique:types,name',
                'phone' => 'required|string',
                'email' => 'required|string',
                'address' => 'required|string',
                'departamento' => 'required|string',
                'provincoa' => 'required|string',
                'distrito' => 'nullable|string',
            ]);

            // Procesar el estado de visibilidad
            $userAdmin = User::where('is_admin', 1)->first();

            $departamento = $request->input('departamento');
            $provincia = $request->input('provincia');
            $distrito = $request->input('distrito');

            $department = Department::where('name', 'LIKE', '%'.$departamento.'%')
                ->first();
            $province = Province::where('name', 'LIKE', '%'.$provincia.'%')
                ->where('department_id', $department->id)
                ->first();
            $district = District::where('name', 'LIKE', '%'.$distrito.'%')
                ->where('province_id', $province->id)
                ->first();

            if ( !isset($department) || !isset($province) || !isset($district)) {
                return response()->json(['message' => 'Error en la ubicación geográfica.'], 422);
            }

            $slug = "fuego-y-masa-".$department->name."-".$province->name."-".$district->name;
            // Crear la categoría
            $shop = new Shop();
            $shop->name = $request->input('name');
            $shop->slug = $slug;
            $shop->owner_id = ($request->input('owner') == null || $request->input('name') == "") ? $userAdmin : $request->input('name');
            $shop->phone = $request->input('phone');
            $shop->email = $request->input('email');
            $shop->address = $request->input('address');
            $shop->latitude = $request->input('latitude');
            $shop->longitude = $request->input('longitude');
            $shop->department_id = $department->id;
            $shop->province_id = $province->id;
            $shop->district_id = $district->id;
            $shop->type = $request->get('type') === 'on' ? 'principal' : 'sucursal';
            $shop->active = $request->get('active') === 'on' ? 'active' : 'inactive';
            $shop->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cambios guardados con éxito.'], 200);
    }

    public function show(Shop $shop)
    {
        return view('shop.show', compact('shop'));
    }

    public function edit(Shop $shop)
    {
        return view('shop.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|max:255|unique:shops,name,' . $shop->id,
                'phone' => 'required|string',
                'email' => 'required|string',
                'address' => 'required|string',
                'department' => 'required|string',
                'province' => 'required|string',
                'district' => 'required|string',
            ]);

            // Procesar el estado de visibilidad
            $isActive = $request->has('active') ? 1 : 0; // Si 'active' no está presente, será 0

            $department = Department::find($request->input('department_id'));
            $province = Province::find($request->input('province_id'));
            $district = District::find($request->input('district_id'));

            $slug = "fuego-y-masa-".$department->name."-".$province->name."-".$district->name;

            // Crear la categoría
            /*$shop = Shop::find($shop->id);*/
            $shop->name = $request->input('name');
            $shop->name = $request->input('name');
            $shop->slug = $slug;
            /*$shop->owner_id = $userAdmin;*/
            $shop->phone = $request->input('phone');
            $shop->email = $request->input('email');
            $shop->address = $request->input('address');
            $shop->latitude = $request->input('latitude');
            $shop->longitude = $request->input('longitude');
            $shop->department_id = $request->input('department_id');
            $shop->province_id = $request->input('province_id');
            $shop->district_id = $request->input('district_id');
            $shop->status = $isActive;
            $shop->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cambios guardados con éxito.'], 200);
    }

    public function desactivar(Shop $shop)
    {
        DB::beginTransaction();
        try {
            $shop->status = 'inactive';
            $shop->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Tienda inactivada con éxito.'], 200);
    }

    public function activar(Shop $shop)
    {
        DB::beginTransaction();
        try {
            $shop->status = 'active';
            $shop->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Tienda activada con éxito.'], 200);
    }
}
