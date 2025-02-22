import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'dac24d98f58cf734beec',
    cluster: 'us2',
    forceTLS: false
});

window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('Conexión establecida con Pusher.');
});

// Escuchar el evento
window.Echo.channel('orders')
    .subscribed(() => {
        console.log('Suscripción exitosa al canal "orders".');
    })
    .listen('.OrderStatusUpdated', (e) => {
        console.log('Evento recibido:', e);
        console.log('Datos de la orden:', e.order);
        console.log('status_name de la orden:', e.status_name);
        console.log('status_name de la orden:', e.status_name);

        let order = e.order; // El objeto recibido del servidor
        let status_name = e.status_name;
        let active_step = e.active_step;

        // Asegúrate de que el order tenga ID y status_name
        if (order && order.id && status_name) {
            const orderElement = $(`#order-${order.id}`);

            if (orderElement.length) {
                // Actualizar el estado en el encabezado
                orderElement.find('.card-header').html("<strong>" + status_name + "</strong>");

                // Actualizar los pasos del estado
                orderElement.find('.step').each(function (index) {
                    $(this).toggleClass('active', index < active_step);
                });
            } else {
                console.warn(`No se encontró el elemento para el pedido con ID: ${order.id}`);
            }
        } else {
            console.error('El evento no contiene los datos esperados:', order);
        }
    })
    .error((err) => {
        console.error('Error al suscribirse al canal:', err);
    });