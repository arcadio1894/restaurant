import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'dac24d98f58cf734beec',
    cluster: 'us2',
    forceTLS: false
});

window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('✅ Conexión establecida con Pusher.');
});

// 🔇 Reproducir un sonido silencioso en bucle para desbloquear el audio
let silentAudio = new Audio("/sounds/silence.wav");
silentAudio.loop = true;
silentAudio.volume = 0;
silentAudio.play().then(() => {
    console.log("🔊 Sonido silencioso iniciado, desbloqueo de audio exitoso.");
}).catch(error => {
    console.warn("⚠️ No se pudo iniciar el audio en bucle:", error);
});


// Crear una función para reproducir el sonido cuando llegue una nueva orden
function playNotificationSound() {
    let audio = new Audio("/sounds/orderCreated.mp3");
    audio.play().then(() => {
        console.log("🔊 Sonido de nueva orden reproducido.");
    }).catch(error => {
        console.warn("⚠️ No se pudo reproducir el sonido automáticamente:", error);
    });
}

// Suscribirse al canal de órdenes creadas
window.Echo.channel('ordersCreated')
    .subscribed(() => {
        console.log('✅ Suscripción exitosa al canal "ordersCreated".');
    })
    .listen('.order.created', (e) => {
        console.log('🔔 Nueva orden recibida:', e);

        let order = e.order;

        if (!order || !order.id || !order.status) {
            console.error('❌ Error: El evento no contiene datos de la orden.', e);
            return;
        }

        let newOrderData = {
            id: String(order.id), // Convertir a string
            status: order.status.trim().toLowerCase(), // Asegurar que coincida con los dataField
            text: getOrderCard(order),
            content: getOrderCard(order), // Contenido en HTML
            tags: "pedido",
            color: obtenerColorEstado(order.status) // Función para asignar color
        };

        // Imprimir los datos antes de agregar al Kanban
        //console.log("📊 Datos enviados a addItem:", JSON.stringify(newOrderData, null, 2));

        // Verificar si el Kanban está listo antes de agregar el ítem
        if ($("#kanban").length && $("#kanban").data('jqxKanban')) {
            console.log("📌 Kanban detectado, agregando orden...");

            try {
                $("#kanban").jqxKanban("addItem", newOrderData);

                /*// 🔊 Intentar reproducir sonido de notificación
                let audio = new Audio("/sounds/orderCreated.mp3");
                audio.play().then(() => {
                    console.log("🔊 Sonido de nueva orden reproducido.");
                }).catch(error => {
                    console.warn("⚠️ No se pudo reproducir el sonido automáticamente debido a restricciones del navegador.");
                });*/

                // 🔊 Intentar reproducir sonido usando Web Audio API
                playNotificationSound();

                console.log("✅ Orden agregada correctamente.");
            } catch (error) {
                console.error("❌ Error al agregar la orden al Kanban:", error.message);
                console.error("🛠 Detalles del error:", error);
            }

        } else {
            console.warn("⚠️ Kanban no inicializado correctamente o no encontrado en el DOM.");
        }
    });


/**
 * Genera la tarjeta de la orden en HTML.
 */
function getOrderCard(order) {
    let bgColor = obtenerColorClase(order.status);

    return `
    <div class="card card-widget widget-user" style="margin: 5px; padding: 5px; width: 100%; min-height: 120px;">
        <div class="widget-user-header ${bgColor}" style="padding: 8px;">
            <span class="widget-user-desc" style="font-size: 14px">Pedido #${order.id}</span>
            <h5 class="widget-user-username" style="font-size: 0.8rem; padding-top: 3px">
                ${order.order_user} <br> ${order.order_phone}
            </h5>
        </div>
        <div class="widget-user-image" style="width: 40px; height: 40px; margin-top: -15px;">
            <img class="img-circle elevation-2" src="/images/users/1.jpg" alt="User Avatar" style="width: 40px; height: 40px;">
        </div>
        <div class="card-footer" style="padding: 8px;">
            <div class="row mt-3">
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <a href="#" data-imprimir_comanda="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">COMANDA</h6>
                        </a>
                    </div>
                </div>
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <a href="#" data-imprimir_boleta="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">BOLETA</h6>
                        </a>
                    </div>
                </div>
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <a href="#" data-ver_ruta="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">VER RUTA</h6>
                        </a>
                    </div>
                </div>
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <a href="#" data-eliminar="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">ELIMINAR</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
}

/**
 * Retorna la clase de color de Bootstrap según el estado de la orden.
 */
function obtenerColorClase(status) {
    switch (status) {
        case "created":
            return "bg-info"; // Azul
        case "processing":
            return "bg-success"; // Verde
        case "shipped":
            return "bg-warning"; // Amarillo
        default:
            return "bg-secondary"; // Gris
    }
}

/**
 * Retorna el color del borde lateral del Kanban según el estado de la orden.
 */
function obtenerColorEstado(status) {
    switch (status) {
        case "created":
            return "lightblue"; // Azul
        case "processing":
            return "lightgreen"; // Verde
        case "shipped":
            return "yellow"; // Amarillo
        default:
            return "gray"; // Gris
    }
}