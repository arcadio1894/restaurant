$(document).ready(function () {
    /*$permissions = JSON.parse($('#permissions').val());*/
    //console.log($permissions);

    $('.custom-control-input').change(function() {
        updateData();
    });


    // Variable para almacenar los nombres clave de los checkboxes activos
    var activeColumns = getActiveColumns();

    // Función para obtener y mostrar los datos iniciales
    function initData() {
        activeColumns = getActiveColumns();
        console.log(activeColumns);
        getDataProducts(1, activeColumns);
    }

    // Función para obtener y mostrar los datos con los checkboxes actuales
    function updateData() {
        activeColumns = getActiveColumns();
        getDataProducts(1, activeColumns);
    }

    // Función para obtener y mostrar los datos con los checkboxes activos y criterios de búsqueda
    function showDataSearch() {
        activeColumns = getActiveColumns();
        getDataProducts(1, activeColumns);
    }

    // Evento al cargar la página
    initData();

    $("#btnBusquedaAvanzada").click(function(e){
        e.preventDefault();
        $(".busqueda-avanzada").slideToggle();
    });

    $(document).on('click', '[data-item]', showData);

    $("#btn-search").on('click', showDataSearch);

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $('#btn-export').on('click', exportExcel);

    $modalImage = $('#modalImage');

    $formDelete = $('#formDelete');
    $formDelete.on('submit', disableMaterial);
    $modalDelete = $('#modalDelete');
    $(document).on('click', '[data-delete]', openModalDisable);

    $(document).on('click', '[data-image]', showImage);


});

var $formDelete;
var $modalDelete;
var $modalImage;
var $permissions;

// Función para obtener los nombres clave de los checkboxes activos
function getActiveColumns() {
    var activeColumns = [];
    $('input[type="checkbox"]:checked').each(function() {
        activeColumns.push($(this).data('column'));
    });
    return activeColumns;
}

function openModalDisable() {
    var material_id = $(this).data('delete');
    var description = $(this).data('description');

    $modalDelete.find('[id=material_id]').val(material_id);
    $modalDelete.find('[id=descriptionDelete]').html(description);

    $modalDelete.modal('show');
}

function disableMaterial() {
    event.preventDefault();
    // Obtener la URL
    var deleteUrl = $formDelete.data('url');
    $.ajax({
        url: deleteUrl,
        method: 'POST',
        data: new FormData(this),
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
            $modalDelete.modal('hide');
            setTimeout( function () {
                location.reload();
            }, 2000 )
        },
        error: function (data) {
            if( data.responseJSON.message && !data.responseJSON.errors )
            {
                toastr.error(data.responseJSON.message, 'Error',
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
            }
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
                        "timeOut": "2000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    });
            }
            /*for ( var property in data.responseJSON.errors ) {
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
            }*/


        },
    });
}

function showImage() {
    var path = $(this).data('src');
    $('#image-document').attr('src', path);
    $modalImage.modal('show');
}

