$(document).ready(function () {
    $formCreate = $('#formCreate');
    //$formCreate.on('submit', storeMaterial);
    $('#btn-submit').on('click', storeProduct);

    $selectCategory = $('#category');

    let optionIndex = 0;

    /*$('.add-option').on('click', function () {
        optionIndex++;
        let newOption = `
        <div class="option" data-index="${optionIndex}">
            <label>Descripción</label>
            <input type="text" name="options[${optionIndex}][description]" class="form-control">
            <label>Cantidad</label>
            <input type="number" name="options[${optionIndex}][quantity]" class="form-control">
            <label>Tipo</label>
            <select name="options[${optionIndex}][type]" class="form-control">
                <option value="radio">Radio</option>
                <option value="checkbox">Checkbox</option>
                <option value="select">Select</option>
            </select>
            <div class="selections"></div>
            <button type="button" class="btn btn-primary add-selection">Agregar Selección</button>
        </div>`;
        $('#product-options').append(newOption);
    });*/

    $("#new-option").on('click', addOption);
    $(document).on('click', '[data-selection]', addSelection);
    
    $(document).on('click', '[data-delete_option]', deleteOption);

    $(document).on('click', '[data-delete_selection]', deleteSelection);

    $('#date_validate').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        todayBtn: "linked",
        clearBtn: true,
        language: "es",
        multidate: false,
    });
});

var $formCreate;
var $select;
var $selectCategory;

function deleteSelection() {
    $(this).parent().parent().parent().remove();
}

function deleteOption() {
    $(this).parent().parent().parent().remove();
}

function addSelection() {
    var clone = activateTemplate('#template-selection');
    var place = $(this).parent().parent().next();
    place.append(clone);
    $('.selections').select2({
        placeholder: "Selecione Producto",
        allowClear: true,
    });
}

function addOption() {
    var clone = activateTemplate('#template-option');
    $("#product-options").append(clone);
    $('.options').select2({
        placeholder: "Selecione Tipo",
        allowClear: true,
    });
}

function storeProduct() {
    event.preventDefault();
    $("#btn-submit").attr("disabled", true);

    let optionsArray = [];

    // Filtrar y recorrer solo las tarjetas de opciones con datos válidos
    $("#product-options .card").each(function (index, optionCard) {
        let option = {};

        // Capturar datos de la opción
        option.description = $(optionCard).find("input[name^='options'][name$='[description]']").val();
        option.quantity = $(optionCard).find("input[name^='options'][name$='[quantity]']").val();
        option.type = $(optionCard).find("select[name^='options'][name$='[type]']").val();

        // Validar si la opción tiene datos válidos
        if (!option.description || !option.quantity || !option.type) {
            return; // Saltar esta iteración si falta algún dato obligatorio
        }

        // Capturar selecciones de la opción
        let selectionsArray = [];
        $(optionCard)
            .find("[data-option_selection='option-selections'] .card")
            .each(function (sIndex, selectionCard) {
                let selection = {};

                selection.product_id = $(selectionCard)
                    .find("select[name^='options'][name$='[product_id]']")
                    .val();
                selection.additional_price = $(selectionCard)
                    .find("input[name^='options'][name$='[additional_price]']")
                    .val();

                // Validar si la selección tiene al menos un producto seleccionado
                if (selection.product_id) {
                    selectionsArray.push(selection);
                }
            });

        // Asignar selecciones al objeto opción
        option.selections = selectionsArray;

        // Agregar la opción al array de opciones
        optionsArray.push(option);
    });

    console.log(optionsArray); // Verifica la estructura del array en la consola

    // Serializar las opciones como JSON
    let optionsJson = JSON.stringify(optionsArray);

    // Obtener la URL
    var createUrl = $formCreate.data('url');
    var form = new FormData($('#formCreate')[0]);
    form.append('options', optionsJson);

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