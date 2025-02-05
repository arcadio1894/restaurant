<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SunatService;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use App\Services\XmlGenerator;
use App\Services\SignatureService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;

class FacturaController extends Controller
{
    protected $sunatService;

    public function __construct(SunatService $sunatService)
    {
        $this->sunatService = $sunatService;
    }

    /**
     * Generar y enviar una factura o boleta a SUNAT.
     */
    public function generarComprobante($orderId)
    {
        // Obtener la orden de venta
        $order = Order::findOrFail($orderId);

        // Validar si ya se generó un comprobante
        if ($order->sunat_ticket) {
            return response()->json(['error' => 'El comprobante ya fue generado'], 400);
        }

        // 🔄 Generar el XML de la factura
        $xmlGenerator = new XmlGenerator();
        $invoice = $xmlGenerator->generarFactura($order);

        // 📁 Guardar el XML en el sistema
        $xmlPath = "facturacion/xml/{$order->serie}-{$order->numero}.xml";
        Storage::put($xmlPath, $this->sunatService->getXmlSigned($invoice));

        // 🔐 Firmar el XML
        $signatureService = new SignatureService();
        $signatureService->firmarXml($xmlPath);

        // Enviar la orden a SUNAT
        $response = $this->sunatService->enviarComprobante($order);

        if ($response['success']) {
            $order->sunat_ticket = $response['ticket'];
            $order->save();

            return response()->json(['message' => 'Comprobante enviado con éxito', 'ticket' => $response['ticket']]);
        } else {
            return response()->json(['error' => 'Error al enviar a SUNAT', 'message' => $response['message']], 500);
        }
    }

    public function descargarComprobante($orderId)
    {
        $order = Order::findOrFail($orderId);
        $xmlPath = "facturacion/xml/{$order->serie}-{$order->numero}.xml";

        if (!Storage::exists($xmlPath)) {
            return response()->json(['error' => 'El comprobante no existe'], 404);
        }

        // Leer el XML
        $xmlContent = Storage::get($xmlPath);
        $xml = simplexml_load_string($xmlContent);

        // Extraer los datos del comprobante (personaliza según tu XML)
        $data = [
            'ruc' => (string) $xml->Company->Ruc,
            'razon_social' => (string) $xml->Company->RazonSocial,
            'nombre_comercial' => (string) $xml->Company->NombreComercial,
            'serie' => $order->serie,
            'numero' => $order->numero,
            'fecha_emision' => (string) $xml->FechaEmision,
            'items' => [],
            'total' => (float) $xml->Total
        ];

        foreach ($xml->Items->Item as $item) {
            $data['items'][] = [
                'cantidad' => (float) $item->Cantidad,
                'descripcion' => (string) $item->Descripcion,
                'valor_unitario' => (float) $item->ValorUnitario,
                'valor_total' => (float) $item->ValorTotal
            ];
        }

        // Generar el PDF
        $pdfOptions = new Options();
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($pdfOptions);

        $html = View::make('order.comprobante', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Descargar el PDF
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=factura-{$order->serie}-{$order->numero}.pdf");
    }
}
