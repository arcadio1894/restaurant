$(document).ready(function () {

    $(document).on("click", '[data-ver_ruta_map]', verRutaMap);

    $.get('/api/orders', function (data) {
        let source = {
            localData: data.map(order => ({
                id: String(order.id), // Asegurar que el ID sea string
                status: order.status.trim().toLowerCase(), // Normalizar status
                text: getOrderCardByStatus(order), // Renderizado inicial
                content: `Pedido #${order.id}` // Obligatorio para evitar errores en addItem()
            })),
            dataType: "array"
        };

        let fields = [
            { name: "id", type: "string" },
            { name: "status", type: "string" },
            { name: "text", type: "string" },
            { name: "content", type: "string" } // Agregamos content
        ];

        let dataAdapter = new $.jqx.dataAdapter(source, { autoBind: true });

        $("#kanban").jqxKanban({
            width: '100%',
            height: 600,
            source: dataAdapter,
            columns: [
                { text: "Recibido", dataField: "created", width: 300 },
                { text: "Cocinando", dataField: "processing", width: 300 },
                { text: "En Trayecto", dataField: "shipped", width: 300 }
            ],
            resources: [ // Se agrega resources para evitar errores en _resources.length
                { id: 1, name: "Default", image: "default.png" }
            ],
            columnRenderer: function (element, collapsedElement, column) {
                element.css({
                    "min-width": "320px",
                    "max-width": "320px",
                    "text-align": "center"
                });
            },
            ready: function () {
                console.log("📌 Kanban inicializado correctamente.");
            }
        });

        // Forzar el diseño con CSS
        setTimeout(() => {
            $(".jqx-kanban-column").css({
                "display": "inline-block",
                "vertical-align": "top",
                "text-align": "center",
                "min-width": "350px",
                "max-width": "350px"
            });

            $(".jqx-kanban").css({
                "display": "flex",
                "justify-content": "center"
            });
        }, 500);
    });

    $("#kanban").on("itemMoved", function (event) {
        let args = event.args;
        let itemId = args.itemId;
        let oldStatus = args.oldColumn.dataField;
        let newStatus = args.newColumn.dataField;

        console.log(`🔄 Intentando mover orden ${itemId} de ${oldStatus} a ${newStatus}`);

        // ❌ Evitar que se procese automáticamente
        event.cancel = true;

        // Ajustar el ID con el prefijo "kanban_"
        let kanbanItemId = `kanban_${itemId}`;

        if (oldStatus === "created" && newStatus === "processing") {
            $.confirm({
                title: "⏳ Tiempo Estimado",
                content: '<label>¿En cuántos minutos estará listo el pedido?</label>' +
                    '<input type="number" placeholder="Ejemplo: 15" class="estimated-time form-control" required />',
                buttons: {
                    aceptar: {
                        text: "Aceptar",
                        btnClass: "btn-blue",
                        action: function () {
                            let tiempoEstimado = this.$content.find(".estimated-time").val().trim();
                            if (!tiempoEstimado || isNaN(tiempoEstimado) || tiempoEstimado <= 0) {
                                $.alert("⚠️ Debes ingresar un número válido.");
                                return false;
                            }

                            // ✅ Enviar actualización al backend
                            $.post({
                                url: '/api/orders/update-time',
                                data: { id: itemId, estimated_time: parseInt(tiempoEstimado), status: "processing" },
                                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                success: function (response) {
                                    console.log("✅ Tiempo de cocción actualizado:", response);
                                    $.alert(`✅ Tiempo estimado guardado: ${tiempoEstimado} minutos`);

                                    // 🗑️ Eliminar temporalmente el item
                                    $("#kanban").jqxKanban("removeItem", itemId);

                                    // 🔄 Recuperar la orden actualizada y volver a agregarla
                                    renderOrder(itemId);
                                },
                                error: function (error) {
                                    console.error("❌ Error al actualizar el tiempo estimado:", error);
                                    $.alert("⚠️ No se pudo actualizar el tiempo.");
                                }
                            });
                        }
                    },
                    cancelar: {
                        text: "Cancelar",
                        action: function () {
                            console.log("🚫 Movimiento cancelado, devolviendo el pedido a 'Recibido'.");

                            setTimeout(() => {
                                $("#kanban").jqxKanban("removeItem", itemId);
                                renderOrder(itemId); // Recuperar y volver a agregar la orden
                            }, 50);
                        }
                    }
                }
            });
        }

        else if (oldStatus === "processing" && newStatus === "shipped") {
            $.confirm({
                title: "🚚 Seleccionar Repartidor",
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: '/api/distributors', // Ruta para obtener los repartidores
                        method: 'GET'
                    }).done(function (response) {
                        let options = response.map(d => `<option value="${d.id}">${d.name}</option>`).join('');
                        self.setContent(`
                        <label>Selecciona el repartidor:</label>
                        <select class="form-control distributor-select">${options}</select>
                    `);
                    }).fail(function () {
                        self.setContent("❌ No se pudieron cargar los repartidores.");
                    });
                },
                buttons: {
                    aceptar: {
                        text: "Asignar Repartidor",
                        btnClass: "btn-green",
                        action: function () {
                            let distributorId = this.$content.find(".distributor-select").val();

                            if (!distributorId) {
                                $.alert("⚠️ Debes seleccionar un repartidor.");
                                return false;
                            }

                            // ✅ Enviar actualización al backend con el repartidor seleccionado
                            $.post({
                                url: '/api/orders/update-distributor',
                                data: { id: itemId, status: "shipped", distributor_id: distributorId },
                                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                success: function (response) {
                                    console.log("✅ Pedido asignado a repartidor:", response);

                                    // 🗑️ Eliminar temporalmente el item y volver a renderizarlo
                                    $("#kanban").jqxKanban("removeItem", itemId);
                                    renderOrder(itemId);
                                },
                                error: function (error) {
                                    console.error("❌ Error al actualizar el repartidor:", error);
                                    $.alert("⚠️ No se pudo asignar el repartidor.");
                                }
                            });
                        }
                    },
                    cancelar: {
                        text: "Cancelar",
                        action: function () {
                            console.log("🚫 Movimiento cancelado, devolviendo el pedido a 'Cocinando'.");

                            setTimeout(() => {
                                $("#kanban").jqxKanban("removeItem", itemId);
                                renderOrder(itemId);
                            }, 50);
                        }
                    }
                }
            });
        }

        else {
            $.confirm({
                title: "🚫 Movimiento No Permitido",
                content: "No puedes mover un pedido a este estado.",
                buttons: {
                    ok: {
                        text: "OK",
                        btnClass: "btn-red",
                        action: function () {
                            console.log(`↩️ Devolviendo pedido ${itemId} a ${oldStatus}.`);
                            setTimeout(() => {
                                $("#kanban").jqxKanban("removeItem", itemId);
                                renderOrder(itemId);
                            }, 50);
                        }
                    }
                }
            });
            return; // 🔴 Detener la ejecución aquí
        }
    });

    $(document).on('click', '[data-anular]', anularOrder);

    $(document).on("click", "[data-entregar]", function (event) {
        event.preventDefault(); // Evitar navegación

        let itemId = $(this).data("id");

        $.confirm({
            title: "📦 Confirmar Entrega",
            content: "¿Estás seguro de que este pedido ha sido entregado?",
            buttons: {
                aceptar: {
                    text: "Sí, Entregado",
                    btnClass: "btn-green",
                    action: function () {
                        // ✅ Enviar actualización al backend
                        $.post({
                            url: "/api/orders/update",
                            data: { id: itemId, status: "completed" },
                            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                            success: function (response) {
                                console.log("✅ Pedido entregado correctamente:", response);

                                // 🗑️ Eliminar la orden del Kanban
                                $("#kanban").jqxKanban("removeItem", itemId);

                                $.alert("✅ Pedido marcado como entregado.");
                            },
                            error: function (error) {
                                console.error("❌ Error al actualizar el pedido:", error);
                                $.alert("⚠️ No se pudo actualizar el estado del pedido.");
                            }
                        });
                    }
                },
                cancelar: {
                    text: "Cancelar",
                    action: function () {
                        console.log("🚫 Entrega cancelada.");
                    }
                }
            }
        });
    });

    $(document).on("click", "[data-tiempo]", function (event) {
        event.preventDefault(); // Evitar navegación

        let itemId = $(this).data("tiempo");

        // Obtener la información de la orden desde el backend
        $.get(`/api/order/${itemId}`, function (order) {
            if (!order.date_processing || !order.estimated_time) {
                $.alert("⚠️ No hay información de tiempo disponible para este pedido.");
                return;
            }

            // Convertir date_processing a un objeto Date
            let processingDate = new Date(order.date_processing);

            // Sumar los minutos del estimated_time
            processingDate.setMinutes(processingDate.getMinutes() + parseInt(order.estimated_time));

            // Formatear la fecha y hora en 12 horas (AM/PM)
            let options = { year: 'numeric', month: 'long', day: 'numeric' };
            let formattedDate = processingDate.toLocaleDateString('es-ES', options);

            let hours = processingDate.getHours();
            let minutes = processingDate.getMinutes();
            let ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12; // Convertir 0 a 12
            minutes = minutes < 10 ? '0' + minutes : minutes;
            let formattedTime = `${hours}:${minutes} ${ampm}`;

            // Mostrar el pop-up con la fecha y hora
            $.confirm({
                title: "⏰ Tiempo de Entrega",
                content: `<p style="font-size: 1.2rem; font-weight: bold;">🗓️ ${formattedDate}</p>
                      <p style="font-size: 2rem; font-weight: bold;">⏱️ ${formattedTime}</p>`,
                buttons: {
                    ok: {
                        text: "Cerrar",
                        btnClass: "btn-blue"
                    }
                }
            });

        }).fail(function () {
            $.alert("❌ No se pudo obtener la información del pedido.");
        });
    });
});

