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
        $zones = Zone::with('shop')->get();
        return view('zone.index', compact('zones'));
    }

    public function create()
    {
        $shops = Shop::all();
        return view('zone.create', compact('shops'));
    }

    /*public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'coordinates' => 'required|json',
        ]);

        Zone::create($request->all());

        return redirect()->route('zones.index')->with('success', 'Zona creada correctamente.');
    }*/

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
                'coordinates' => $this->convertPolygonToArray($zone->coordinates), // Convertir POLYGON a array
            ];
        });

        return response()->json($zones);
    }

    /**
     * Convierte un objeto GEOMETRY (POLYGON) a un array de coordenadas.
     */
    private function convertPolygonToArray($polygon)
    {
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

        DB::beginTransaction();
        try {
            $shopId = $request->shop_id;

            // 🔍 Obtener las zonas actuales de la tienda con sus coordenadas
            $existingZones = Zone::where('shop_id', $shopId)->get()->keyBy('id');

            // 🔄 Lista de IDs de zonas que se mantendrán
            $zonesToKeep = [];

            foreach ($request->zones as $zoneData) {
                $coordinates = $zoneData['coordinates'];

                // 🔄 Convertir coordenadas a formato WKT POLYGON
                $wktPolygon = "POLYGON((";
                foreach ($coordinates as $point) {
                    $wktPolygon .= number_format($point[0], 6, '.', '') . " " . number_format($point[1], 6, '.', '') . ",";
                }
                $wktPolygon = rtrim($wktPolygon, ',') . "))";

                // 🚀 Buscar si existe una zona con las mismas coordenadas
                $matchedZone = null;
                foreach ($existingZones as $zone) {
                    $dbCoordinates = DB::selectOne("SELECT ST_AsText(coordinates) as coords FROM zones WHERE id = ?", [$zone->id]);

                    if ($dbCoordinates && trim($dbCoordinates->coords) === trim($wktPolygon)) {
                        $matchedZone = $zone;
                        break;
                    }
                }

                if ($matchedZone) {
                    // ✅ La zona ya existe con las mismas coordenadas, la mantenemos
                    $zonesToKeep[] = $matchedZone->id;
                } else {
                    // 🚀 Verificar si la zona existe con coordenadas diferentes (actualización)
                    $updated = false;
                    foreach ($existingZones as $zone) {
                        if (!in_array($zone->id, $zonesToKeep)) {
                            // 🔄 Si encontramos una zona sin usar, la actualizamos con las nuevas coordenadas
                            $zone->update([
                                'coordinates' => DB::raw("ST_PolygonFromText('$wktPolygon')")
                            ]);
                            $zonesToKeep[] = $zone->id;
                            $updated = true;
                            break;
                        }
                    }

                    // 🆕 Si no se pudo actualizar, creamos una nueva zona
                    if (!$updated) {
                        $newZone = Zone::create([
                            'shop_id' => $shopId,
                            'name' => 'Fuego y Masa Trujillo ' . $shopId,
                            'coordinates' => DB::raw("ST_PolygonFromText('$wktPolygon')"),
                        ]);
                        $zonesToKeep[] = $newZone->id;
                    }
                }
            }

            // ❌ Eliminar solo las zonas que ya no están en la lista enviada
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

    public function toggleStatus(Zone $zone)
    {
        $zone->status = $zone->status === 'active' ? 'inactive' : 'active';
        $zone->save();

        return response()->json(['success' => true, 'message' => 'Estado cambiado']);
    }


}
