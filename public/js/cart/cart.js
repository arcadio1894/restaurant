$(document).ready(function() {
    // Evento para aumentar la cantidad
    $('[data-plus]').on('click', function() {
        updateQuantity($(this), 1);
    });

    // Evento para disminuir la cantidad
    $('[data-minus]').on('click', function() {
        updateQuantity($(this), -1);
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