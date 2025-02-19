$(document).ready(function() {
    // Nueva logica

    loadCart();

    $(document).on("click", ".icono-cantidad" ,function () {
        console.log("Entre");
        let iconTrash = $(this).parent().find("i.fa-trash-alt");
        let iconMinus = $(this).parent().find("i.fa-minus");

        let cantidadElemento = $(this).parent().find("span.cantidad-numero");

        let cantidad = parseInt(cantidadElemento.text());

        let button = $(this);

        if ($(this).find("i.fa-trash-alt").is(":visible")) {
            // Acciones para eliminar el producto (solo si la papelera está visible)
            console.log("Eliminar producto");

            // Mostrar el cuadro de confirmación con jQuery Confirm
            $.confirm({
                title: 'Eliminar producto',
                content: '¿Desea eliminar este producto del carrito?',
                buttons: {
                    Eliminar: function() {
                        const detailIndex = button.data('detail_id'); // UID del producto

                        // Obtener el carrito desde el localStorage
                        let cart = JSON.parse(localStorage.getItem('cart')) || [];

                        // Buscar el producto por su UID (cart_index)
                        const productIndex = cart.findIndex(item => item.cart_index === detailIndex);

                        if (productIndex !== -1) {
                            // Eliminar producto del carrito
                            cart.splice(productIndex, 1);

                            // Actualizar el carrito en el localStorage
                            localStorage.setItem('cart', JSON.stringify(cart));

                            // Actualizar la vista después de eliminar
                            updateCartViewAfterDelete(cart);

                            // Eliminar el producto visualmente
                            button.closest(".producto").remove();

                            // Mostrar mensaje de éxito
                            toastr.success("Producto eliminado del carrito", 'Éxito', {
                                "closeButton": true,
                                "positionClass": "toast-top-right",
                                "timeOut": "2000"
                            });
                        } else {
                            console.error("No se encontró el producto con el UID especificado.");
                        }
                    },
                    Cancelar: function() {
                        // No hacer nada, solo cerrar el cuadro de confirmación
                    }
                }
            });
        } else if ($(this).find("i.fa-minus").is(":visible")) {
            // Restar cantidad (solo si el menos está visible)
            if (cantidad > 1) {
                cantidad -= 1;
                cantidadElemento.text(cantidad);
                updateQuantity($(this), -1);
            }
        } else if ($(this).find("i.fa-plus").length > 0) {
            // Sumar cantidad
            cantidad += 1;
            cantidadElemento.text(cantidad);
            updateQuantity($(this), 1);
        }

        // Mostrar/ocultar íconos según la cantidad
        if (cantidad === 1) {
            iconTrash.show();
            iconMinus.hide();
        } else {
            iconTrash.hide();
            iconMinus.show();
        }
    });

    //$(document).on('click', '#btn-observations',saveObservations);
    // Evento para los botones
    $(document).on('click', '#go-to-checkout, #go-to-checkout-btn-mobile', function (e) {
        e.preventDefault(); // Prevenir el comportamiento predeterminado

        // Obtener las observaciones
        const observations = $('#observations').val();

        // Guardar las observaciones en el localStorage
        localStorage.setItem('observations', observations);

        const href = $(this).data('href');

        $.ajax({
            url: '/api/business-hours', // Cambia la ruta si es necesario
            method: 'GET',
            success: function (response) {
                if (!response.is_open) {
                    $.confirm({
                        title: '¡Aún no estamos atendiendo!',
                        content: `
                    <img src="/images/checkout/cerrado.png" style="display:block; margin: 0 auto; padding-bottom: 15px; width: 100px; height: auto;" />
                    <p class="text-center"><strong>Estamos fuera de horario. Te esperamos en nuestro próximo turno.</strong></p>
                    <p class="text-center">En este momento no podemos atenderte, pues nos encontramos fuera del horario de servicio de atención al cliente.</p>
                  
                    <p class="mb-2 text-center"><strong >Estos son nuestros horarios:</strong></p>
                    <p class="mb-0 text-center">Lunes a Domingos: 6:30pm - 11:30pm</p>
                `,
                        buttons: {
                            close: {
                                text: 'Ir igualmente',
                                action: function () {
                                    // Acción al cerrar el pop-up
                                    if (href) {
                                        window.location.href = href;
                                    } else {
                                        console.error("El atributo data-href no está definido en el botón.");
                                    }
                                }
                            }
                        }
                    });
                } else {
                    // Redirigir al enlace en data-href
                    if (href) {
                        window.location.href = href;
                    } else {
                        console.error("El atributo data-href no está definido en el botón.");
                    }
                }
            },
            error: function () {
                console.error('No se pudo verificar el horario de atención.');
            }
        });

    });

    // Eliminar producto del carrito
    $(document).on('click', '.remove-item', function () {
        const productId = $(this).data('product-id');
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart = cart.filter(item => item.product_id !== productId);
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCart();
        toastr.success('Producto eliminado del carrito.', 'Éxito');
    });

    $(document).on('click', '#btn-delete_cart', function () {
        $.confirm({
            title: 'Confirmar eliminación',
            content: '¿Estás seguro de que quieres borrar todo el carrito?',
            type: 'red', // Color de alerta
            theme: 'modern', // Tema del modal
            buttons: {
                confirmar: {
                    text: 'Sí, borrar',
                    btnClass: 'btn-red',
                    action: function () {
                        // Eliminar el carrito del localStorage
                        localStorage.removeItem("cart");
                        localStorage.removeItem("observations");

                        // Mostrar mensaje de éxito y esperar confirmación antes de recargar
                        $.alert({
                            title: 'Carrito eliminado',
                            content: 'Tu carrito ha sido eliminado correctamente.',
                            type: 'green',
                            buttons: {
                                ok: {
                                    text: 'OK',
                                    action: function () {
                                        location.reload(); // Ahora solo recarga después de que el usuario presione "OK"
                                    }
                                }
                            }
                        });
                    }
                },
                cancelar: {
                    text: 'Cancelar',
                    action: function () {
                        // No hacer nada si el usuario cancela
                    }
                }
            }
        });
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
    let observations = localStorage.getItem('observations') || '';

    // Limpiar los contenedores actuales
    $('#body-items').empty();
    $('#body-observations').empty();
    $('#body-summary').empty();

    // Si el carrito está vacío
    if (cart.length === 0) {
        const clone = activateTemplate('#template-cart_empty');
        $("#body-items").append(clone);
        hideLoading();
        $(".mobile-fixed-cart").hide();
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
            clone.querySelector("[data-quantity]").innerHTML = item.quantity;
            clone.querySelector("[data-detail_subtotal]").innerHTML = `S/. ${productTotal.toFixed(2)}`;
            clone.querySelector("[data-detail_price]").innerHTML = `S/. ${productTotal.toFixed(2)} / por item`;
            clone.querySelector("[data-minus]").setAttribute('data-detail_id', item.cart_index);
            clone.querySelector("[data-quantity]").setAttribute('data-detail_id', item.cart_index);
            clone.querySelector("[data-plus]").setAttribute('data-detail_id', item.cart_index);
            clone.querySelector("[data-delete_item]").setAttribute('data-detail_id', item.cart_index);
            clone.querySelector("[data-detail_productType]").innerHTML = "Tipo: "+item.product_type_name;

            const iconTrash = clone.querySelector("#icon-trash");
            const iconMinus = clone.querySelector("#icon-minus");

            // Mostrar el icono correcto basado en la cantidad
            if (item.quantity > 1) {
                iconTrash.style.display = "none"; // Ocultar el icono de eliminar
                iconMinus.style.display = "inline-block"; // Mostrar el icono de restar cantidad
            } else {
                iconTrash.style.display = "inline-block"; // Mostrar el icono de eliminar
                iconMinus.style.display = "none"; // Ocultar el icono de restar cantidad
            }


            const detailsLink = clone.querySelector(".producto-detalles-link");
            const detailsPopup = clone.querySelector(".detalles-popup");

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
            } else {
                // Ocultar enlace y popup si no hay toppings
                detailsLink.style.display = "none";
                detailsPopup.style.display = "none";
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
            clone.querySelector("[data-quantity]").innerHTML = item.quantity;
            clone.querySelector("[data-detail_price]").innerHTML = `S/. ${product.price.toFixed(2)} / por item`;
            clone.querySelector("[data-minus]").setAttribute('data-detail_id', item.cart_index);
            clone.querySelector("[data-quantity]").setAttribute('data-detail_id', item.cart_index);
            clone.querySelector("[data-plus]").setAttribute('data-detail_id', item.cart_index);
            clone.querySelector("[data-delete_item]").setAttribute('data-detail_id', item.cart_index);
            clone.querySelector("[data-detail_productType]").innerHTML = product.product_type;

            const iconTrash = clone.querySelector("#icon-trash");
            const iconMinus = clone.querySelector("#icon-minus");

            // Mostrar el icono correcto basado en la cantidad
            if (item.quantity > 1) {
                iconTrash.style.display = "none"; // Ocultar el icono de eliminar
                iconMinus.style.display = "inline-block"; // Mostrar el icono de restar cantidad
            } else {
                iconTrash.style.display = "inline-block"; // Mostrar el icono de eliminar
                iconMinus.style.display = "none"; // Ocultar el icono de restar cantidad
            }

            const detailsLink = clone.querySelector(".producto-detalles-link");
            const detailsPopup = clone.querySelector(".detalles-popup");

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
                detailsLink.style.display = "none";
                detailsPopup.style.display = "none";
            }

            // Actualizar subtotal del producto
            total += productTotal;
            clone.querySelector("[data-detail_subtotal]").innerHTML = `S/. ${productTotal.toFixed(2)}`;

            return clone;
        }

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
        // Asignar el valor al textarea
        const textarea = cloneObservations.querySelector("[data-cart_observations]");
        if (textarea) {
            textarea.value = observations;

            // Actualizar el contador inmediatamente
            const currentLength = observations.length;
            const charCount = cloneObservations.querySelector("#charCount");
            if (charCount) {
                charCount.textContent = `${currentLength}/100`;

                // Cambiar el color si ya está cerca del límite
                if (currentLength >= 90) {
                    charCount.classList.add("text-danger");
                } else {
                    charCount.classList.remove("text-danger");
                }
            }
        }
    }

    // Agregar el contenido al DOM
    $('#body-observations').append(cloneObservations);

    // Ocultar indicador de carga una vez todo esté cargado
    hideLoading();

    $("#product-price-mobile").text(total.toFixed(2))
}

function updateQuantity(button, change) {
    // Obtener el índice del detalle desde el atributo data-detail_id
    const detailIndex = button.data('detail_id');

    // Obtener el carrito desde el localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Buscar el producto por su UID (cart_index)
    const productIndex = cart.findIndex(item => item.cart_index === detailIndex);

    if (productIndex !== -1) {
        // Actualizar la cantidad del producto
        cart[productIndex].quantity += change;

        // Si la cantidad es menor que 1, mostrar mensaje de confirmación antes de eliminar
        if (cart[productIndex].quantity < 1) {
            if (confirm("¿Desea eliminar este producto del carrito?")) {
                cart.splice(productIndex, 1);
            } else {
                cart[productIndex].quantity = 1; // Restaurar la cantidad mínima
            }
        }

        // Actualizar el carrito en el localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Actualizar la vista del carrito
        updateCartView(button, cart, detailIndex);
    } else {
        console.error("No se encontró el producto con el UID especificado.");
    }
}

function updateCartView(button, cart, detailIndex) {
    // Variables para los cálculos
    let total = 0;
    const TAX_RATE = 0.18;

    // Calcular totales
    cart.forEach((item) => {
        total += item.total * item.quantity;
    });

    // Calcular los valores del resumen del carrito
    const taxesCart = total - total / (1 + TAX_RATE);
    const subtotalCart = total - taxesCart;

    // Actualizar los valores del resumen
    $("[data-subtotal_cart]").text(`S/. ${subtotalCart.toFixed(2)}`);
    $("[data-taxes_cart]").text(`S/. ${taxesCart.toFixed(2)}`);
    $("[data-total_cart]").text(`S/. ${total.toFixed(2)}`);

    // Actualizar el precio del botón móvil
    $("#product-price-mobile").text(total.toFixed(2));

    // Actualizar el subtotal del producto específico
    console.log(detailIndex);
    const $productRow = $(`[data-detail_id="${detailIndex}"]`).closest(".producto");
    console.log($productRow);
    if ($productRow.length) {
        const item = cart.find(item => item.cart_index === detailIndex); // Buscar por UID
        if (item) {
            const productSubtotal = item.total * item.quantity;
            console.log(productSubtotal);
            $productRow.find("[data-detail_subtotal]").text(`S/. ${productSubtotal.toFixed(2)}`);
            console.log($productRow.find("[data-detail_subtotal]"));
            // Mostrar/ocultar iconos según la cantidad
            if (item.quantity > 1) {
                $productRow.find("#icon-trash").hide();
                $productRow.find("#icon-minus").show();
            } else {
                $productRow.find("#icon-trash").show();
                $productRow.find("#icon-minus").hide();
            }
        }
    }
}

function updateCartViewAfterDelete(cart) {
    // Si el carrito está vacío, mostramos el mensaje del template
    if (cart.length === 0) {
        updateQuantityCart();
        loadCart();
        return; // Termina la ejecución de la función
    }

    // Variables para los cálculos
    let total = 0;
    const TAX_RATE = 0.18; // Cambia según tu configuración

    // Recorrer el carrito para calcular totales
    cart.forEach((item) => {
        if (item) { // Asegúrate de que el producto no sea undefined
            total += item.total * item.quantity;
        }
    });

    // Calcular los valores del resumen del carrito
    const taxesCart = total - total / (1 + TAX_RATE);
    const subtotalCart = total - taxesCart;

    // Actualizar los valores del resumen en el carrito
    $("[data-subtotal_cart]").text(`S/. ${subtotalCart.toFixed(2)}`);
    $("[data-taxes_cart]").text(`S/. ${taxesCart.toFixed(2)}`);
    $("[data-total_cart]").text(`S/. ${total.toFixed(2)}`);

    // Actualizar el precio del botón móvil
    $("#product-price-mobile").text(total.toFixed(2));

    // Actualizar el subtotal del producto específico
    cart.forEach((item) => {
        // Busca el elemento del DOM por el UID (cart_index)
        const $productRow = $(`[data-detail_id="${item.cart_index}"]`).closest(".producto");
        if ($productRow.length && item) {
            const productSubtotal = item.total * item.quantity;

            // Actualizar el subtotal del producto en la vista
            $productRow.find("[data-detail_subtotal]").text(`S/. ${productSubtotal.toFixed(2)}`);

            // Mostrar/ocultar iconos según la cantidad
            if (item.quantity > 1) {
                $productRow.find("#icon-trash").hide();
                $productRow.find("#icon-minus").show();
            } else {
                $productRow.find("#icon-trash").show();
                $productRow.find("#icon-minus").hide();
            }
        }
    });

    updateQuantityCart();
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

function updateQuantityCart() {
    // Si no está autenticado, obtener la cantidad desde localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Contar el número de productos únicos
    let totalItems = cart.length;

    // Actualizar el contenido del span
    $("#quantityCart").html(`(${totalItems})`);
    $("#quantityCart2").html(`(${totalItems})`);
}