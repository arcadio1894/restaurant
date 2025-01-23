$(document).ready(function() {
    // Nueva logica

    loadCart();

    // Evento para aumentar la cantidad
    $(document).on('click', '[data-plus]', function() {
        updateQuantity($(this), 1);
    });

    // Evento para disminuir la cantidad
    $(document).on('click', '[data-minus]', function() {
        updateQuantity($(this), -1);
    });

    $(document).on('click', '[data-delete_item]',function() {
        deleteItem($(this));
    });

    $(document).on('click', '#btn-observations',saveObservations);

    // Eliminar producto del carrito
    $(document).on('click', '.remove-item', function () {
        const productId = $(this).data('product-id');
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart = cart.filter(item => item.product_id !== productId);
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCart();
        toastr.success('Producto eliminado del carrito.', 'Éxito');
    });

});

const TAX_RATE = 0.18; // IGV (18%)

async function fetchProduct(productId, productTypeId) {
    try {
        const response = await $.ajax({
            url: `/products/${productId}/${productTypeId}`,
            type: 'GET'
        });
        return response;
    } catch (error) {
        console.error(`Error al obtener producto ${productId}:`, error);
        return null;
    }
}

async function fetchOption(optionId) {
    try {
        const response = await $.ajax({
            url: `/products/${optionId}/null`,
            type: 'GET'
        });
        return response;
    } catch (error) {
        console.error(`Error al obtener opción ${optionId}:`, error);
        return null;
    }
}

function showLoading() {
    $("#loading-indicator").show();  // Muestra el indicador de carga
}

function hideLoading() {
    $("#loading-indicator").hide();  // Oculta el indicador de carga
}