function anularOrder() {
    var order_id = $(this).data('id');

    $.confirm({
        icon: 'fas fa-trash-alt',
        theme: 'modern',
        closeIcon: true,
        animation: 'zoom',
        type: 'red',
        title: '¿Está seguro de anular esta order?',
        content: 'ORDEN - '+order_id,
        buttons: {
            confirm: {
                text: 'CONFIRMAR',
                action: function (e) {
                    $.ajax({
                        url: '/dashboard/anular/order/'+order_id,
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        processData:false,
                        contentType:false,
                        success: function (data) {
                            console.log(data);
                            $.alert(data.message);
                            setTimeout( function () {
                                getDataOrders(1);
                            }, 2000 )
                        },
                        error: function (data) {
                            $.alert("Sucedió un error en el servidor. Intente nuevamente.");
                        },
                    });
                },
            },
            cancel: {
                text: 'CANCELAR',
                action: function (e) {
                    $.alert("Cambio de estado cancelado.");
                },
            },
        },
    });

}

function renderOrder(itemId) {
    $.get(`/api/order/${itemId}`, function (order) {
        if (!order || !order.id || !order.status) {
            console.error("❌ Error: La orden no fue encontrada en la base de datos.");
            return;
        }

        let newOrderData = {
            id: String(order.id),
            status: order.status.trim().toLowerCase(),
            text: getOrderCardByStatus(order), // Generar HTML del card
            content: getOrderCardByStatus(order),
            tags: "pedido",
            color: ""
        };

        console.log("🔄 Recuperando y reinsertando la orden en el Kanban:", newOrderData);

        $("#kanban").jqxKanban("addItem", newOrderData);
    }).fail(function () {
        console.error("❌ Error: No se pudo recuperar la orden de la base de datos.");
    });
}

