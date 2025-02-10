$(document).ready(function() {
    $('#btn-submitState').on('click', sendInfoState);
});

function sendInfoState() {
    $.confirm({
        title: 'Confirmar Envío de código',
        content: '¿Estás seguro del código del reclamo?',
        type: 'orange',
        buttons: {
            confirmar: {
                text: 'Sí, buscar',
                btnClass: 'btn-primary',
                action: function() {
                    // Deshabilitar el botón para evitar múltiples envíos
                    $('#btn-submitState').attr('disabled', true);

                    // Obtener el valor del reCAPTCHA
                    let recaptchaResponse = grecaptcha.getResponse();
                    if (recaptchaResponse === "") {
                        toastr.error('Por favor completa el CAPTCHA.', 'Error', {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right"
                        });
                        $('#btn-submitState').attr('disabled', false);
                        return;
                    }

                    // Obtener el código del reclamo
                    let codigo = $('#codigo').val().trim();

                    if (!codigo) {
                        toastr.error('El código del reclamo es obligatorio.', 'Error', {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right"
                        });
                        $('#btn-submitState').attr('disabled', false);
                        return;
                    }

                    // Incluir el token CSRF
                    let csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Preparar los datos para el envío
                    let data = {
                        codigo: codigo,
                        'g-recaptcha-response': recaptchaResponse,
                        _token: csrfToken  // Añadir el token CSRF
                    };

                    let url = $("#btn-submitState").data('url');

                    // Enviar datos al backend usando AJAX
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        success: function (data) {
                            // Renderizar la tabla con los resultados
                            renderTable(data.reclamo);
                            toastr.success(data.message, 'Éxito', {
                                "closeButton": true,
                                "progressBar": true,
                                "positionClass": "toast-top-right"
                            });
                        },
                        error: function (data) {
                            let errors = data.responseJSON.errors || { error: 'Error al consultar el estado del reclamo.' };
                            for (let property in errors) {
                                toastr.error(errors[property], 'Error', {
                                    "closeButton": true,
                                    "progressBar": true,
                                    "positionClass": "toast-top-right"
                                });
                            }
                            $('#btn-submitState').attr('disabled', false);
                        },
                        complete: function () {
                            // Resetear el reCAPTCHA y habilitar el botón
                            grecaptcha.reset();
                            $('#codigo').val("");
                            $('#btn-submitState').attr('disabled', false);
                        }
                    });
                }
            },
            cancelar: {
                text: 'Cancelar',
                action: function() {
                    // No hacer nada si se cancela
                    grecaptcha.reset();
                    toastr.info('El envío fue cancelado.', 'Cancelado', {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right"
                    });
                }
            }
        }
    });
}

function renderTable(reclamo) {
    let bodyTable = $('#body-table');
    bodyTable.empty();

    if (reclamo) {
        // Clonar el template y rellenar los datos
        let template = $('#item-table').html();
        let row = $(template);
        row.find('[data-fecha]').text(reclamo.fecha);
        row.find('[data-codigo]').text(reclamo.codigo);
        row.find('[data-estado]').text(reclamo.estado);
        row.find('[data-solucion]').text(reclamo.solucion || 'Sin solución');
        bodyTable.append(row);
    } else {
        // Mostrar template vacío
        let emptyTemplate = $('#item-table-empty').html();
        bodyTable.append(emptyTemplate);
    }
}