async function loadCart() {
    showLoading(); // Mostrar carga al comenzar

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let observations = JSON.parse(localStorage.getItem('observations')) || [];

    // Limpiar los contenedores actuales
    $('#body-items').empty();
    $('#body-observations').empty();
    $('#body-summary').empty();

    // Si el carrito está vacío
    if (cart.length === 0) {
        const clone = activateTemplate('#template-cart_empty');
        $("#body-items").append(clone);
        hideLoading();
        return;
    }

    let total = 0;

    // Promesas de productos
    const productPromises = cart.map(async (item, index) => {
        if (item.custom) {
            // Caso de producto custom
            const clone = activateTemplate('#template-cart_detail');
            let productTotal = item.total * item.quantity;
            // Setear datos comunes del producto custom
            let url_image = document.location.origin + '/images/icons/default_custom_image.png'; // Usa una imagen genérica para productos custom
            clone.querySelector("[data-image]").setAttribute('src', url_image);
            clone.querySelector("[data-product_name]").innerHTML = "Producto Personalizado";
            clone.querySelector("[data-quantity]").value = item.quantity;
            clone.querySelector("[data-detail_subtotal]").innerHTML = `S/. ${productTotal.toFixed(2)}`;
            clone.querySelector("[data-detail_price]").innerHTML = `S/. ${productTotal.toFixed(2)} / por item`;
            clone.querySelector("[data-minus]").setAttribute('data-detail_id', index);
            clone.querySelector("[data-quantity]").setAttribute('data-detail_id', index);
            clone.querySelector("[data-plus]").setAttribute('data-detail_id', index);
            clone.querySelector("[data-delete_item]").setAttribute('data-detail_id', index);
            clone.querySelector("[data-detail_productType]").innerHTML = "Tipo: "+item.product_type_name;

            // Procesar toppings
            if (item.toppings && item.toppings.length > 0) {
                item.toppings.forEach(topping => {
                    const cloneOption = activateTemplate('#template-option');
                    const selected = topping.isSelected ? 'Sí' : 'No';
                    const position =
                        topping.type === 'whole'
                            ? 'En todo'
                            : topping.type === 'left'
                            ? 'A la izquierda'
                            : 'A la derecha';
                    const extra = topping.extra === 1 ? 'Extra' : 'Normal';

                    cloneOption.querySelector("[data-option]").innerHTML = `${topping.topping_name} (${selected} - ${position} - ${extra})`;
                    clone.querySelector("[data-body_options]").append(cloneOption);
                });
            }

            // Incrementar el total general
            total += productTotal;

            return clone;
        } else {
            // Caso de producto normal
            const product = await fetchProduct(item.product_id, item.product_type_id);
            if (!product) return null; // Si no conseguimos el producto, lo omitimos

            const clone = activateTemplate('#template-cart_detail');
            let productTotal = product.price * item.quantity;

            // Rellenar los datos del producto
            let url_image = document.location.origin + '/images/products/' + product.image_url;
            clone.querySelector("[data-image]").setAttribute('src', url_image);
            clone.querySelector("[data-product_name]").innerHTML = product.name;
            clone.querySelector("[data-quantity]").value = item.quantity;
            clone.querySelector("[data-detail_price]").innerHTML = `S/. ${product.price.toFixed(2)} / por item`;
            clone.querySelector("[data-minus]").setAttribute('data-detail_id', index);
            clone.querySelector("[data-quantity]").setAttribute('data-detail_id', index);
            clone.querySelector("[data-plus]").setAttribute('data-detail_id', index);
            clone.querySelector("[data-delete_item]").setAttribute('data-detail_id', index);
            clone.querySelector("[data-detail_productType]").innerHTML = product.product_type;

            // Si tiene opciones
            if (item.options && Object.keys(item.options).length > 0) {
                const options = Object.values(item.options).flat(); // Asegurarse de que sea una lista plana
                let optionTotal = 0;

                options.forEach(option => {
                    const cloneOption = activateTemplate('#template-option');
                    cloneOption.querySelector("[data-option]").innerHTML = `${option.selection_name} (+S/. ${option.additional_price.toFixed(2)})`;
                    clone.querySelector("[data-body_options]").append(cloneOption);

                    // Sumar el precio adicional de la opción al total
                    optionTotal += option.additional_price;
                });

                // Ajustar el precio del producto base sumando las opciones por unidad
                const adjustedUnitPrice = product.price + optionTotal;
                clone.querySelector("[data-detail_price]").innerHTML = `S/. ${adjustedUnitPrice.toFixed(2)} / por item`;

                // Calcular el subtotal ajustado
                productTotal += optionTotal * item.quantity;
            } else {
                // Si no tiene opciones, mostrar el precio base
                clone.querySelector("[data-detail_price]").innerHTML = `S/. ${product.price.toFixed(2)} / por item`;
            }

            // Actualizar subtotal del producto
            total += productTotal;
            clone.querySelector("[data-detail_subtotal]").innerHTML = `S/. ${productTotal.toFixed(2)}`;

            return clone;
        }
        /*const product = await fetchProduct(item.product_id, item.product_type_id);
        if (!product) return null; // Si no conseguimos el producto, lo omitimos

        const clone = activateTemplate('#template-cart_detail');
        let productTotal = product.price * item.quantity;

        // Rellenar los datos del producto
        let url_image = document.location.origin + '/images/products/' + product.image_url;
        clone.querySelector("[data-image]").setAttribute('src', url_image);
        clone.querySelector("[data-product_name]").innerHTML = product.name;
        clone.querySelector("[data-quantity]").value = item.quantity;
        clone.querySelector("[data-detail_price]").innerHTML = `S/. ${product.price.toFixed(2)} / por item`;
        clone.querySelector("[data-minus]").setAttribute('data-detail_id', index);
        clone.querySelector("[data-quantity]").setAttribute('data-detail_id', index);
        clone.querySelector("[data-plus]").setAttribute('data-detail_id', index);
        clone.querySelector("[data-delete_item]").setAttribute('data-detail_id', index);
        clone.querySelector("[data-detail_productType]").innerHTML = product.product_type;

        // Si tiene opciones
        if (item.options && Object.keys(item.options).length > 0) {
            const options = Object.values(item.options).flat(); // Asegurarse de que sea una lista plana
            let optionTotal = 0;

            options.forEach(option => {
                const cloneOption = activateTemplate('#template-option');
                cloneOption.querySelector("[data-option]").innerHTML = `${option.selection_name} (+S/. ${option.additional_price.toFixed(2)})`;
                clone.querySelector("[data-body_options]").append(cloneOption);

                // Sumar el precio adicional de la opción al total
                optionTotal += option.additional_price;
            });

            // Ajustar el precio del producto base sumando las opciones por unidad
            const adjustedUnitPrice = product.price + optionTotal;
            clone.querySelector("[data-detail_price]").innerHTML = `S/. ${adjustedUnitPrice.toFixed(2)} / por item`;

            // Calcular el subtotal ajustado
            productTotal += optionTotal * item.quantity;
        } else {
            // Si no tiene opciones, mostrar el precio base
            clone.querySelector("[data-detail_price]").innerHTML = `S/. ${product.price.toFixed(2)} / por item`;
        }

        // Actualizar subtotal del producto
        total += productTotal;
        clone.querySelector("[data-detail_subtotal]").innerHTML = `S/. ${productTotal.toFixed(2)}`;

        return clone;*/
    });

    // Esperamos a que todas las promesas se resuelvan
    const productClones = await Promise.all(productPromises);

    // Filtramos nulls y agregamos los clones de productos al DOM
    productClones.filter(Boolean).forEach(clone => {
        $('#body-items').append(clone);
    });

    // Renderizamos el resumen del carrito
    const taxesCart = total - (total / (1 + TAX_RATE));
    const subtotalCart = total - taxesCart;
    const cloneSummary = activateTemplate('#template-cart_summary');
    cloneSummary.querySelector("[data-subtotal_cart]").innerHTML = `S/. ${subtotalCart.toFixed(2)}`;
    cloneSummary.querySelector("[data-taxes_cart]").innerHTML = `S/. ${taxesCart.toFixed(2)}`;
    cloneSummary.querySelector("[data-total_cart]").innerHTML = `S/. ${total.toFixed(2)}`;

    $('#body-summary').append(cloneSummary);

    // Renderizamos las observaciones
    const cloneObservations = activateTemplate('#template-observations');
    if (observations) {
        cloneObservations.querySelector("[data-cart_observations]").innerHTML = observations;
    }
    $('#body-observations').append(cloneObservations);

    // Ocultar indicador de carga una vez todo esté cargado
    hideLoading();
}
/*async function loadCart() {
    showLoading();  // Mostrar carga al comenzar

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let observations = JSON.parse(localStorage.getItem('observations')) || [];

    // Limpiar los contenedores actuales
    $('#body-items').empty();
    $('#body-observations').empty();
    $('#body-summary').empty();

    // Si el carrito está vacío
    if (cart.length === 0) {
        const clone = activateTemplate('#template-cart_empty');
        $("#body-items").append(clone);
        hideLoading();
        return;
    }

    let total = 0;

    // Promesas de productos
    const productPromises = cart.map(async (item, index) => {
        const product = await fetchProduct(item.product_id, item.product_type_id);
        if (!product) return null;  // Si no conseguimos el producto, lo omitimos

        const clone = activateTemplate('#template-cart_detail');
        const subtotal = product.price * item.quantity;
        total += subtotal;

        // Rellenar los datos del producto
        let url_image = document.location.origin + '/images/products/' + product.image_url;
        clone.querySelector("[data-image]").setAttribute('src', url_image);
        clone.querySelector("[data-product_name]").innerHTML = product.name;
        clone.querySelector("[data-quantity]").value = item.quantity;
        clone.querySelector("[data-detail_price]").innerHTML = `S/. ${product.price.toFixed(2)} / por item`;
        clone.querySelector("[data-minus]").setAttribute('data-detail_id', index);
        clone.querySelector("[data-quantity]").setAttribute('data-detail_id', index);
        clone.querySelector("[data-quantity]").setAttribute('value', item.quantity);
        clone.querySelector("[data-plus]").setAttribute('data-detail_id', index);
        clone.querySelector("[data-detail_subtotal]").innerHTML = "S/. " + subtotal.toFixed(2);
        clone.querySelector("[data-detail_price]").innerHTML = "S/. " + product.price.toFixed(2) + " / por item";
        clone.querySelector("[data-delete_item]").setAttribute('data-detail_id', index);
        clone.querySelector("[data-detail_productType]").innerHTML = product.product_type;
        // Si tiene opciones
        if (item.options && Object.keys(item.options).length > 0) {
            const optionPromises = Object.values(item.options).flat().map(fetchOption);
            const options = await Promise.all(optionPromises);

            options.forEach(option => {
                if (option) {
                    const cloneOption = activateTemplate('#template-option');
                    cloneOption.querySelector("[data-option]").innerHTML = option.name;
                    clone.querySelector("[data-body_options]").append(cloneOption);
                }
            });
        }

        return clone;
    });

    // Esperamos a que todas las promesas se resuelvan
    const productClones = await Promise.all(productPromises);

    // Filtramos nulls y agregamos los clones de productos al DOM
    productClones.filter(Boolean).forEach(clone => {
        $('#body-items').append(clone);
    });

    // Renderizamos el resumen del carrito
    const taxesCart = total - (total / (1 + TAX_RATE));
    const subtotalCart = total - taxesCart;
    const cloneSummary = activateTemplate('#template-cart_summary');
    cloneSummary.querySelector("[data-subtotal_cart]").innerHTML = `S/. ${subtotalCart.toFixed(2)}`;
    cloneSummary.querySelector("[data-taxes_cart]").innerHTML = `S/. ${taxesCart.toFixed(2)}`;
    cloneSummary.querySelector("[data-total_cart]").innerHTML = `S/. ${total.toFixed(2)}`;

    $('#body-summary').append(cloneSummary);

    // Renderizamos las observaciones
    const cloneObservations = activateTemplate('#template-observations');
    if (observations) {
        cloneObservations.querySelector("[data-cart_observations]").innerHTML = observations;
    }
    $('#body-observations').append(cloneObservations);

    // Ocultar indicador de carga una vez todo esté cargado
    hideLoading();
}*/

