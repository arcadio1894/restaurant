$(document).ready(function () {

    $('#pizza-type-select').on('change', function () {
        let selectedPrice = parseFloat($(this).find(':selected').data('price'))
        $('#product-price').text(selectedPrice.toFixed(2));
    });

    $('#add-to-cart-btn').on('click', function (e) {
        e.preventDefault();

        const productId = $(this).data('product-id');
        const authCheckUrl = $(this).data('auth-check-url');
        const addCartUrl = $(this).data('add-cart-url');
        const productTypeId = $('#pizza-type-select').val(); // Obtener el tipo de pizza seleccionado

        // Verificar autenticación
        $.ajax({
            url: authCheckUrl,
            type: "GET",
            success: function (response) {
                if (response.authenticated) {
                    // Usuario autenticado, agregar al carrito
                    addToCart(productId, productTypeId, addCartUrl);
                } else {
                    // Redirigir al login
                    window.location.href = `/login?redirect_to=producto/${productId}`;
                }
            },
            error: function (error) {
                console.error("Error al verificar la autenticación:", error);
            }
        });
    });

    function addToCart(productId, productTypeId, url) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: url,
            type: "POST",
            data: {
                product_id: productId,
                product_type_id: productTypeId, // Enviar el tipo de producto
                _token: csrfToken
            },
            success: function (data) {
                // Redirigir al carrito
                window.location.href = data.redirect;
            },
            error: function (error) {
                console.error("Error al agregar al carrito:", error);
            }
        });
    }

});