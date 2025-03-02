$(document).ready(function () {
    $formCreate = $('#formCreate');
    //$formCreate.on('submit', storeMaterial);
    $('#btn-submit').on('click', storeShop);

    //$(document).on('click', '[data-delete]', deleteType);
    function initSelect2(selector, placeholder) {
        $(selector).select2({
            placeholder: placeholder,
            allowClear: true,
            width: '100%',
            dropdownAutoWidth: true,
            theme: 'bootstrap4',
            dropdownParent: $(selector).parent()
        });
    }

    // Inicializar Select2
    initSelect2('#department', 'Seleccione departamento');
    initSelect2('#province', 'Seleccione provincia');
    initSelect2('#district', 'Seleccione distrito');

    $('#owner').select2({
        placeholder: "Selecione propietario",
        allowClear: true,
        width: '100%',   // Asegura que el ancho sea el correcto
        dropdownAutoWidth: true,
        theme: 'bootstrap4',  // Especifica el tema aquí
        /*dropdownParent: $('#owner').parent()*/
    });

    // Función para cargar opciones en un select y resetear los dependientes
    // Función para cargar opciones dinámicamente
    function loadOptions(url, targetSelect, hiddenInputId, dependentSelects = []) {
        $.get(url, function(data) {
            let $select = $(targetSelect);
            let preselectedValue = $(hiddenInputId).val(); // Obtener valor preseleccionado

            $select.empty().append(new Option('Seleccionar', '', false, false));

            $.each(data, function(index, item) {
                let isSelected = item.id == preselectedValue;
                let newOption = new Option(item.name, item.id, isSelected, isSelected);
                $select.append(newOption);
            });

            $select.trigger('change.select2');

            // Resetear selects dependientes
            dependentSelects.forEach(sel => {
                $(sel).empty().append(new Option('Seleccionar', '', false, false)).trigger('change.select2');
            });

            // Si hay valor preseleccionado, disparar evento change
            if (preselectedValue) {
                $select.trigger('change');
            }
        });
    }

    // Evento: Cambio en Departamento
    $('#department').on('change', function() {
        let departmentId = $(this).val();
        if (departmentId) {
            loadOptions(`/provincias/${departmentId}`, '#province', '#province_id', ['#district']);
        } else {
            $('#province, #district').empty().append(new Option('Seleccionar', '', false, false)).trigger('change.select2');
        }
    });

    // Evento: Cambio en Provincia
    $('#province').on('change', function() {
        let provinceId = $(this).val();
        if (provinceId) {
            loadOptions(`/distritos/${provinceId}`, '#district', '#district_id');
        } else {
            $('#district').empty().append(new Option('Seleccionar', '', false, false)).trigger('change.select2');
        }
    });

    // 🚀 Cargar valores al iniciar la página
    if ($('#department').val()) {
        $('#department').trigger('change');
    }

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