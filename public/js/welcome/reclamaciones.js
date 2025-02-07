$(document).ready(function() {

    const mensajes = {
        reclamo: 'RECLAMO: Disconformidad relacionada con los productos o servicios.',
        queja: 'QUEJA: Disconformidad no relacionada a los productos o servicios; o, malestar o descontento respecto a la atención al público.'
    };

    $('#tipo_documento').select2({
        placeholder: "Selecione tipo de documento",
        allowClear: true,
        width: '100%',   // Asegura que el ancho sea el correcto
        dropdownAutoWidth: true,
        theme: 'bootstrap4',  // Especifica el tema aquí
        dropdownParent: $('#tipo_documento').parent()
    });

    $('#canal').select2({
        placeholder: "Selecione canal",
        allowClear: true,
        width: '100%',   // Asegura que el ancho sea el correcto
        dropdownAutoWidth: true,
        theme: 'bootstrap4',  // Especifica el tema aquí
        dropdownParent: $('#canal').parent()
    });

    $('#departamento').select2({
        placeholder: "Selecione departamento",
        allowClear: true,
        width: '100%',   // Asegura que el ancho sea el correcto
        dropdownAutoWidth: true,
        theme: 'bootstrap4',  // Especifica el tema aquí
        dropdownParent: $('#departamento').parent()
    });

    $('#provincia').select2({
        placeholder: "Selecione provincia",
        allowClear: true,
        width: '100%',   // Asegura que el ancho sea el correcto
        dropdownAutoWidth: true,
        theme: 'bootstrap4',  // Especifica el tema aquí
        dropdownParent: $('#provincia').parent()
    });

    $('#distrito').select2({
        placeholder: "Selecione distrito",
        allowClear: true,
        width: '100%',   // Asegura que el ancho sea el correcto
        dropdownAutoWidth: true,
        theme: 'bootstrap4',  // Especifica el tema aquí
        dropdownParent: $('#distrito').parent()
    });

    $('#departamento').on('change', function() {
        let departmentId = $(this).val();

        if (departmentId) {
            $.ajax({
                url: `/provincias/${departmentId}`,
                type: 'GET',
                dataType: 'json',
                success: function(provinces) {
                    $('#provincia').empty().append('<option value="">Seleccionar</option>');
                    $('#distrito').empty().append('<option value="">Seleccionar</option>');

                    $.each(provinces, function(index, province) {
                        $('#provincia').append(`<option value="${province.id}">${province.name}</option>`);
                    });
                }
            });
        } else {
            $('#provincia').empty().append('<option value="">Seleccionar</option>');
            $('#distrito').empty().append('<option value="">Seleccionar</option>');
        }
    });

    // Al seleccionar una provincia, cargar los distritos
    $('#provincia').on('change', function() {
        let provinceId = $(this).val();

        if (provinceId) {
            $.ajax({
                url: `/distritos/${provinceId}`,
                type: 'GET',
                dataType: 'json',
                success: function(districts) {
                    $('#distrito').empty().append('<option value="">Seleccionar</option>');

                    $.each(districts, function(index, district) {
                        $('#distrito').append(`<option value="${district.id}">${district.name}</option>`);
                    });
                }
            });
        } else {
            $('#distrito').empty().append('<option value="">Seleccionar</option>');
        }
    });

    $('#motivo').select2({
        placeholder: "Selecione motivo",
        allowClear: true,
        width: '100%',   // Asegura que el ancho sea el correcto
        dropdownAutoWidth: true,
        theme: 'bootstrap4',  // Especifica el tema aquí
        dropdownParent: $('#motivo').parent()
    });

    $('#submotivo').select2({
        placeholder: "Selecione submotivo",
        allowClear: true,
        width: '100%',   // Asegura que el ancho sea el correcto
        dropdownAutoWidth: true,
        theme: 'bootstrap4',  // Especifica el tema aquí
        dropdownParent: $('#submotivo').parent()
    });

    // Al seleccionar un motivo, cargar los submotivos
    $('#motivo').on('change', function() {
        let motivoId = $(this).val();

        if (motivoId) {
            $.ajax({
                url: `/submotivos/${motivoId}`,
                type: 'GET',
                dataType: 'json',
                success: function(submotivos) {
                    $('#submotivo').empty().append('<option value="">Seleccionar</option>');

                    $.each(submotivos, function(index, submotivo) {
                        $('#submotivo').append(`<option value="${submotivo.id}">${submotivo.nombre}</option>`);
                    });
                }
            });
        } else {
            $('#submotivo').empty().append('<option value="">Seleccionar</option>');
        }
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

    $('#detalle').on('input', function() {
        const maxLength = 300;
        let value = $(this).val();

        // Si el valor supera la longitud máxima, recortarlo
        if (value.length > maxLength) {
            $(this).val(value.substring(0, maxLength));
        }

        // Mostrar los caracteres restantes
        $('#charCountDetalle').text(`${maxLength - $(this).val().length} caracteres restantes`);
    });

    $('#pedido_cliente').on('input', function() {
        const maxLength = 300;
        let value = $(this).val();

        // Si el valor supera la longitud máxima, recortarlo
        if (value.length > maxLength) {
            $(this).val(value.substring(0, maxLength));
        }

        // Mostrar los caracteres restantes
        $('#charCountPedidoCliente').text(`${maxLength - $(this).val().length} caracteres restantes`);
    });

    $('#pedido_cliente').on('input', function() {
        const maxLength = 300;
        let value = $(this).val();

        // Si el valor supera la longitud máxima, recortarlo
        if (value.length > maxLength) {
            $(this).val(value.substring(0, maxLength));
        }

        // Mostrar los caracteres restantes
        $('#charCountPedidoCliente').text(`${maxLength - $(this).val().length} caracteres restantes`);
    });

});