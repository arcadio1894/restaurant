// Variables globales
let map;
let marker;
let polygons = []; // Almacena los polígonos dibujados

// 🗺️ Inicializar el mapa
function initMap() {
    console.log("Inicializando mapa...");

    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: -12.0464, lng: -77.0428 }, // Lima, Perú
        zoom: 12
    });

    // Evento para agregar puntos al polígono
    map.addListener("click", function (event) {
        let newPoint = event.latLng;

        // Crear un nuevo polígono si no hay ninguno
        if (!polygons.length || polygons[polygons.length - 1].getPath().getLength() > 2) {
            createNewPolygon([newPoint]);
        } else {
            // Agregar puntos al polígono actual
            let lastPolygon = polygons[polygons.length - 1];
            lastPolygon.getPath().push(newPoint);
        }
    });

    console.log("Mapa cargado correctamente");
}

// 📌 Definir `initMap` globalmente para que Google Maps lo reconozca
window.initMap = initMap;

$(document).ready(function () {
    console.log("Documento listo");

    // Inicializar Select2 para la tienda
    $('#shop_id').select2({
        placeholder: "Seleccione tienda",
        allowClear: true,
        width: '100%',
        dropdownAutoWidth: true,
        theme: 'bootstrap4',
        dropdownParent: $('#shop_id').parent()
    });

    // Cambiar tienda y cargar sus datos (marcador + zonas)
    $("#shop_id").change(function () {
        let shopId = $(this).val();
        loadShopData(shopId);
    });

    // Botón para eliminar todas las zonas
    $("#clearZones").click(clearPolygons);

    // Botón para guardar zonas
    $("#saveZones").click(saveZones);
});

// 📌 Cargar datos de la tienda seleccionada (Marcador + Zonas)
function loadShopData(shopId) {
    $.ajax({
        url: `/dashboard/shops/${shopId}`,
        method: "GET",
        success: function (shop) {
            setShopMarker(shop.latitude, shop.longitude);
            loadZones(shopId);
        }
    });
}

// 📍 Agregar un marcador en la ubicación de la tienda
function setShopMarker(lat, lng) {
    if (marker) marker.setMap(null);
    marker = new google.maps.Marker({
        position: { lat: parseFloat(lat), lng: parseFloat(lng) },
        map: map,
        title: "Ubicación de la tienda"
    });
    map.setCenter(marker.getPosition());
}

// 🔄 Cargar zonas de la tienda desde la BD y dibujarlas
function loadZones(shopId) {
    clearPolygons(); // Limpia los polígonos actuales

    $.ajax({
        url: `/dashboard/shops/${shopId}/zones`,
        method: "GET",
        success: function (zones) {
            zones.forEach(zone => {
                //drawPolygon(zone.coordinates); // Ahora recibe un array de coordenadas
                let polygon = drawPolygon(zone.coordinates, zone.id, zone.status);
                polygon.zoneId = zone.id; // Guardamos el ID en el polígono
                polygon.status = zone.status; // Guardamos el status
            });
        }
    });
}

function drawPolygon(coordinates, zoneId = null, status) {
    let color = (status == 'active') ? "#FF0000" : "#808080"; // Rojo si está activa, gris si está inactiva

    let polygon = new google.maps.Polygon({
        paths: coordinates.map(coord => ({ lat: coord[1], lng: coord[0] })), // [lat, lng]
        strokeColor: color,
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: color,
        fillOpacity: 0.35,
    });

    polygon.setMap(map);
    polygons.push(polygon);

    // Guardamos el ID y status dentro del polígono
    polygon.zoneId = zoneId;
    polygon.status = status;

    // 🛑 Agregar evento para eliminar o deshabilitar con clic derecho
    polygon.addListener("rightclick", function () {
        let zoneId = this.zoneId;
        let zoneStatus = this.status;
        let currentPolygon = this;

        if (!zoneId) {
            console.error("No se encontró el ID de la zona.");
            return;
        }

        $.confirm({
            title: 'Gestionar Zona',
            content: '¿Qué acción deseas realizar?',
            type: 'orange',
            buttons: {
                deshabilitar: {
                    text: (zoneStatus == 'inactive') ? 'Habilitar':'Deshabilitar',
                    btnClass: (zoneStatus == 'inactive') ? 'btn-success':'btn-warning',
                    action: function () {
                        changeZoneStatus(zoneId, currentPolygon);
                    }
                },
                eliminar: {
                    text: 'Eliminar',
                    btnClass: 'btn-red',
                    action: function () {
                        deleteZone(zoneId, currentPolygon);
                    }
                },
                cancelar: {
                    text: 'Cancelar'
                }
            }
        });
    });

    return polygon;
}

