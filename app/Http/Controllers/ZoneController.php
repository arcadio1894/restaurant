<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZoneController extends Controller
{
    public function index()
    {
        $shops = Shop::all();
        return view('zone.index', compact('shops'));
    }

    public function create()
    {
        $shops = Shop::all();
        return view('zone.create', compact('shops'));
    }

    public function edit(Zone $zone)
    {
        $shops = Shop::all();
        return view('zone.edit', compact('zone', 'shops'));
    }

    public function update(Request $request, Zone $zone)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'coordinates' => 'required|json',
        ]);

        $zone->update($request->all());

        return redirect()->route('zone.index')->with('success', 'Zona actualizada correctamente.');
    }

    public function destroy($id)
    {
        try {
            $zone = Zone::findOrFail($id);
            $zone->delete();

            return response()->json(['success' => true, 'message' => 'Zona eliminada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Obtener las zonas de una tienda
    public function getZones($shopId)
    {
        $zones = Zone::where('shop_id', $shopId)->get()->map(function ($zone) {
            return [
                'id' => $zone->id,
                'name' => $zone->name,
                'status' => $zone->status,
                'price' => $zone->price,
                'coordinates' => $this->convertPolygonToArray($zone->coordinates), // Convertir POLYGON a array
            ];
        });
        /*$zones = Zone::where('shop_id', $shopId)->get()->map(function ($zone) {
            return [
                'id' => $zone->id,
                'name' => $zone->name,
                'status' => $zone->status,
                'price' => $zone->price,
                'coordinates' => $this->convertPolygonToArray(DB::selectOne("SELECT ST_AsText('$zone->coordinates') AS wkt")->wkt),
            ];
        });*/

        return response()->json($zones);
    }

    /**
     * Convierte un objeto GEOMETRY (POLYGON) a un array de coordenadas.
     */
    private function convertPolygonToArray($polygon)
    {
        dd($polygon);
        $coordinates = [];

        if ($polygon) {
            $wkt = DB::selectOne("SELECT ST_AsText(?) AS wkt", [$polygon])->wkt;
            preg_match('/\(\((.*?)\)\)/', $wkt, $matches);

            if (!empty($matches[1])) {
                $points = explode(',', $matches[1]);
                foreach ($points as $point) {
                    list($lng, $lat) = explode(' ', trim($point));
                    $coordinates[] = [floatval($lng), floatval($lat)];
                }
            }
        }

        return $coordinates;
    }

    // Guardar nuevas zonas
    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'zones' => 'required|array',
            'zones.*.coordinates' => 'required|array|min:3',
        ]);

        try {
            DB::beginTransaction(); // Aseguramos que la transacción inicia

            $shopId = $request->shop_id;
            $shop = Shop::findOrFail($shopId);
            $baseName = $shop->name; // Nombre base de la tienda

            // 🔍 Obtener las zonas existentes de la tienda y extraer números
            $existingZones = Zone::where('shop_id', $shop->id)
                ->where('name', 'LIKE', "$baseName%")
                ->pluck('name')
                ->map(function ($name) use ($baseName) {
                    return (int) str_replace($baseName . ' ', '', $name);
                })
                ->filter()
                ->sort()
                ->values();

            // 🔄 Lista de IDs de zonas que se mantendrán
            $zonesToKeep = [];
            $usedNumbers = $existingZones->toArray(); // Guardamos los números usados

            foreach ($request->zones as $zoneData) {
                $coordinates = $zoneData['coordinates'];

                // 🔄 Convertir coordenadas a formato WKT POLYGON
                $wktPolygon = "POLYGON((";
                foreach ($coordinates as $point) {
                    $wktPolygon .= number_format($point[0], 6, '.', '') . " " . number_format($point[1], 6, '.', '') . ",";
                }
                $wktPolygon = rtrim($wktPolygon, ',') . "))";

                // 🚀 Buscar si existe una zona con las mismas coordenadas
                $matchedZone = Zone::where('shop_id', $shopId)
                    ->whereRaw("ST_AsText(coordinates) = ?", [$wktPolygon])
                    ->first();

                if ($matchedZone) {
                    // ✅ La zona ya existe con las mismas coordenadas, la mantenemos
                    $zonesToKeep[] = $matchedZone->id;
                    continue;
                }

                // 🔢 Generar número correlativo único
                $newNumber = 1;
                while (in_array($newNumber, $usedNumbers)) {
                    $newNumber++;
                }
                $usedNumbers[] = $newNumber; // Marcar número como usado
                $zoneName = $baseName . ' ' . $newNumber;

                // 🆕 Crear nueva zona con nombre único
                $newZone = Zone::create([
                    'shop_id' => $shopId,
                    'name' => $zoneName,
                    'coordinates' => DB::raw("ST_PolygonFromText('$wktPolygon')"),
                ]);
                $zonesToKeep[] = $newZone->id;
            }

            // ❌ Eliminar zonas que ya no están en la lista enviada
            Zone::where('shop_id', $shopId)
                ->whereNotIn('id', $zonesToKeep)
                ->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Zonas actualizadas correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }

    }

    public function changeStatus($id)
    {
        $zone = Zone::findOrFail($id);
        $zone->status = ($zone->status == 'active') ? 'inactive' : 'active'; // Cambiar de activo a inactivo o viceversa
        $zone->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente.',
            'coordinates' => $this->convertPolygonToArray($zone->coordinates),
            'status' => $zone->status,
        ]);
    }

    public function deleteZone($id)
    {
        $zone = Zone::findOrFail($id);
        $zone->delete();

        return response()->json(['success' => true, 'message' => 'Zona eliminada correctamente.']);
    }

    public function updatePrice(Request $request, Zone $zone)
    {
        $request->validate([
            'price' => 'required|numeric|min:0'
        ]);

        $zone->update(['price' => $request->price]);

        return response()->json(['success' => true]);
    }

    public function show(Zone $zone)
    {
        return response()->json([
            'id' => $zone->id,
            'name' => $zone->name,
            'price' => $zone->price,
            'coordinates' => $this->convertPolygonToArray($zone->coordinates), // Convierte el POLYGON en array
            'shop_latitude' => $zone->shop->latitude, // Latitud de la tienda
            'shop_longitude' => $zone->shop->longitude // Longitud de la tienda
        ]);
    }
}
