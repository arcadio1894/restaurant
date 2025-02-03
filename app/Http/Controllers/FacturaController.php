<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SunatService;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use App\Services\XmlGenerator;
use App\Services\SignatureService;

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
}
