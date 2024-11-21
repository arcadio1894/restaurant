$(document).ready(function() {
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
});

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