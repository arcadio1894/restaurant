<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\ImagickEscposImage; // Para manejar imágenes

class PrintController extends Controller
{
    public function printByMilly( Request $request )
    {

    }

    public function show($id)
    {
        // Buscar la boleta por ID
        $boleta = Boleta::find($id);

        if (!$boleta) {
            // Si no existe, retornar un mensaje de error
            return response()->json(['error' => 'Boleta no encontrada'], 404);
        }

        // Retornar la boleta en formato JSON
        return response()->json($boleta);
    }

    public function imprimir($id)
    {
        $boleta = Boleta::find($id);

        if (!$boleta) {
            return response()->json(['error' => 'Boleta no encontrada'], 404);
        }

        try {
            // Conectar a la impresora
            $connector = new WindowsPrintConnector("EPSON_TM-T20III");
            $printer = new Printer($connector);

            // Cargar e imprimir el logotipo
            $logoPath = public_path('images/logo.png'); // Ruta del logotipo
            if (file_exists($logoPath)) {
                $logo = ImagickEscposImage::load($logoPath);
                $printer->graphics($logo); // Imprime la imagen
            }

            // Encabezado de la boleta
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("RESTAURANTE PIZZA HOUSE\n");
            $printer->text("RUC: 12345678901\n");
            $printer->text("Av. Principal 123, Lima\n");
            $printer->text("--------------------------------\n");
            $printer->text("BOLETA DE VENTA\n");
            $printer->text("Nro: {$boleta->numero_boleta}\n");
            $printer->text("Cliente: {$boleta->cliente}\n");
            $printer->text("--------------------------------\n");

            // Detalle de productos
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            foreach ($boleta->productos as $producto) {
                $nombre = str_pad($producto['nombre'], 20);
                $precio = str_pad('S/. ' . number_format($producto['precio'] * $producto['cantidad'], 2), 10, ' ', STR_PAD_LEFT);
                $printer->text("{$nombre}{$precio}\n");
                $printer->text("  {$producto['cantidad']} x S/. {$producto['precio']}\n");
                if ($producto['descuento'] > 0) {
                    $printer->text("  Descuento: S/. {$producto['descuento']}\n");
                }
            }

            $printer->text("--------------------------------\n");

            // Totales
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("Subtotal: S/. {$boleta->subtotal}\n");
            $printer->text("Descuento: S/. {$boleta->descuento}\n");
            $printer->text("IGV: S/. {$boleta->igv}\n");
            $printer->text("TOTAL: S/. {$boleta->total}\n");
            $printer->text("--------------------------------\n");

            // Pie de página
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Gracias por su compra\n");
            $printer->text("www.pizzahouse.com\n");

            // Finalizar impresión
            $printer->cut();
            $printer->close();

            return response()->json(['success' => 'Boleta impresa correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al imprimir: ' . $e->getMessage()], 500);
        }
    }
}
