<?php

namespace App\Services;

use Greenter\See;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Company\Company;
use Greenter\Model\Summary\Summary;
use Greenter\Ws\Services\BillService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Greenter\Xml\XmlSigner;
use Greenter\Ws\Services\SunatEndpoints;

class SunatService
{
    protected $see;

    public function __construct()
    {
        // Configuración de SUNAT
        $this->see = new See();
        $this->see->setCertificate(file_get_contents(config('sunat.certificado')));  // Ruta del certificado
        $this->see->setClaveSOL(
            config('sunat.ruc'),
            config('sunat.usuario_sol'),
            config('sunat.clave_sol')
        ); // Credenciales SOL
        $this->see->setService(config('sunat.env') === 'production'
            ? SunatEndpoints::FE_PRODUCCION
            : SunatEndpoints::FE_BETA
        ); // Selección del entorno SUNAT
    }

    public function enviarComprobante($order)
    {
        try {
            if ($order->type_document == '01') {
                // 🟢 FACTURA
                $invoice = new Invoice();
            } elseif ($order->type_document == '03') {
                // 🔴 BOLETA (Se debe agrupar en un resumen)
                return $this->enviarResumenBoletas($order);
            } else {
                return ['success' => false, 'message' => 'Tipo de documento no válido.'];
            }

            // 🏢 DATOS DE LA EMPRESA
            $empresa = new Company();
            $empresa->setRuc(config('sunat.ruc'));
            $empresa->setRazonSocial(config('sunat.razon_social'));
            $empresa->setNombreComercial(config('sunat.nombre_comercial'));

            // 📄 CONFIGURACIÓN DEL DOCUMENTO
            $invoice->setSerie($order->serie);
            $invoice->setCorrelativo($order->numero);
            $invoice->setTipoDocumento($order->type_document);
            $invoice->setCompany($empresa);

            // 🔐 Generar XML firmado
            $xml = $this->see->getXmlSigned($invoice);
            $xmlPath = "facturacion/xml/{$order->serie}-{$order->numero}.xml";
            Storage::put($xmlPath, $xml);

            // 🔄 REINTENTOS DE ENVÍO A SUNAT
            $maxReintentos = 3;
            for ($i = 0; $i < $maxReintentos; $i++) {
                $result = $this->see->send($invoice);
                if ($result->isSuccess()) {
                    // 📥 Guardar CDR (Respuesta de SUNAT)
                    $cdrPath = "facturacion/cdrs/{$order->serie}-{$order->numero}.zip";
                    Storage::put($cdrPath, $result->getCdrZip());

                    return ['success' => true, 'ticket' => $result->getTicket()];
                }
                sleep(2); // ⏳ Esperar antes de reintentar
            }

            return ['success' => false, 'message' => 'No se pudo enviar a SUNAT después de varios intentos.'];

        } catch (\Exception $e) {
            Log::error("Error en facturación: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error en el proceso de facturación.'];
        }
    }

    /**
     * 🚀 ENVÍA UN RESUMEN DE BOLETAS A SUNAT
     */
    public function enviarResumenBoletas($order)
    {
        try {
            $summary = new Summary();
            $summary->setCompany(new Company(['ruc' => config('sunat.ruc')]));
            $summary->setFechaReferente(new \DateTime());
            $summary->setSerie('R001');
            $summary->setCorrelativo(rand(1, 9999));

            // 🔐 Firmar XML
            $xml = $this->see->getXmlSigned($summary);
            Storage::put("facturacion/xml/resumen-{$summary->getSerie()}-{$summary->getCorrelativo()}.xml", $xml);

            // 🔄 Intentar enviar a SUNAT
            $maxReintentos = 3;
            for ($i = 0; $i < $maxReintentos; $i++) {
                $result = $this->see->sendSummary($summary);
                if ($result->isSuccess()) {
                    Storage::put("facturacion/cdrs/resumen-{$summary->getSerie()}-{$summary->getCorrelativo()}.zip", $result->getCdrZip());
                    return ['success' => true, 'ticket' => $result->getTicket()];
                }
                sleep(2);
            }

            return ['success' => false, 'message' => 'No se pudo enviar el resumen a SUNAT después de varios intentos.'];

        } catch (\Exception $e) {
            Log::error("Error en resumen de boletas: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error en el proceso de resumen de boletas.'];
        }
    }
}
