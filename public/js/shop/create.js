$(document).ready(function () {
    $formCreate = $('#formCreate');
    //$formCreate.on('submit', storeMaterial);
    $('#btn-submit').on('click', storeShop);

    //$(document).on('click', '[data-delete]', deleteType);
    $('#department').select2({
        placeholder: "Selecione departamento",
        allowClear: true,
        width: '100%',   // Asegura que el ancho sea el correcto
        dropdownAutoWidth: true,
        theme: 'bootstrap4',  // Especifica el tema aquí
        dropdownParent: $('#department').parent()
    });

    $('#province').select2({
        placeholder: "Selecione provincia",
        allowClear: true,
        width: '100%',   // Asegura que el ancho sea el correcto
        dropdownAutoWidth: true,
        theme: 'bootstrap4',  // Especifica el tema aquí
        dropdownParent: $('#province').parent()
    });

    $('#district').select2({
        placeholder: "Selecione distrito",
        allowClear: true,
        width: '100%',   // Asegura que el ancho sea el correcto
        dropdownAutoWidth: true,
        theme: 'bootstrap4',  // Especifica el tema aquí
        dropdownParent: $('#district').parent()
    });

    $('#owner').select2({
        placeholder: "Selecione propietario",
        allowClear: true,
        width: '100%',   // Asegura que el ancho sea el correcto
        dropdownAutoWidth: true,
        theme: 'bootstrap4',  // Especifica el tema aquí
        /*dropdownParent: $('#owner').parent()*/
    });

    $('#department').on('change', function() {
        let departmentId = $(this).val();

        if (departmentId) {
            $.ajax({
                url: `/provincias/${departmentId}`,
                type: 'GET',
                dataType: 'json',
                success: function(provinces) {
                    $('#province').empty().append('<option value="">Seleccionar</option>');
                    $('#district').empty().append('<option value="">Seleccionar</option>');

                    $.each(provinces, function(index, province) {
                        $('#province').append(`<option value="${province.id}">${province.name}</option>`);
                    });
                }
            });
        } else {
            $('#province').empty().append('<option value="">Seleccionar</option>');
            $('#district').empty().append('<option value="">Seleccionar</option>');
        }
    });

    // Al seleccionar una provincia, cargar los distritos
    $('#province').on('change', function() {
        let provinceId = $(this).val();

        if (provinceId) {
            $.ajax({
                url: `/distritos/${provinceId}`,
                type: 'GET',
                dataType: 'json',
                success: function(districts) {
                    $('#district').empty().append('<option value="">Seleccionar</option>');

                    $.each(districts, function(index, district) {
                        $('#district').append(`<option value="${district.id}">${district.name}</option>`);
                    });
                }
            });
        } else {
            $('#district').empty().append('<option value="">Seleccionar</option>');
        }
    });

    $("#btn-selectAddress").on('click', function () {
        console.log("Abri modal");
        $("#addressModal").modal("show");
    });
});

var $formCreate;

function storeShop() {
    event.preventDefault();
    $("#btn-submit").attr("disabled", true);
    // Obtener la URL
    var createUrl = $formCreate.data('url');
    var form = new FormData($('#formCreate')[0]);
    $.ajax({
        url: createUrl,
        method: 'POST',
        data: form,
        processData:false,
        contentType:false,
        success: function (data) {
            console.log(data);
            toastr.success(data.message, 'Éxito',
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
                $("#btn-submit").attr("disabled", false);
                location.reload();
            }, 2000 )
        },
        error: function (data) {
            for ( var property in data.responseJSON.errors ) {
                toastr.error(data.responseJSON.errors[property], 'Error',
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
                        "timeOut": "4000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
            }
            $("#btn-submit").attr("disabled", false);

        },
    });
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}