/**
 * 🔥 Función para seleccionar la plantilla adecuada según el estado del pedido
 */
function getOrderCardByStatus(order) {
    switch (order.status) {
        case "created":
            return getOrderCardCreated(order);
        case "processing":
            return getOrderCardProcessing(order);
        case "shipped":
            return getOrderCardShipped(order);
        default:
            return getOrderCardCreated(order);
    }
}

// Función para generar las tarjetas en AdminLTE
function getOrderCardCreated(order) {
    // Definir el color de fondo según el estado del pedido
    let bgColor = "bg-info";
    let url_comanda = document.location.origin + '/imprimir/comanda/' + order.id;
    let url_boleta = document.location.origin + '/imprimir/recibo/' + order.id;
    let address = ( order.shipping_address == null ) ? '': order.shipping_address.address;
    let latitude = ( order.shipping_address == null ) ? '': order.shipping_address.latitude;
    let longitude = ( order.shipping_address == null ) ? '': order.shipping_address.longitude;

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
                        <a href="${url_comanda}" target="_blank" data-imprimir_comanda="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">COMANDA</h6>
                        </a>
                    </div>
                </div>
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <a href="${url_boleta}" target="_blank" data-imprimir_boleta="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">BOLETA</h6>
                        </a>
                    </div>
                </div>
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <a href="#" data-ver_ruta_map data-id="${order.id}" data-address="${address}" data-latitude="${latitude}" data-longitude="${longitude}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">VER RUTA</h6>
                        </a>
                    </div>
                </div>
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <a href="#" data-anular data-id="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">ELIMINAR</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
}

