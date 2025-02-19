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

// ✅ Obtener el TOP 10 clientes con más pedidos
fetch('/api/top-clientes')
    .then(response => response.json())
    .then(data => {
        console.log("Clientes con más pedidos:", data);

        if (data.clients.length === 0) {
            document.getElementById("topClientsContainer").innerHTML = "<p>No hay datos disponibles.</p>";
            return;
        }

        const topClients = data.clients.slice(0, 10); // ✅ Top 10 clientes
        const otherClients = data.clients.slice(10); // ✅ Clientes restantes
        const container = document.getElementById("topClientsContainer");

        let html = "";

        // ✅ Construir el HTML para el Top 10
        topClients.forEach(client => {
            html += `
                <div class="progress-group">
                    ${client.first_name} ${client.last_name} (${client.phone})
                    <span class="float-right"><b>${client.orders}</b> pedidos</span>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" style="width: ${client.percentage}%;"></div>
                    </div>
                </div>
            `;
        });

        // ✅ Botón para mostrar más clientes
        if (otherClients.length > 0) {
            html += `<div id="moreClients" style="display: none;">`;

            otherClients.forEach(client => {
                html += `
                    <div class="progress-group">
                        ${client.first_name} ${client.last_name} (${client.phone})
                        <span class="float-right"><b>${client.orders}</b> pedidos</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-secondary" style="width: ${client.percentage}%;"></div>
                        </div>
                    </div>
                `;
            });

            html += `</div>`;
            html += `<a href="#" id="showMoreClients">Ver más</a>`;
        }

        container.innerHTML = html;

        // ✅ Evento para mostrar más clientes
        const showMoreLink = document.getElementById("showMoreClients");
        if (showMoreLink) {
            showMoreLink.addEventListener("click", (e) => {
                e.preventDefault();
                document.getElementById("moreClients").style.display = "block";
                showMoreLink.style.display = "none";
            });
        }
    })
    .catch(error => console.error("Error obteniendo clientes:", error));

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