function exportExcel() {
    var start  = $('#start').val();
    var end  = $('#end').val();
    var startDate   = moment(start, "DD/MM/YYYY");
    var endDate     = moment(end, "DD/MM/YYYY");

    console.log(start);
    console.log(end);
    console.log(startDate);
    console.log(endDate);

    if ( start == '' || end == '' )
    {
        console.log('Sin fechas');
        $.confirm({
            icon: 'fas fa-file-excel',
            theme: 'modern',
            closeIcon: true,
            animation: 'zoom',
            type: 'green',
            title: 'No especificó fechas',
            content: 'Si no hay fechas se descargará todos los ingresos',
            buttons: {
                confirm: {
                    text: 'DESCARGAR',
                    action: function (e) {
                        //$.alert('Descargado igual');
                        console.log(start);
                        console.log(end);

                        var query = {
                            start: start,
                            end: end
                        };

                        $.alert('Descargando archivo ...');

                        var url = "/dashboard/exportar/reporte/egresos/proveedores/?" + $.param(query);

                        window.location = url;

                    },
                },
                cancel: {
                    text: 'CANCELAR',
                    action: function (e) {
                        $.alert("Exportación cancelada.");
                    },
                },
            },
        });
    } else {
        console.log('Con fechas');
        console.log(JSON.stringify(start));
        console.log(JSON.stringify(end));

        var query = {
            start: start,
            end: end
        };

        toastr.success('Descargando archivo ...', 'Éxito',
            {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "2000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            });

        var url = "/dashboard/exportar/reporte/egresos/proveedores/?" + $.param(query);

        window.location = url;

    }

}

/*function showDataSearch() {
    getDataMaterials(1)
}*/

function showData() {
    //event.preventDefault();
    var numberPage = $(this).attr('data-item');
    console.log(numberPage);
    var activeColumns = getActiveColumns();
    getDataProducts(numberPage, activeColumns)
}

function getDataProducts($numberPage, $activeColumns) {
    $('[data-toggle="tooltip"]').tooltip('dispose').tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    var full_name = $('#full_name').val();
    var category = $('#category').val();
    var code = $('#code').val();

    $.get('/dashboard/get/data/products/'+$numberPage, {
        full_name:full_name,
        category: category,
        code:code,
    }, function(data) {
        if ( data.data.length == 0 )
        {
            renderDataProductsEmpty(data);
        } else {
            renderDataProducts(data, $activeColumns);
        }


    }).fail(function(jqXHR, textStatus, errorThrown) {
        // Función de error, se ejecuta cuando la solicitud GET falla
        console.error(textStatus, errorThrown);
        if (jqXHR.responseJSON.message && !jqXHR.responseJSON.errors) {
            toastr.error(jqXHR.responseJSON.message, 'Error', {
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
        }
        for (var property in jqXHR.responseJSON.errors) {
            toastr.error(jqXHR.responseJSON.errors[property], 'Error', {
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
        }
    }, 'json')
        .done(function() {
            // Configuración de encabezados
            var headers = {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            };

            $.ajaxSetup({
                headers: headers
            });
        });
}

function renderDataProductsEmpty(data) {
    var dataAccounting = data.data;
    var pagination = data.pagination;
    console.log(dataAccounting);
    console.log(pagination);

    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' productos');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    renderDataTableEmpty();
}

function renderDataProducts(data, activeColumns) {
    var dataQuotes = data.data;
    var pagination = data.pagination;

    $("#header-table").html('');
    $("#body-table").html('');
    $("#pagination").html('');
    $("#textPagination").html('');
    $("#textPagination").html('Mostrando '+pagination.startRecord+' a '+pagination.endRecord+' de '+pagination.totalFilteredRecords+' productos.');
    $('#numberItems').html('');
    $('#numberItems').html(pagination.totalFilteredRecords);

    renderDataTableHeader(activeColumns);

    for (let j = 0; j < dataQuotes.length ; j++) {
        renderDataTable(dataQuotes[j], activeColumns);
    }

    if (pagination.currentPage > 1)
    {
        renderPreviousPage(pagination.currentPage-1);
    }

    if (pagination.totalPages > 1)
    {
        if (pagination.currentPage > 3)
        {
            renderItemPage(1);

            if (pagination.currentPage > 4) {
                renderDisabledPage();
            }
        }

        for (var i = Math.max(1, pagination.currentPage - 2); i <= Math.min(pagination.totalPages, pagination.currentPage + 2); i++)
        {
            renderItemPage(i, pagination.currentPage);
        }

        if (pagination.currentPage < pagination.totalPages - 2)
        {
            if (pagination.currentPage < pagination.totalPages - 3)
            {
                renderDisabledPage();
            }
            renderItemPage(i, pagination.currentPage);
        }

    }

    if (pagination.currentPage < pagination.totalPages)
    {
        renderNextPage(pagination.currentPage+1);
    }
}

function renderDataTableEmpty() {
    var clone = activateTemplate('#item-table-empty');
    $("#body-table").append(clone);
}

function renderDataTableHeader(activeColumns) {
    var cloneHeader = document.querySelector('#item-header').content.cloneNode(true);
    var headerRow = cloneHeader.querySelector('tr');

    headerRow.querySelectorAll('[data-column]').forEach(function(column) {
        var columnName = column.dataset.column;
        if (activeColumns.includes(columnName)) {
            column.style.display = 'table-cell';
        } else {
            column.style.display = 'none';
        }
    });

    $("#header-table").append(cloneHeader);

}

function renderDataTable(data, activeColumns) {
    var clone = document.querySelector('#item-table').content.cloneNode(true);

    // Iterar sobre cada columna en el cuerpo de la tabla y mostrar u ocultar según los checkboxes activos
    clone.querySelectorAll('[data-column]').forEach(function(column) {
        var columnName = column.dataset.column;
        if (activeColumns.includes(columnName)) {
            column.style.display = 'table-cell';
        } else {
            column.style.display = 'none';
        }
    });

    // Llenar los datos en cada celda según el objeto de datos
    clone.querySelector("[data-codigo]").innerHTML = data.codigo;
    clone.querySelector("[data-nombre]").innerHTML = data.nombre;
    clone.querySelector("[data-descripcion]").innerHTML = data.descripcion;
    clone.querySelector("[data-precio]").innerHTML = data.precio;
    clone.querySelector("[data-categoria]").innerHTML = data.categoria;
    clone.querySelector("[data-ingredientes]").innerHTML = data.ingredientes;

    let url_image = document.location.origin + '/images/products/' + data.image;
    clone.querySelector("[data-ver_imagen]").setAttribute("data-src", url_image);
    clone.querySelector("[data-ver_imagen]").setAttribute("data-image", data.id);

    // Configurar enlaces y botones según los permisos y datos
    /*if ($.inArray('update_material', $permissions) !== -1) {*/
        let url = document.location.origin + '/dashboard/editar/producto/' + data.id;
        clone.querySelector("[data-editar_product]").setAttribute("href", url);
    /*} else {
        let element = clone.querySelector("[data-editar_material]");
        if (element) {
            element.style.display = 'none';
        }
    }*/

    /*if ($.inArray('enable_material', $permissions) !== -1) {*/
        clone.querySelector("[data-deshabilitar]").setAttribute("data-delete", data.id);
        clone.querySelector("[data-deshabilitar]").setAttribute("data-description", data.nombre);
    /*} else {
        let element = clone.querySelector("[data-deshabilitar]");
        if (element) {
            element.style.display = 'none';
        }
    }*/

    // Agregar la fila clonada al cuerpo de la tabla
    $("#body-table").append(clone);

    // Inicializar tooltips si es necesario
    $('[data-toggle="tooltip"]').tooltip();
}

function renderPreviousPage($numberPage) {
    var clone = activateTemplate('#previous-page');
    clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
    $("#pagination").append(clone);
}

function renderDisabledPage() {
    var clone = activateTemplate('#disabled-page');
    $("#pagination").append(clone);
}

function renderItemPage($numberPage, $currentPage) {
    var clone = activateTemplate('#item-page');
    if ( $numberPage == $currentPage )
    {
        clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
        clone.querySelector("[data-active]").setAttribute('class', 'page-item active');
        clone.querySelector("[data-item]").innerHTML = $numberPage;
    } else {
        clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
        clone.querySelector("[data-item]").innerHTML = $numberPage;
    }

    $("#pagination").append(clone);
}

function renderNextPage($numberPage) {
    var clone = activateTemplate('#next-page');
    clone.querySelector("[data-item]").setAttribute('data-item', $numberPage);
    $("#pagination").append(clone);
}
