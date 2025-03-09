let map, marker, infowindow, autocomplete;
let shopMarker = null;
$(document).ready(function () {
    console.log("Documento listo");

    $(document).on('click', '#go-to-checkout, #go-to-checkout-btn-mobile', function (e) {
        e.preventDefault(); // Prevenir el comportamiento predeterminado

        // Obtener las observaciones
        const observations = $('#observations').val();

        // Guardar las observaciones en el localStorage
        localStorage.setItem('observations', observations);

        const href = $(this).data('href');

        $.ajax({
            url: '/api/business-hours', // Cambia la ruta si es necesario
            method: 'GET',
            success: function (response) {
                if (!response.is_open) {
                    $.confirm({
                        title: '¡Aún no estamos atendiendo!',
                        content: `
                    <img src="/images/checkout/cerrado.png" style="display:block; margin: 0 auto; padding-bottom: 15px; width: 100px; height: auto;" />
                    <p class="text-center"><strong>Estamos fuera de horario. Te esperamos en nuestro próximo turno.</strong></p>
                    <p class="text-center">En este momento no podemos atenderte, pues nos encontramos fuera del horario de servicio de atención al cliente.</p>
                  
                    <p class="mb-2 text-center"><strong >Estos son nuestros horarios:</strong></p>
                    <p class="mb-0 text-center">Lunes a Domingos: 6:30pm - 11:30pm</p>
                    <p class="mb-0 text-center">Sábados y Domingos: 4:00pm - 11:30pm</p>
                `,
                        buttons: {
                            close: {
                                text: 'Ir igualmente',
                                action: function () {
                                    // Verificar la tienda seleccionada antes de redirigir
                                    const tiendaSeleccionada = localStorage.getItem('tiendaSeleccionada');
                                    if (tiendaSeleccionada && tiendaSeleccionada.trim() !== '') {
                                        if (href) {
                                            window.location.href = href;
                                        } else {
                                            console.error("El atributo data-href no está definido en el botón.");
                                        }
                                    } else {
                                        window.location.href = '/seleccionar/local';
                                    }
                                }
                            }
                        }
                    });
                } else {
                    // Verificar la tienda seleccionada antes de redirigir
                    const tiendaSeleccionada = localStorage.getItem('tiendaSeleccionada');
                    if (tiendaSeleccionada && tiendaSeleccionada.trim() !== '') {
                        if (href) {
                            window.location.href = href;
                        } else {
                            console.error("El atributo data-href no está definido en el botón.");
                        }
                    } else {
                        window.location.href = '/seleccionar/local';
                    }
                }
            },
            error: function () {
                console.error('No se pudo verificar el horario de atención.');
            }
        });

    });
});

// Función para inicializar el mapa
function initAutocomplete() {
    console.log("Google Maps API cargada correctamente.");

    const trujilloLatLng = { lat: -8.1132, lng: -79.0290 };
    map = new google.maps.Map(document.getElementById("map"), {
        center: trujilloLatLng,
        zoom: 14,
    });

    marker = new google.maps.Marker({
        position: trujilloLatLng,
        map: map,
        draggable: true,
        title: "Arrastra el marcador para cambiar la dirección"
    });

    infowindow = new google.maps.InfoWindow();

    // Evento cuando se arrastra el marcador
    google.maps.event.addListener(marker, "dragend", function() {
        updateMarkerPosition(marker.getPosition(), true);
    });

    // Evento cuando se hace clic en el mapa
    map.addListener("click", function(event) {
        marker.setPosition(event.latLng);
        updateMarkerPosition(event.latLng, true);
    });

    // Inicializar el Autocomplete
    const input = $("#searchInput")[0];
    autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo("bounds", map);

    // Evento cuando se selecciona una dirección en Google Autocomplete
    autocomplete.addListener("place_changed", function() {
        const place = autocomplete.getPlace();
        if (!place.geometry) {
            alert("No se encontró información para esta dirección.");
            return;
        }

        // Colocar marcador y actualizar la dirección
        marker.setPosition(place.geometry.location);
        map.setCenter(place.geometry.location);
        updateMarkerPosition(place.geometry.location, true);
    });
}

function updateMarkerPosition(latLng, fetchStores = false) {
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ location: latLng }, function(results, status) {
        if (status === "OK" && results[0]) {
            const address = results[0].formatted_address;

            $("#searchInput").val(address);
            $("#address").val(address);
            $("#latitude").val(latLng.lat());
            $("#longitude").val(latLng.lng());

            // Mostrar la dirección en el infowindow
            infowindow.setContent(`<div style="font-family: Arial, sans-serif;">
                <div style="font-size: 14px; font-weight: bold; color: #000;">Dirección:</div>
                <div style="font-size: 16px; font-weight: bold; color: #007BFF;">${address}</div>
            </div>`);
            infowindow.open(map, marker);

            if (fetchStores) {
                fetchShops(latLng.lat(), latLng.lng(), address);
            }
        }
    });
}