function changeZoneStatus(zoneId, polygon) {
    $.ajax({
        url: `/dashboard/zones/${zoneId}/status`,
        method: "POST",
        data: {
            _token: $("meta[name='csrf-token']").attr("content"),
        },
        success: function (response) {
            if (response.success) {
                console.log("Zona deshabilitada correctamente.");
                polygon.setMap(null); // Quitar del mapa
                let updatedPolygon = drawPolygon(response.coordinates, zoneId, response.status); // Repintar en gris
                updatedPolygon.zoneId = zoneId;
            } else {
                console.error("Error al cambiar el estado:", response.error);
            }
        },
        error: function (xhr) {
            console.error("Error en la petición AJAX:", xhr.responseText);
        }
    });
}

function deleteZone(zoneId, polygon) {
    $.ajax({
        url: `/dashboard/zones/${zoneId}/delete`,
        method: "POST",
        data: { _token: $("meta[name='csrf-token']").attr("content") },
        success: function (response) {
            if (response.success) {
                console.log("Zona eliminada correctamente.");
                polygon.setMap(null); // Eliminar visualmente
                polygons = polygons.filter(p => p !== polygon); // Remover de la lista
            } else {
                console.error("Error al eliminar la zona:", response.error);
            }
        },
        error: function (xhr) {
            console.error("Error en la petición AJAX:", xhr.responseText);
        }
    });
}

// 🎨 Crear un nuevo polígono
function createNewPolygon(coords) {
    let polygon = new google.maps.Polygon({
        paths: coords,
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        editable: true,
        draggable: true
    });

    polygon.setMap(map);
    polygons.push(polygon);

    // Agregar botón para eliminar individualmente
    google.maps.event.addListener(polygon, 'rightclick', function () {
        removePolygon(polygon);
    });
}

// ❌ Eliminar todas las zonas del mapa
function clearPolygons() {
    polygons.forEach(polygon => polygon.setMap(null));
    polygons = [];
    console.log("Todas las zonas eliminadas.");
}

// 🗑️ Eliminar un solo polígono (clic derecho en él)
function removePolygon(polygon) {
    polygon.setMap(null);
    polygons = polygons.filter(p => p !== polygon);
    console.log("Zona eliminada individualmente.");
}

// 💾 Guardar zonas en la BD
function saveZones() {
    let shopId = $("#shop_id").val();
    var zones = [];
    polygons.forEach(function (polygon, index) {
        var path = polygon.getPath();
        var coordinates = [];

        path.forEach(function (latLng) {
            coordinates.push([latLng.lng(), latLng.lat()]); // ⚠️ Formato: [lng, lat]
        });

        // Cerrar el polígono con el primer punto
        coordinates.push(coordinates[0]);

        zones.push({ coordinates: coordinates });
    });

    console.log("Zonas a enviar:", JSON.stringify(zones));

    $.ajax({
        url: `/dashboard/zones/store`,
        method: "POST",
        data: {
            _token: $("meta[name='csrf-token']").attr("content"),
            shop_id: shopId,
            zones: zones
        },
        success: function (response) {
            alert("Zonas guardadas con éxito");
        }
    });
}

