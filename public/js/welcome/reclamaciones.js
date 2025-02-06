$(document).ready(function() {

    const mensajes = {
        reclamo: 'RECLAMO: Disconformidad relacionada con los productos o servicios.',
        queja: 'QUEJA: Disconformidad no relacionada a los productos o servicios; o, malestar o descontento respecto a la atención al público.'
    };

    $('#tipo_documento').select2({
        placeholder: "Selecione categoría",
        allowClear: true,
    });

    $('#telefono').on('input', function() {
        // Permitir solo números
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    $('#telefono_representante').on('input', function() {
        // Permitir solo números
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    $('#monto').on('input', function() {
        // Permitir solo números y un solo punto decimal
        $(this).val(function(index, value) {
            return value.replace(/[^0-9.]/g, '')   // Permitir solo números y punto
                .replace(/(\..*)\./g, '$1'); // Eliminar puntos adicionales
        });
    });

    // Manejar el cambio en los radio buttons
    $('input[name="menor_edad"]').on('change', function() {
        if ($(this).val() === 'si') {
            $('#datos-representante').slideDown();  // Mostrar la sección
        } else {
            $('#datos-representante').slideUp();    // Ocultar la sección
        }
    });

    $('#descripcion').on('input', function() {
        const maxLength = 300;
        let value = $(this).val();

        // Si el valor supera la longitud máxima, recortarlo
        if (value.length > maxLength) {
            $(this).val(value.substring(0, maxLength));
        }

        // Mostrar los caracteres restantes
        $('#charCount').text(`${maxLength - $(this).val().length} caracteres restantes`);
    });

});