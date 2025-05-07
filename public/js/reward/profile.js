$(document).ready(function() {
    $('#btn-submit').on('click', function(e) {
        e.preventDefault();

        // Bloquear el botón para evitar múltiples envíos
        $('#btn-submit').prop('disabled', true).text('Guardando...');

        // Serializamos los datos del formulario
        const formData = $('#profile-form').serialize();
        const actionUrl = $('#profile-form').data('action');

        $.confirm({
            title: '¿Confirmar guardado?',
            content: '¿Deseas actualizar los datos de perfil?',
            type: 'green',
            buttons: {
                confirmar: {
                    text: 'Guardar',
                    btnClass: 'btn-success',
                    action: function() {
                        $.ajax({
                            url: actionUrl,
                            method: 'POST',
                            data: formData,
                            success: function(response) {
                                $.alert({
                                    title: 'Guardado exitoso',
                                    content: 'Los datos se han actualizado correctamente.',
                                    type: 'green',
                                    buttons: {
                                        ok: {
                                            text: 'Aceptar',
                                            action: function() {
                                                location.reload(); // Recarga la página
                                            }
                                        }
                                    }
                                });
                            },
                            error: function(xhr) {
                                let errors = xhr.responseJSON.errors;
                                let errorMsg = 'Ocurrió un error al guardar los datos.';

                                if (errors) {
                                    errorMsg = Object.values(errors).join('<br>');
                                }

                                $.alert({
                                    title: 'Error',
                                    content: errorMsg,
                                    type: 'red'
                                });

                                // Desbloquear el botón en caso de error
                                $('#btn-submit').prop('disabled', false).text('Guardar');
                            }
                        });
                    }
                },
                cancelar: {
                    text: 'Cancelar',
                    btnClass: 'btn-danger',
                    action: function() {
                        // Desbloquear el botón si se cancela la operación
                        $('#btn-submit').prop('disabled', false).text('Guardar');
                    }
                }
            }
        });
    });
});
