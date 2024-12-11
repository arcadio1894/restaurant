$(document).ready(function() {
    updateCartQuantity();
    // Llamar al método para verificar horario de atención
    $.ajax({
        url: '/api/business-hours', // Cambia la ruta si es necesario
        method: 'GET',
        success: function (response) {
            if (!response.is_open) {
                // Mostrar mensaje si el negocio está cerrado
                $('#business-message').text(response.message);
                $('#business-status').fadeIn();
            }
        },
        error: function () {
            console.error('No se pudo verificar el horario de atención.');
        }
    });

    // Cerrar el mensaje al hacer clic en el botón "X"
    $('#close-business-status').on('click', function () {
        $('#business-status').fadeOut();
    });
});

function checkAuthentication(productId, linkElement) {
    // Obtener la URL de verificación de autenticación desde el atributo data-* del enlace
    var authCheckUrl = $(linkElement).data('auth-check-url');

    // Verificar si el usuario está autenticado
    $.ajax({
        url: authCheckUrl,  // Usamos la URL del atributo data-* para verificar la autenticación
        type: "GET",
        success: function(response) {
            if (response.authenticated) {
                // Si está autenticado, agregar el producto al carrito
                addToCart(productId);
            } else {
                // Si no está autenticado, redirigir al login
                window.location.href = `/login?redirect_to=producto/${productId}`;
            }
        },
        error: function(error) {
            console.error("Error al verificar la autenticación:", error);
        }
    });
}

// Función para agregar al carrito
function addToCart(productId) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $.ajax({
        url: '/cart/manage',  // Usando ruta relativa
        type: "POST",
        data: {
            product_id: productId,
            _token: csrfToken  // Token CSRF para seguridad
        },
        success: function(data) {
            // Redirigir al carrito después de agregar el producto
            window.location.href = '/carrito';  // Usando ruta relativa
        },
        error: function(error) {
            console.error("Error al agregar al carrito:", error);
        }
    });
}

function updateCartQuantity() {
    const authCheckUrl = '/auth/check'; // URL para verificar autenticación
    const cartQuantityUrl = '/cart/quantity'; // URL para obtener la cantidad del carrito

    $.ajax({
        url: authCheckUrl,
        type: "GET",
        success: function (response) {

            // Si no está autenticado, obtener la cantidad desde localStorage
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Contar el número de productos únicos
            let totalItems = cart.length;

            // Actualizar el contenido del span
            $("#quantityCart").html(`(${totalItems})`);

        },
        error: function (error) {
            console.error("Error al verificar autenticación:", error);
            $("#quantityCart").html(`(0)`);
        }
    });
}