// Modificación en `fetchShops()` para limpiar `localStorage` antes de buscar tiendas
function fetchShops(latitude, longitude, address) {
    // Borrar la tienda seleccionada antes de buscar nuevas tiendas
    localStorage.removeItem("tiendaSeleccionada");

    $.ajax({
        url: "/buscar-tiendas",
        method: "POST",
        data: {
            latitude: latitude,
            longitude: longitude,
            address: address,
            _token: $('meta[name="csrf-token"]').attr("content")
        },
        success: function(response) {
            if (response.success) {
                mostrarTiendas(response.tiendas);
            } else {
                $("#body-locals").html(`<div class="alert alert-danger">${response.message}</div>`);
            }
        },
        error: function() {
            $("#body-locals").html(`<div class="alert alert-danger">Error al buscar tiendas.</div>`);
        }
    });
}

function mostrarTiendas(tiendas) {
    let html = "";

    tiendas.forEach((tienda, index) => {
        html += `
            <div class="card mb-2 tienda-card ${index === 0 ? 'border border-success' : ''}" 
                 data-id="${tienda.id}" data-precio="${tienda.price}" 
                 data-name="${tienda.name}" data-lat="${tienda.latitude}" data-lng="${tienda.longitude}">
                <div class="card-body p-2">
                    <strong class="m-1">${tienda.name}</strong>
                    <p class="m-1" style="font-size: 0.9rem" ><i class="fas fa-map-marker-alt"></i> ${tienda.address}</p>
                    <p class="m-1" style="font-size: 0.9rem"><i class="fas fa-mobile-alt"></i> ${tienda.phone} - <i class="far fa-money-bill-alt"></i> S/ ${tienda.price}</p>
                    <button class="btn btn-primary btn-sm btn-ver-mapa" 
                        data-lat="${tienda.latitude}" data-lng="${tienda.longitude}">Ver en mapa</button>

                    <button class="btn btn-success btn-sm btn-seleccionar-tienda">Seleccionar tienda</button>
                </div>
            </div>
        `;
    });

    $("#body-locals").html(html);

    // Si hay tiendas, seleccionar automáticamente la primera
    if (tiendas.length > 0) {
        seleccionarTienda($(".tienda-card").first());
    } else {
        // Si no hay tiendas, borrar tiendaSeleccionada del localStorage
        localStorage.removeItem("tiendaSeleccionada");
    }

    // Evento para ver en el mapa
    $(".btn-ver-mapa").on("click", function () {
        let lat = $(this).data("lat");
        let lng = $(this).data("lng");
        verMapa(lat, lng);
    });

    // Evento para seleccionar tienda manualmente
    $(".btn-seleccionar-tienda").on("click", function () {
        seleccionarTienda($(this).closest(".tienda-card"));
    });
}

// Función para seleccionar una tienda y guardarla en localStorage
function seleccionarTienda(card) {
    $(".tienda-card").removeClass("border border-success"); // Resetear selección previa
    card.addClass("border border-success"); // Marcar como seleccionada

    let tiendaSeleccionada = {
        tiendaId: card.data("id"),
        precioEnvio: card.data("precio"),
        nombreTienda: card.data("name"),
        direccionCliente: $("#searchInput").val(),
        latitudCliente: $("#latitude").val(),
        longitudCliente: $("#longitude").val()
    };

    localStorage.setItem("tiendaSeleccionada", JSON.stringify(tiendaSeleccionada));

    console.log("Tienda seleccionada automáticamente:", tiendaSeleccionada);
}

// Función para mostrar la tienda en el mapa
function verMapa(lat, lng) {
    // Limpiar el marcador anterior si existe
    if (shopMarker) {
        shopMarker.setMap(null);
    }

    // Crear un nuevo marcador en la ubicación de la tienda con un icono personalizado
    shopMarker = new google.maps.Marker({
        position: { lat: parseFloat(lat), lng: parseFloat(lng) },
        map: map,
        title: "Ubicación de la tienda",
        icon: {
            url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png", // Icono azul
            scaledSize: new google.maps.Size(50, 50) // Aumentamos el tamaño del icono
        }
    });

    // Centrar el mapa en la tienda con un zoom adecuado
    //map.setCenter(shopMarker.getPosition());
    map.setZoom(15); // Ajustar el zoom para mejor visualización
}

// Inicializar el mapa y la funcionalidad de autocomplete al cargar el script
window.initAutocomplete = initAutocomplete;