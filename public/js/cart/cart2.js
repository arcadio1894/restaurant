$(document).ready(function() {
    // Nueva logica

    loadCart();

    // Evento para aumentar la cantidad
    $('[data-plus]').on('click', function() {
        updateQuantity($(this), 1);
    });

    // Evento para disminuir la cantidad
    $('[data-minus]').on('click', function() {
        updateQuantity($(this), -1);
    });

    $('[data-delete_item]').on('click', function() {
        deleteItem($(this));
    });

    $("#btn-observations").on('click', saveObservations);

    // Eliminar producto del carrito
    $(document).on('click', '.remove-item', function () {
        const productId = $(this).data('product-id');
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart = cart.filter(item => item.product_id !== productId);
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCart();
        toastr.success('Producto eliminado del carrito.', 'Éxito');
    });

});

const TAX_RATE = 0.18; // IGV (18%)

function updateQuantity(button, increment) {
    let detailId = button.data('detail_id');
    let quantityInput = $(`[data-quantity][data-detail_id="${detailId}"]`);
    let newQuantity = parseInt(quantityInput.val()) + increment;

    // Evitar cantidades menores a 1
    if (newQuantity < 1) return;

    // Actualizar el valor en el input
    quantityInput.val(newQuantity);

    // Llamada AJAX para actualizar en el servidor
    $.ajax({
        url: '/cart/update-quantity',
        type: 'POST',
        data: {
            detail_id: detailId,
            quantity: newQuantity,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Actualizar subtotal del detalle
                $(`[data-quantity][data-detail_id="${detailId}"]`).closest('.row').find('.h6').text(`S/ ${response.detail_subtotal}`);

                // Actualizar totales del carrito
                $('#subtotal_cart').text(`S/ ${response.subtotal_cart}`);
                $('#taxes_cart').text(`S/ ${response.taxes_cart}`);
                $('#total_cart').text(`S/ ${response.total_cart}`);
            }
        },
        error: function(error) {
            console.error("Error al actualizar la cantidad:", error);
        }
    });
}

function loadCart() {
    // Obtener carrito desde localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let observations = JSON.parse(localStorage.getItem('observations')) || [];

    // Vaciamos los contenedores actuales
    $('#body-items').empty();
    $('#body-observations').empty();
    $('#body-summary').empty();

    // Si el carrito está vacío
    if (cart.length === 0) {
        var clone = activateTemplate('#template-cart_empty');
        $("#body-items").append(clone);
        return;
    } else {

        let total = 0;

        // Almacenar todas las promesas
        const itemPromises = cart.map((item, index) => {
            return new Promise((resolve) => {
                var clone2 = activateTemplate('#template-cart_detail');

                $.ajax({
                    url: `/products/${item.product_id}`,
                    type: 'GET',
                    success: function (product) {
                        // Calcular subtotal del producto
                        const subtotal = product.price * item.quantity;
                        total += subtotal;

                        // Renderizar datos del producto
                        let url_image = document.location.origin + '/images/products/' + product.image_url;
                        clone2.querySelector("[data-image]").setAttribute('src', url_image);
                        clone2.querySelector("[data-product_name]").innerHTML = product.name;
                        clone2.querySelector("[data-minus]").setAttribute('data-detail_id', index);
                        clone2.querySelector("[data-quantity]").setAttribute('data-detail_id', index);
                        clone2.querySelector("[data-quantity]").setAttribute('value', item.quantity);
                        clone2.querySelector("[data-plus]").setAttribute('data-detail_id', index);
                        clone2.querySelector("[data-detail_subtotal]").innerHTML = "S/. " + subtotal.toFixed(2);
                        clone2.querySelector("[data-detail_price]").innerHTML = "S/. " + product.price.toFixed(2) + " / por item";
                        clone2.querySelector("[data-delete_item]").setAttribute('data-detail_id', index);
                        clone2.querySelector("[data-detail_productType]").innerHTML = product.product_type;

                        // Opciones del producto
                        if (item.options && Object.keys(item.options).length > 0) {
                            const optionPromises = Object.entries(item.options).map(([optionId, productIds]) => {
                                return new Promise((resolveOption) => {
                                    productIds.forEach(productId => {
                                        var clone3 = activateTemplate('#template-option');

                                        $.ajax({
                                            url: `/products/${productId}`,
                                            type: 'GET',
                                            success: function (optionProduct) {
                                                // Renderizar opciones
                                                clone3.querySelector("[data-option]").innerHTML = optionProduct.name;
                                                const bodyOptions = clone2.querySelector("[data-body_options]");
                                                if (bodyOptions) {
                                                    bodyOptions.append(clone3);
                                                }
                                                resolveOption(); // Resolver la promesa de esta opción
                                            },
                                            error: function () {
                                                console.error(`Error al obtener datos del producto ${productId} en las opciones`);
                                                resolveOption(); // Resolver incluso si hay un error
                                            }
                                        });
                                    });
                                });
                            });

                            // Esperar a que todas las opciones se resuelvan
                            Promise.all(optionPromises).then(() => resolve(clone2));
                        } else {
                            resolve(clone2); // Resolver si no hay opciones
                        }
                    },
                    error: function () {
                        console.error(`Error al obtener datos del producto ${item.product_id}`);
                        resolve(clone2); // Resolver incluso si hay un error
                    }
                });
            });
        });

        // Procesar todas las promesas de los items
        Promise.all(itemPromises).then((clones) => {
            clones.forEach(clone => {
                $('#body-items').append(clone);
            });

            // Renderizar el resumen después de procesar todos los productos
            var clone4 = activateTemplate('#template-cart_summary');

            //$total - ($total / 1.18)
            var taxes_cart = total - (total / (1+TAX_RATE));

            var subtotal_cart = total - taxes_cart;

            clone4.querySelector("[data-subtotal_cart]").innerHTML = "S/. "+ subtotal_cart.toFixed(2);
            clone4.querySelector("[data-taxes_cart]").innerHTML = "S/. "+ taxes_cart.toFixed(2);
            clone4.querySelector("[data-total_cart]").innerHTML = "S/. "+ total.toFixed(2);

            $("#body-summary").append(clone4);
        });
    }

    if ( observations.length === 0 )
    {
        var clone3 = activateTemplate('#template-observations');
        $("#body-observations").append(clone3);
    }


}

