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
        <p>COMANDA - {{ $order->id }}</p>
        {{--<p>Nro: ORDEN - {{ $order->id }}</p>--}}
        <div class="line"></div>
    </div>
    <p><b>Pedido:</b> {{ $order->formatted_created_date }}</p>
    <p><b>Entrega:</b> {{ $order->formatted_date }}</p>
    <p><b>Cliente:</b> {{ $order->user->name }}</p>
    <p><b>Telefono:</b> {{ ($order->shipping_address_id == null) ? 'N/A':$order->shipping_address->phone }}</p>
    <div class="line"></div>
    @foreach ($order->details as $detail)
        <strong><p style="font-size: 18px">{{ ($detail->product->full_name) }} <span style="float: right;">{{ $detail->quantity }}</span></p></strong>
        @foreach( $detail->options as $option )
            <p style="font-size: 16px">- {{ str_pad( ($option->product->full_name), 10, ' ', STR_PAD_LEFT) }} x {{ $detail->quantity }}</p>
        @endforeach
    @endforeach
    <div class="line"></div>
    <div style="border: 0.5px solid black; padding: 3px; margin-top: 10px;">
        <strong>Observaciones:</strong>
        {{ $order->observations }}
    </div>
    <div class="text-center" >
        <p>¡Gracias por su compra!</p>
        <p>www.fuegoymasa.com</p>
    </div>
{{--style="border-style: solid" esto va en comanda para observaciones--}}
</body>
</html>