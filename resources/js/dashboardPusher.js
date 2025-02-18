import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'dac24d98f58cf734beec',
    cluster: 'us2',
    forceTLS: true,
    encrypted: true,
});

window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('Conexión establecida con Pusher.');
});

// ✅ 1️⃣ Obtener cantidad inicial de usuarios activos al cargar la página
fetch('/api/usuarios-activos')
    .then(response => response.json())
    .then(data => {
        console.log("Usuarios activos al cargar:", data.activeUsers);
        actualizarUsuariosActivos(data.activeUsers);
    })
    .catch(error => console.error("Error obteniendo usuarios activos:", error));

// ✅ Obtener cantidad inicial de usuarios registrados
fetch('/api/usuarios-registrados')
    .then(response => response.json())
    .then(data => {
        console.log("Usuarios registrados al cargar:", data.registeredUsers);
        actualizarUsuariosRegistrados(data.registeredUsers);
    })
    .catch(error => console.error("Error obteniendo usuarios registrados:", error));

function actualizarUsuariosRegistrados(cantidad) {
    console.log("Actualizando usuarios registrados a: ", cantidad);
    document.getElementById("registerUsersCount").innerText = cantidad;
}

// Escuchar el evento
window.Echo.channel("active-users") // ✅ Asegúrate de usar `.channel()`, no `.join()`
    .subscribed(() => {
        console.log('✅ Suscripción exitosa al canal "active-users".');
    })
    .listen(".user.active", (data) => {
        console.log("Evento recibido en frontend: ", data); // ✅ Agrega log para verificar si el evento llega
        actualizarUsuariosActivos(data.activeUsers);
    });

function actualizarUsuariosActivos(cantidad) {
    console.log("Actualizando usuarios activos a: ", cantidad); // ✅ Agrega log para ver si el DOM se actualiza
    document.getElementById("activeUsersCount").innerText = cantidad;
}