function getOrderCardProcessing(order) {
    // Definir el color de fondo según el estado del pedido
    let bgColor = "bg-success";
    let url_comanda = document.location.origin + '/imprimir/comanda/' + order.id;
    let url_boleta = document.location.origin + '/imprimir/recibo/' + order.id;
    let address = ( order.shipping_address == null ) ? '': order.shipping_address.address;
    let latitude = ( order.shipping_address == null ) ? '': order.shipping_address.latitude;
    let longitude = ( order.shipping_address == null ) ? '': order.shipping_address.longitude;

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
                        <a href="${url_comanda}" target="_blank" data-imprimir_comanda="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">COMANDA</h6>
                        </a>
                        <br>
                        <a href="${url_boleta}" target="_blank" data-imprimir_boleta="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">BOLETA</h6>
                        </a>
                    </div>
                </div>
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <a href="#" data-ver_ruta_map data-id="${order.id}" data-address="${address}" data-latitude="${latitude}" data-longitude="${longitude}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">VER RUTA</h6>
                        </a>
                    </div>
                </div>
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <a href="#" data-tiempo="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">TIEMPO</h6>
                        </a>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="description-block">
                        <a href="#" data-anular data-id="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">ELIMINAR</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
}

function getOrderCardShipped(order) {
    // Definir el color de fondo según el estado del pedido
    let bgColor = "bg-warning";
    let url_comanda = document.location.origin + '/imprimir/comanda/' + order.id;
    let url_boleta = document.location.origin + '/imprimir/recibo/' + order.id;
    let address = ( order.shipping_address == null ) ? '': order.shipping_address.address;
    let latitude = ( order.shipping_address == null ) ? '': order.shipping_address.latitude;
    let longitude = ( order.shipping_address == null ) ? '': order.shipping_address.longitude;

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
                        <a href="${url_comanda}" target="_blank" data-imprimir_comanda="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">COMANDA</h6>
                        </a> 
                        <br>
                        <a href="${url_boleta}" target="_blank" data-imprimir_boleta="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">BOLETA</h6>
                        </a>
                    </div>
                </div>
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <a href="#" data-ver_ruta_map data-id="${order.id}" data-address="${address}" data-latitude="${latitude}" data-longitude="${longitude}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">VER RUTA</h6>
                        </a>
                    </div>
                </div>
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <a href="#" data-anular data-id="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">ELIMINAR</h6>
                        </a>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="description-block">
                        <a href="#" data-entregar data-id="${order.id}">
                            <h6 class="description-header" style="font-size: 0.5rem; font-weight: bold; color: black">ENTREGAR</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
}

function verRutaMap() {
    console.log("Botón clicado"); // Asegúrate de que este mensaje aparezca en la consola
    let latitude = $(this).data("latitude");
    let longitude = $(this).data("longitude");

    if (latitude && longitude) {
        // Construir la URL de Google Maps
        const googleMapsUrl = `https://www.google.com/maps?q=${latitude},${longitude}&z=15`;

        // Abrir la URL en una nueva pestaña
        window.open(googleMapsUrl, "_blank");
    } else {
        alert("No se encontraron coordenadas.");
    }
}