$(document).ready(function () {
    $formCreate = $('#formCreate');
    //$formCreate.on('submit', storeMaterial);
    $('#btn-submit').on('click', updateMilestone);

    // Cuando se hace clic en el botón "Agregar"
    $('#btn-add-product').on('click', function () {
        let productId = $('#products').val();
        let productName = $('#products option:selected').text();

        // Validación de selección
        if (!productId) {
            alert('Seleccione un producto.');
            return;
        }

        // Validación para evitar duplicados
        if ($('#selected-products').find(`[data-id='${productId}']`).length > 0) {
            alert('El producto ya está en la lista.');
            return;
        }

        // Generar el elemento en el listado (Card más delgado y alineado correctamente)
        let productCard = `
            <div class="card mb-2" data-id="${productId}" style="max-width: 100%;">
                <div class="card-body d-flex align-items-center justify-content-between p-2">
                    <span class="product-name flex-grow-1">${productName}</span>
                    <button class="btn btn-danger btn-sm btn-remove-product ml-2" style="min-width: 32px;">&times;</button>
                </div>
            </div>
        `;

        // Añadir el producto a la lista
        $('#selected-products').append(productCard);
    });

    // Eliminar el producto de la lista al hacer clic en el botón de "X"
    $('#selected-products').on('click', '.btn-remove-product', function () {
        $(this).closest('.card').remove();
    });
});

var $formCreate;

function updateMilestone() {
    event.preventDefault();
    $("#btn-submit").attr("disabled", true);

    // Validar datos del formulario
    var title = $('#title').val();
    var description = $('#description').val();
    var flames = $('#flames').val();
    var products = [];

    // Obtener los productos seleccionados
    $('#selected-products .card').each(function () {
        products.push($(this).data('id'));
    });

    // Verificar si los campos están completos
    if (!title || !description || !flames || products.length === 0) {
        $.confirm({
            title: 'Error',
            content: 'Todos los campos son obligatorios y debe seleccionar al menos un producto.',
            type: 'red',
            buttons: {
                ok: {
                    text: "Entendido",
                    btnClass: 'btn-danger',
                }
            }
        });
        $("#btn-submit").attr("disabled", false);
        return;
    }

    // Obtener la URL del formulario
    var createUrl = $('#formCreate').data('url');

    // Crear el objeto FormData
    var form = new FormData($('#formCreate')[0]);

    // Agregar los IDs de los productos al FormData
    form.append('products', JSON.stringify(products));

    // Enviar AJAX
    $.ajax({
        url: createUrl,
        method: 'POST',
        data: form,
        processData: false,
        contentType: false,
        success: function (data) {
            $.confirm({
                title: 'Éxito',
                content: data.message,
                type: 'green',
                buttons: {
                    ok: {
                        text: "Aceptar",
                        btnClass: 'btn-success',
                        action: function () {
                            location.reload();
                        }
                    }
                }
            });
        },
        error: function (data) {
            $.confirm({
                title: 'Error',
                content: 'Ocurrió un error al guardar los datos.',
                type: 'red',
                buttons: {
                    ok: {
                        text: "Aceptar",
                        btnClass: 'btn-danger',
                    }
                }
            });
            $("#btn-submit").attr("disabled", false);
        },
    });
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}