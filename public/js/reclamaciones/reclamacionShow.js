$(document).ready(function () {
    /*// Suponiendo que $reclamo->comprobante se obtiene desde el backend
    let comprobanteUrl = $('#comprobante-container').data('comprobante');  // Ruta al comprobante
    console.log(comprobanteUrl);
    let comprobanteExtension = comprobanteUrl.split('.').pop().toLowerCase();

    // Mostrar el botón correspondiente según el tipo de archivo
    if (comprobanteExtension === 'pdf') {
        $('#comprobante-container').html(`
            <button class="btn btn-primary" id="view-pdf-btn">Ver comprobante PDF</button>
        `);

        // Evento para mostrar el PDF en el modal
        $('#view-pdf-btn').on('click', function () {
            $('#comprobante-content').html(`
                <iframe src="${comprobanteUrl}" width="100%" height="500px"></iframe>
            `);
            $('#comprobanteModal').modal('show');
        });
    } else if (['jpg', 'jpeg', 'png'].includes(comprobanteExtension)) {
        $('#comprobante-container').html(`
            <button class="btn btn-primary" id="view-image-btn">Ver comprobante</button>
        `);

        // Evento para mostrar la imagen en el modal
        $('#view-image-btn').on('click', function () {
            $('#comprobante-content').html(`
                <img src="${comprobanteUrl}" class="img-fluid" alt="Comprobante">
            `);
            $('#comprobanteModal').modal('show');
        });
    }*/
    $('.comprobante-item').each(function (index) {
        let url = $(this).data('url');
        let ext = $(this).data('extension').toLowerCase();

        if (ext === 'pdf') {
            $(this).html(`
                <button class="btn btn-outline-danger btn-sm view-comprobante" data-url="${url}" data-type="pdf">
                    Ver PDF #${index + 1}
                </button>
            `);
        } else if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
            $(this).html(`
                <img src="${url}" class="img-thumbnail view-comprobante" 
                     data-url="${url}" data-type="image" 
                     style="max-width: 100px; cursor: pointer;" title="Clic para ampliar">
            `);
        } else {
            $(this).html(`<span class="text-muted">Archivo no compatible</span>`);
        }
    });

    // Evento delegado para ver comprobantes
    $(document).on('click', '.view-comprobante', function () {
        let url = $(this).data('url');
        let type = $(this).data('type');

        if (type === 'pdf') {
            $('#comprobante-content').html(`<iframe src="${url}" width="100%" height="500px"></iframe>`);
        } else if (type === 'image') {
            $('#comprobante-content').html(`<img src="${url}" class="img-fluid">`);
        }

        $('#comprobanteModal').modal('show');
    });

    $(document).on('click', '#btn-submit', function () {
        let respuesta = $('#respuesta').val().trim();

        if (!respuesta) {
            toastr.error('La respuesta es obligatoria.', 'Error', {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right"
            });
            return;
        }

        $.confirm({
            title: 'Confirmar acción',
            content: '¿Qué deseas hacer con esta respuesta?',
            type: 'blue',
            buttons: {
                guardar: {
                    text: 'Solo guardar respuesta',
                    btnClass: 'btn-primary',
                    action: function () {
                        enviarRespuesta('revisado');
                    }
                },
                solucionar: {
                    text: 'Guardar y solucionar reclamo',
                    btnClass: 'btn-success',
                    action: function () {
                        enviarRespuesta('solucionado');
                    }
                },
                anular: {
                    text: 'Guardar y anular reclamo',
                    btnClass: 'btn-danger',
                    action: function () {
                        enviarRespuesta('anulado');
                    }
                },
                cancelar: {
                    text: 'Cancelar',
                    action: function () {
                        $.alert({
                            title: 'Acción cancelada',
                            content: 'Has cancelado el proceso.',
                            type: 'orange',
                            buttons: {
                                ok: {
                                    text: 'Entendido',
                                    btnClass: 'btn-primary'
                                }
                            }
                        });
                    }
                }
            }
        });
    });
});

function enviarRespuesta(estado) {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    let url = $('#btn-submit').data('url');
    let reclamo_id = $('#reclamo_id').val();
    let data = {
        respuesta: $('#respuesta').val().trim(),
        estado: estado,
        _token: csrfToken,
        reclamo_id: reclamo_id
    };

    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        success: function (data) {
            toastr.success(data.message, 'Éxito', {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "hideDuration": "2000",
                "timeOut": "2000",
            });
            setTimeout(function () {
                location.reload();
            }, 2000);
        },
        error: function (data) {
            let errors = data.responseJSON.errors || { error: 'Error al guardar la respuesta.' };
            for (let property in errors) {
                toastr.error(errors[property], 'Error', {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right"
                });
            }
        }
    });
}