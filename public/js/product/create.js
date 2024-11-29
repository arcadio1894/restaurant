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
});

var $formCreate;
var $select;
var $selectCategory;

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