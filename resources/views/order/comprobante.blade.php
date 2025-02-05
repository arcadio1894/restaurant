<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $serie }}-{{ $numero }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .content { width: 100%; margin: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
<div class="header">
    <h2>{{ $razon_social }}</h2>
    <p>RUC: {{ $ruc }}</p>
    <p>{{ $nombre_comercial }}</p>
</div>

<div class="content">
    <h3>Factura {{ $serie }}-{{ $numero }}</h3>
    <p>Fecha de Emisión: {{ $fecha_emision }}</p>
    <table>
        <thead>
        <tr>
            <th>Cantidad</th>
            <th>Descripción</th>
            <th>Valor Unitario</th>
            <th>Valor Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <tr>
                <td>{{ $item['cantidad'] }}</td>
                <td>{{ $item['descripcion'] }}</td>
                <td>{{ number_format($item['valor_unitario'], 2) }}</td>
                <td>{{ number_format($item['valor_total'], 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <p><strong>Total: S/ {{ number_format($total, 2) }}</strong></p>
</div>
</body>
</html>