$(document).ready(function () {
    $('#btn-status').on('switchChange.bootstrapSwitch', function (event, state) {
        // Obtener el estado actual
        const currentStatus = $(this).data('status'); // 1 (abierta) o 0 (cerrada)
        const newStatus = state ? 1 : 0; // Nuevo estado basado en el switch

        // Mensaje de confirmación
        const action = newStatus === 1 ? 'abrir' : 'cerrar';
        const confirmation = confirm(`¿Estás seguro de que deseas ${action} la tienda?`);

        if (!confirmation) {
            // Revertir el estado del switch si el usuario cancela
            $(this).bootstrapSwitch('state', currentStatus === 1, true);
            return;
        }

        // Enviar la solicitud AJAX para actualizar el estado
        $.ajax({
            url: '/dashboard/toggle-store-status', // Ruta hacia el controlador
            method: 'POST',
            data: {
                status_store: newStatus,
                _token: $('meta[name="csrf-token"]').attr('content'), // Agregar el token CSRF
            },
            success: function (response) {
                // Actualizar el estado en el switch
                $('#btn-status').data('status', response.statusStore);

                // Mostrar un mensaje de éxito
                toastr.success(response.message, 'Estado de la Tienda', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: 2000,
                });
            },
            error: function (xhr) {
                console.error(xhr);
                toastr.error(xhr.responseJSON.error, 'Error', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: 2000,
                });

                // Revertir el estado del switch en caso de error
                $('#btn-status').bootstrapSwitch('state', currentStatus === 1, true);
            },
        });
    });

    $('#btn-print').on('click', printEpson);
});

function printEpson() {
    $.ajax({
        url: '/dashboard/print',
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        processData:false,
        contentType:false,
        success: function(response) {
            if (response.success) {
                alert("Impresión exitosa: " + response.success);
            } else {
                alert("Error en la impresión: " + response.error);
            }
        },
        error: function(error) {
            console.error("Error:", error);
        }
    });
}