function updateQuantity(button, change) {
    // Obtener el índice del detalle desde el atributo data-detail_id
    const detailIndex = button.data('detail_id');

    // Obtener el carrito desde el localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Verificar si el índice existe en el carrito
    if (detailIndex !== undefined && cart[detailIndex]) {
        // Actualizar la cantidad del producto
        cart[detailIndex].quantity += change;

        // Si la cantidad es menor que 1, mostrar mensaje de confirmación antes de eliminar
        if (cart[detailIndex].quantity < 1) {
            if (confirm("¿Desea eliminar este producto del carrito?")) {
                cart.splice(detailIndex, 1);
            } else {
                cart[detailIndex].quantity = 1; // Restaurar la cantidad mínima
            }
        }

        // Actualizar el carrito en el localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Recargar la vista del carrito
        toastr.success("Detalle actualizado con éxito", 'Éxito',
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
            loadCart();
        }, 2000 )

    } else {
        console.error("Índice del detalle no válido.");
    }
}

/*function loadCart() {
    // Obtener carrito desde localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let observations = JSON.parse(localStorage.getItem('observations')) || [];

    // Vaciamos los contenedores actuales
    $('#body-items').empty();
    $('#body-observations').empty();
    $('#body-summary').empty();

    // Si el carrito está vacío
    if (cart.length === 0) {
        var clone = activateTemplate('#template-cart_empty');
        $("#body-items").append(clone);
        return;
    } else {

        let total = 0;

        // Almacenar todas las promesas
        const itemPromises = cart.map((item, index) => {
            return new Promise((resolve) => {
                var clone2 = activateTemplate('#template-cart_detail');

                $.ajax({
                    url: `/products/${item.product_id}/${item.product_type_id}`,
                    type: 'GET',
                    success: function (product) {
                        // Calcular subtotal del producto
                        const subtotal = product.price * item.quantity;
                        total += subtotal;

                        // Renderizar datos del producto
                        let url_image = document.location.origin + '/images/products/' + product.image_url;
                        clone2.querySelector("[data-image]").setAttribute('src', url_image);
                        clone2.querySelector("[data-product_name]").innerHTML = product.name;
                        clone2.querySelector("[data-minus]").setAttribute('data-detail_id', index);
                        clone2.querySelector("[data-quantity]").setAttribute('data-detail_id', index);
                        clone2.querySelector("[data-quantity]").setAttribute('value', item.quantity);
                        clone2.querySelector("[data-plus]").setAttribute('data-detail_id', index);
                        clone2.querySelector("[data-detail_subtotal]").innerHTML = "S/. " + subtotal.toFixed(2);
                        clone2.querySelector("[data-detail_price]").innerHTML = "S/. " + product.price.toFixed(2) + " / por item";
                        clone2.querySelector("[data-delete_item]").setAttribute('data-detail_id', index);
                        clone2.querySelector("[data-detail_productType]").innerHTML = product.product_type;

                        // Opciones del producto
                        if (item.options && Object.keys(item.options).length > 0) {
                            const optionPromises = Object.entries(item.options).map(([optionId, productIds]) => {
                                return new Promise((resolveOption) => {
                                    productIds.forEach(productId => {
                                        var clone3 = activateTemplate('#template-option');

                                        $.ajax({
                                            url: `/products/${productId}/null`,
                                            type: 'GET',
                                            success: function (optionProduct) {
                                                // Renderizar opciones
                                                clone3.querySelector("[data-option]").innerHTML = optionProduct.name;
                                                const bodyOptions = clone2.querySelector("[data-body_options]");
                                                if (bodyOptions) {
                                                    bodyOptions.append(clone3);
                                                }
                                                resolveOption(); // Resolver la promesa de esta opción
                                            },
                                            error: function () {
                                                console.error(`Error al obtener datos del producto ${productId} en las opciones`);
                                                resolveOption(); // Resolver incluso si hay un error
                                            }
                                        });
                                    });
                                });
                            });

                            // Esperar a que todas las opciones se resuelvan
                            Promise.all(optionPromises).then(() => resolve(clone2));
                        } else {
                            resolve(clone2); // Resolver si no hay opciones
                        }
                    },
                    error: function () {
                        console.error(`Error al obtener datos del producto ${item.product_id}`);
                        resolve(clone2); // Resolver incluso si hay un error
                    }
                });
            });
        });

        // Procesar todas las promesas de los items
        Promise.all(itemPromises).then((clones) => {
            clones.forEach(clone => {
                $('#body-items').append(clone);
            });

            // Renderizar el resumen después de procesar todos los productos
            var clone4 = activateTemplate('#template-cart_summary');

            //$total - ($total / 1.18)
            var taxes_cart = total - (total / (1+TAX_RATE));

            var subtotal_cart = total - taxes_cart;

            clone4.querySelector("[data-subtotal_cart]").innerHTML = "S/. "+ subtotal_cart.toFixed(2);
            clone4.querySelector("[data-taxes_cart]").innerHTML = "S/. "+ taxes_cart.toFixed(2);
            clone4.querySelector("[data-total_cart]").innerHTML = "S/. "+ total.toFixed(2);

            $("#body-summary").append(clone4);
        });
    }

    const savedObservations = JSON.parse(localStorage.getItem('observations'));

    // Clonar el template para observaciones
    var cloneO = activateTemplate('#template-observations');

    // Si hay observaciones guardadas, establecer el contenido del textarea
    if (savedObservations) {
        cloneO.querySelector("[data-cart_observations]").innerHTML = savedObservations;
    }

    // Agregar el template clonado al contenedor
    $("#body-observations").append(cloneO);

}*/

