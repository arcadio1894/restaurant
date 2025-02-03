<?php

namespace App\Services;

use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Company\Company;

class XmlGenerator
{
    public function generarFactura($order)
    {
        // 🏢 Configuración de la empresa
        $empresa = new Company();
        $empresa->setRuc(config('sunat.ruc'));
        $empresa->setRazonSocial(config('sunat.razon_social'));
        $empresa->setNombreComercial(config('sunat.nombre_comercial'));

        // 📄 Configuración de la factura
        $invoice = new Invoice();
        $invoice->setTipoDocumento('01');  // Factura
        $invoice->setSerie($order->serie);
        $invoice->setCorrelativo($order->numero);
        $invoice->setFechaEmision(new \DateTime());
        $invoice->setCompany($empresa);
        $invoice->setTipoMoneda('PEN');  // Soles

        // 🛒 Agregar los detalles de la orden
        foreach ($order->items as $item) {
            $detalle = new SaleDetail();
            $detalle->setCantidad($item->quantity);
            $detalle->setUnidad('NIU');  // Unidad de medida
            $detalle->setDescripcion($item->description);
            $detalle->setValorVenta($item->total);

            $invoice->addDetail($detalle);
        }

        // Retornar el objeto factura
        return $invoice;
    }
}