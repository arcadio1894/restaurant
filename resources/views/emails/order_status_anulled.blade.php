<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de estado de tu orden</title>
</head>
<body>
<h1 style="font-size: 24px; text-align: center; font-family: Arial, sans-serif;">¡Hola! {{ $name }}</h1>
<p style="font-size: 16px; text-align: center; font-family: Arial, sans-serif;">
    Queremos informarte que el estado de tu orden ha sido actualizado.
</p>

<div style="max-width: 600px; margin: auto; font-family: Arial, sans-serif;">
    <div style="border: 1px solid #ddd; border-radius: 5px; overflow: hidden/*; text-align: center;*/">
        <div style="background-color: #f8f9fa; padding: 15px; font-size: 18px; font-weight: bold;text-align: center;">
            RECHAZADO
        </div>
        <div style="padding: 15px;">
            <p style="font-size: 16px; margin: 0;text-align: center;">PEDIDO ID: #{{ $orderId }}</p>
            <div style="border: 1px solid #ddd; margin: 15px 0; padding: 10px; border-radius: 5px; /*text-align: center;*/">
                <p style="margin: 5px 0;">
                    <strong>Tu pedido llegará aproximadamente:</strong> {{ $orderDateDelivery }}
                </p>
                <p style="margin: 5px 0;">
                    <strong>Método de pago:</strong> {{ $method }}
                </p>
                <p style="margin: 5px 0;">
                    <strong>Monto a pagar:</strong> S/. {{ $total }}
                </p>
                <p style="margin: 5px 0;">
                    <strong>Celular:</strong> {{ $phone }}
                </p>
                <p style="margin: 5px 0;">
                    <strong>Dirección:</strong> {{ $address }}
                </p>
                <p style="margin: 5px 0;">
                    <strong>Referencia:</strong> {{ $reference }}
                </p>

            </div>
        </div>
    </div>
</div>

<p style="font-size: 16px; text-align: center; font-family: Arial, sans-serif;">
    Gracias por confiar en nosotros. Si tienes dudas sobre tu pedido escríbenos al Whatsapp de la empresa 906 343 258.
</p>
</body>
</html>