function saveObservations() {
    console.log("Guardar observaciones");
    // Obtener el contenido del textarea
    const observations = $('#observations').val();

    // Guardar o actualizar el valor en el localStorage
    localStorage.setItem('observations', JSON.stringify(observations));

    // Mostrar mensaje de confirmación (opcional)
    console.log("Observaciones guardadas en localStorage:", observations);

    toastr.success("Observaciones guardadas.", 'Éxito',
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

function deleteItem(button) {
    // Obtener el índice del detalle desde el atributo data-detail_id
    const detailIndex = button.data('detail_id');

    // Obtener el carrito desde el localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Verificar si el índice existe en el carrito
    if (detailIndex !== undefined && cart[detailIndex]) {
        // Eliminar el elemento del carrito
        cart.splice(detailIndex, 1);

        // Actualizar el carrito en el localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Recargar la vista del carrito
        loadCart();

        // Mostrar mensaje de éxito
        toastr.success("Producto eliminado del carrito.", "Éxito", {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: "2000",
            extendedTimeOut: "1000",
        });
    } else {
        // Mostrar error si el índice no es válido
        console.error("Índice del detalle no válido.");
    }

    let cartN = JSON.parse(localStorage.getItem('cart')) || [];

    // Contar el número de productos únicos
    let totalItems = cartN.length;

    // Actualizar el contenido del span
    $("#quantityCart").html(`(${totalItems})`);

}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}