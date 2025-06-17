<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de estado de tu reclamo</title>
</head>
<body>
<h1 style="font-size: 24px; text-align: center; font-family: Arial, sans-serif;">Estimado cliente, {{ $reclamacion->nombre }} {{ $reclamacion->apellido }}</h1>
<p style="font-size: 16px; text-align: center; font-family: Arial, sans-serif;">
Su reclamo ha cambiado de estado.
</p>

<div style="max-width: 600px; margin: auto; font-family: Arial, sans-serif;">
    <div style="border: 1px solid #ddd; border-radius: 5px; overflow: hidden/*; text-align: center;*/">
        <div style="background-color: #f8f9fa; padding: 15px; font-size: 18px; font-weight: bold;text-align: center;">
            {{ $reclamacion->status_name }}
        </div>
        <div style="padding: 15px;">
            <p style="font-size: 16px; margin: 0;text-align: center;">RECLAMO ID: #{{ $reclamacion->codigo }}</p>
            <p>{!! nl2br(e($reclamacion->respuesta)) !!}</p>
        </div>
    </div>
</div>
<p style="font-size: 16px; text-align: center; font-family: Arial, sans-serif;">Puedes revisar el estado de tu reclamo utilizando el código en nuestro sistema.
    <a href="{{ route('estado-reclamos') }}">Ir a la pagina web</a>
</p>
<p style="font-size: 16px; text-align: center; font-family: Arial, sans-serif;">
    Gracias por confiar en nosotros. Si tienes dudas sobre tu pedido escríbenos al Whatsapp de la empresa 906 343 258.
</p>
<p>Atentamente,<br>El equipo de Fuego y Masa</p>
</body>
</html>