function saveObservations() {
    let cart_id = $("#cart_id").val();
    let observation = $("#observations").val();

    event.preventDefault();
    $("#btn-observations").attr("disabled", true);
    // Obtener la URL

}

function deleteItem(button) {
    let detailId = button.data('detail_id');

    // Confirmación antes de eliminar
    if (!confirm('¿Estás seguro de que deseas eliminar este ítem del carrito?')) return;

    // Llamada AJAX para eliminar el detalle
    $.ajax({
        url: `/cart/delete-detail/${detailId}`,
        type: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status === 'detail_deleted') {
                toastr.success("Detalle eliminado", 'Éxito',
                    {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
                // Eliminar el ítem visualmente
                $(`[data-detail_id="${detailId}"]`).closest('.row').remove();

                // Actualizar los totales
                $('#subtotal_cart').text(`S/ ${response.cart.subtotal}`);
                $('#taxes_cart').text(`S/ ${response.cart.taxes}`);
                $('#total_cart').text(`S/ ${response.cart.total}`);

                // Mostrar un mensaje si ya no quedan ítems en el carrito
                if (response.cart.count === 0) {
                    $('.container').html(`
                        <div class="text-center py-5">
                            <h5>Tu carrito está vacío</h5>
                            <a href="/" class="btn btn-primary mt-3">Volver a la tienda</a>
                        </div>
                    `);
                }
            } else if (response.status === 'cart_deleted') {
                // Manejar caso en el que todo el carrito es eliminado
                toastr.success("Detalle eliminado", 'Éxito',
                    {
                        "closeButton": true,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
                setTimeout( function () {
                    location.reload();
                }, 2000 )
                /*$('.container').html(`
                    <div class="text-center py-5">
                        <h5>${response.message}</h5>
                        <a href="/" class="btn btn-primary mt-3">Volver a la tienda</a>
                    </div>
                `);*/
            }
        },
        error: function(error) {
            console.error("Error al eliminar el ítem:", error);
            toastr.error('Ocurrió un error al eliminar el ítem. Por favor, intenta nuevamente.', 'Error',
                {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "2000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                });
        }
    });
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}