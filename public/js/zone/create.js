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

            });
        }
    });
}

function drawPolygon(coordinates, zoneId = null) {
    let polygon = new google.maps.Polygon({
        paths: coordinates.map(coord => ({ lat: coord[1], lng: coord[0] })), // [lat, lng]
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
    });

    polygon.setMap(map);
    polygons.push(polygon);

    // 🛑 Agregar evento para eliminar con clic derecho
    polygon.addListener("rightclick", function () {
        $.confirm({
            title: 'Eliminar zona',
            content: '¿Deseas eliminar esta zona?',
            type: 'red',
            icon: 'fa fa-exclamation-triangle',
            buttons: {
                cancelar: {
                    text: 'Cancelar',
                    action: function () {
                        // No hacer nada
                    }
                },
                eliminar: {
                    text: 'Eliminar',
                    btnClass: 'btn-red',
                    action: function () {
                        polygon.setMap(null); // Ocultar en el mapa
                        polygons = polygons.filter(p => p !== polygon); // Eliminar de la lista
                        if (zoneId) {
                            deleteZone(zoneId); // Eliminar de la base de datos
                        }
                    }
                }
            }
        });
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

