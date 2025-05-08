<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\MilestoneReward;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class MilestoneController extends Controller
{
    public function index()
    {
        return view('milestone.index');
    }

    public function getDataRewards(Request $request, $pageNumber = 1)
    {
        $perPage = 10;

        $query = Milestone::orderBy('flames');

        // Aplicar filtros si se proporcionan

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $milestones = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $array = [];

        foreach ( $milestones as $milestone )
        {
            array_push($array, [
                "id" => $milestone->id,
                "title" => $milestone->title,
                "description" => $milestone->description,
                "flames" => $milestone->flames,
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
        $products = Product::where('enable_status', 1)->get();
        return view('milestone.create', compact('products'));
    }

    public function store(Request $request)
    {
        // ✅ Validación de datos
        $validator = Validator::make($request->all(), [
            'flames' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'products' => 'required|json'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Hay errores en los datos ingresados.',
                'errors' => $validator->errors()
            ], 422);
        }

        // ✅ Iniciar transacción para asegurar la integridad de datos
        DB::beginTransaction();

        try {
            // ✅ Procesar y guardar imagen con Intervention Image
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('/images/reward/' . $imageName);

            // Guardar sin modificar tamaño
            Image::make($image)->save($imagePath, 90); // 90% de calidad para optimización

            // ✅ Crear el Milestone
            $milestone = Milestone::create([
                'flames' => $request->flames,
                'title' => $request->title,
                'description' => $request->description,
                'image' => $imageName
            ]);

            // ✅ Decodificar los productos (vienen en JSON)
            $products = json_decode($request->products, true);

            // ✅ Guardar los productos en MilestoneReward
            foreach ($products as $productId) {
                MilestoneReward::create([
                    'milestone_id' => $milestone->id,
                    'product_id' => $productId
                ]);
            }

            // ✅ Confirmar la transacción
            DB::commit();

            // ✅ Respuesta exitosa
            return response()->json([
                'message' => 'Hito y productos guardados correctamente.'
            ], 200);

        } catch (\Exception $e) {
            // ❌ Error: revertir cambios
            DB::rollBack();
            return response()->json([
                'message' => 'Error al guardar el hito y los productos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        // ✅ Iniciar transacción para asegurar la integridad
        DB::beginTransaction();
        try {
            // ✅ Buscar el Milestone
            $milestone = Milestone::findOrFail($id);

            // ✅ Eliminar la imagen del servidor
            $imagePath = public_path('/images/reward/' . $milestone->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            // ✅ Eliminar los productos asociados en MilestoneReward
            MilestoneReward::where('milestone_id', $milestone->id)->delete();

            // ✅ Eliminar el Milestone
            $milestone->delete();

            // ✅ Confirmar la transacción
            DB::commit();

            // ✅ Respuesta exitosa
            return response()->json([
                'message' => 'Hito eliminado correctamente.'
            ], 200);

        } catch (\Exception $e) {
            // ❌ Error: revertir cambios
            DB::rollBack();
            return response()->json([
                'message' => 'Error al eliminar el hito.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $milestone = Milestone::with('rewards.product')
            ->findOrFail($id);

        $products = Product::where('enable_status', 1)->get();
        return view('milestone.edit', compact('products', 'milestone'));
    }

    public function update(Request $request)
    {
        // ✅ Validación de datos
        $request->validate([
            'milestone_id' => 'required|exists:milestones,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'flames' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'products' => 'required|json'
        ]);

        // ✅ Iniciar transacción
        DB::beginTransaction();

        try {
            // 🔎 Obtener el Milestone
            $milestone = Milestone::findOrFail($request->milestone_id);

            // ✅ Actualización de los datos
            $milestone->title = $request->title;
            $milestone->description = $request->description;
            $milestone->flames = $request->flames;

            // ✅ Manejo de la imagen con Intervention Image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = public_path('/images/reward/' . $imageName);

                // Eliminar la imagen anterior
                $oldImagePath = $imagePath . $milestone->image;
                if (File::exists($oldImagePath) && $milestone->image) {
                    File::delete($oldImagePath);
                }

                // Crear instancia de Intervention Image
                $img = Image::make($image);

                // Guardar en el directorio (sin resize, como me indicaste antes)
                $img->save($imagePath, 90);

                // Guardar el nuevo nombre en la DB
                $milestone->image = $imageName;
            }

            $milestone->save();

            // ✅ Manejo de los productos seleccionados
            $newProductIds = json_decode($request->products);

            // 1️⃣ Obtener los IDs de los productos actualmente en la DB
            $existingProductIds = MilestoneReward::where('milestone_id', $milestone->id)
                ->pluck('product_id')
                ->toArray();

            // 2️⃣ Identificar los productos a agregar y a eliminar
            $toAdd = array_diff($newProductIds, $existingProductIds);
            $toRemove = array_diff($existingProductIds, $newProductIds);

            // 3️⃣ Eliminar productos que ya no están seleccionados
            if (!empty($toRemove)) {
                MilestoneReward::where('milestone_id', $milestone->id)
                    ->whereIn('product_id', $toRemove)
                    ->delete();
            }

            // 4️⃣ Agregar los nuevos productos seleccionados
            foreach ($toAdd as $productId) {
                MilestoneReward::create([
                    'milestone_id' => $milestone->id,
                    'product_id' => $productId,
                ]);
            }

            // ✅ Confirmar la transacción
            DB::commit();

            // ✅ Respuesta exitosa
            return response()->json([
                'message' => 'Hito actualizado correctamente.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al actualizar el hito.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
