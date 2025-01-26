<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        return view('slider.index', compact('sliders'));
    }

    public function create()
    {
        $maxOrder = Slider::max('order');
        return view('slider.create', compact('maxOrder'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|integer|min:1',
            'size' => 'required|string|in:on,off',
            'link' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        //dd($request);
        DB::beginTransaction();
        try {
            $slider = Slider::create([
                'order' => $validated['order'],
                'size' => $request->get('size') === 'on' ? 's' : 'l',
                'link' => $validated['link'],
            ]);

            // TODO: Tratamiento de un archivo de forma tradicional
            $slider->image = $this->handleImageUpload($request, $slider);
            $slider->save();

            DB::commit();

            return response()->json(['message' => 'Cambios guardados con éxito.'], 200);

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }


    }

    private function handleImageUpload(Request $request, $slider)
    {
        if ($request->hasFile('image')) {
            // Ruta de almacenamiento
            $path = public_path('images/slider/');
            if (!file_exists($path)) {
                mkdir($path, 0755, true); // Crear el directorio si no existe
            }

            // Obtener la extensión original
            $extension = $request->file('image')->getClientOriginalExtension();

            // Determinar el sufijo según el tamaño
            $sizeSuffix = $request->get('size') === 'on' ? '_s' : '_l';

            // Generar el nombre del archivo
            $filename = 'slide' . $slider->id . $sizeSuffix . '.' . $extension;

            // Procesar la imagen con Intervention (sin cambios)
            $image = Image::make($request->file('image')->getRealPath());

            // Guardar la imagen en la ruta especificada
            $image->save($path . $filename);

            return $filename;
        }

        return 'no_image.png';
    }

    public function edit(Slider $slider)
    {
        return view('slider.edit', compact('slider'));
    }

    public function update(Request $request)
    {
        //dd($request);
        $validated = $request->validate([
            'image_id' => 'required|exists:sliders,id',
            'order' => 'required|integer|min:1',
            'size' => 'required|string|in:on,off',
            'link' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Validación para imágenes
        ]);

        DB::beginTransaction();
        try {

            $slider = Slider::findOrFail($request->get('image_id'));

            // Actualizar los valores básicos
            $slider->order = $request->get('order');
            $slider->size = $request->get('size') === 'on' ? 's' : 'l';
            $slider->link = $request->get('link');

            // Manejo de la imagen
            if ($request->hasFile('image')) {
                $slider->image = $this->handleImageUpdate($request, $slider);
            }

            $slider->save();

            DB::commit();

            return response()->json(['message' => 'Cambios guardados con éxito.'], 200);

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

    }

    private function handleImageUpdate(Request $request, $slider)
    {
        $path = public_path('images/slider/');
        if (!file_exists($path)) {
            mkdir($path, 0755, true); // Crear el directorio si no existe
        }

        $sizeSuffix = $request->get('size') === 'on' ? '_s' : '_l';

        // Eliminar la imagen anterior si existe y no es la imagen predeterminada
        if ($slider->image && $slider->image !== 'no_image.png') {
            $existingImagePath = $path . $slider->image;
            if (file_exists($existingImagePath)) {
                unlink($existingImagePath);
            }
        }

        // Procesar y guardar la nueva imagen
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = 'slide' . $slider->id . $sizeSuffix . '.' . $extension;

            // Procesar la imagen con Intervention (sin cambios)
            $image = Image::make($request->file('image')->getRealPath());

            // Guardar la imagen en la ruta especificada
            $image->save($path . $filename);

            return $filename;
        }

        return 'no_image.png';
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $slider = Slider::find($request->get('image_id'));

            $image_path = public_path().'/images/slider/'.$slider->image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            $slider->delete();

            DB::commit();
            return response()->json(['message' => 'Imagen eliminada con éxito.'], 200);
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

    }

    public function getSliders()
    {
        $sliders = Slider::select('id', 'image', 'order', 'size', 'link')->get();
        return datatables($sliders)->toJson();

    }
}
