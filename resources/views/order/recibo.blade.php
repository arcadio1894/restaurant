<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            /*width: 226.8pt; !* 80mm *!*/
        }
        .text-center {
            text-align: center;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
            width: 100%;
            display: block;
        }
        .bold {
            font-weight: bold;
        }
        * {
            page-break-inside: avoid;
            page-break-before: auto;
            page-break-after: auto;
        }
        p {
            margin: 0;
            padding: 2px 0;
        }
        img {
            max-width: 100px; /* Ajusta el tamaño del logo según sea necesario */
            height: auto;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="text-center bold">
        <img src="{{ public_path('images/logo/logoPequeño.png') }}" alt="Logo de Restaurante">
        <p>RESTAURANTE FUEGO Y MASA</p>
        <p>RUC: 20613407287</p>
        <p>Manuel Candamo 810, Lima</p>
        <div class="line"></div>
        <p>NOTA DE VENTA - {{ $order->id }}</p>
        {{--<p>Nro: ORDEN - {{ $order->id }}</p>--}}
        <div class="line"></div>
    </div>
    <p><b>Fecha Pedido:</b> {{ $order->created_at->format('d/m/Y') }}</p>
    {{--<p><b>Entrega:</b> {{ $order->formatted_date }}</p>--}}
    <p><b>Cliente:</b> {{ $order->user->name }}</p>
    <p><b>Telefono:</b> {{ ($order->shipping_address_id == null) ? 'N/A':$order->shipping_address->phone }}</p>
    <div class="line"></div>
    @foreach ($order->details as $detail)

        <p><b>{{ str_pad( ($detail->product->full_name), 10, ' ', STR_PAD_LEFT)  }} x {{ $detail->quantity }}</b>  <span style="float: right;">{{ 'S/. ' . number_format($detail->price * $detail->quantity, 2) }}</span></p>
        @foreach( $detail->options as $option )
            <p>- {{ str_pad( ($option->product->full_name), 10, ' ', STR_PAD_LEFT) }}</p>
        @endforeach
    @endforeach
    <div class="line"></div>
    <p><b>Sub Total:</b> <span style="float: right;">S/. {{ $amount_subtotal }}</span></p>
    <p><b>Descuento:</b> <span style="float: right;">S/. {{ $discount }}</span></p>
    <p><b>IGV:</b> <span style="float: right;">S/. {{ $amount_igv }}</span></p>
    <p><b>TOTAL:</b> <span style="float: right;">S/. {{ $order->amount_pay }}</span></p>
    <div class="text-center" >
        <p>¡Gracias por su compra!</p>
        <p>www.fuegoymasa.com</p>
    </div>
{{--style="border-style: solid" esto va en comanda para observaciones--}}
</body>
</html>