$(document).ready(function () {
    // Detectar tamaño de la pantalla y ajustar número de productos
    function adjustCarousel() {
        if ($(window).width() < 768) {
            $('.carousel .row .col-12').each(function () {
                $(this).addClass('single-item');
            });
        } else {
            $('.carousel .row .col-12').each(function () {
                $(this).removeClass('single-item');
            });
        }
    }

    // Llamar a la función al cargar la página y al cambiar el tamaño de la ventana
    adjustCarousel();

    $(window).resize(function () {
        adjustCarousel();
    });

    updatePrices();

    // Escuchar el evento de cambio en los radios de tamaño de pizza
    $(document).on('change', 'input[name="pizza-size"]', function () {
        //console.log("cambio");
        // Obtener el precio del radio seleccionado
        let selectedPrice = parseFloat($(this).data('price'));
        //console.log(selectedPrice);

        /*// Actualizar el precio en el elemento con id "product-price"
        $('#product-price').text(selectedPrice);

        // También actualizar el atributo data-base-price por si es necesario
        $('#product-price').attr('data-base-price', selectedPrice);

        // Actualizar el precio en el elemento con id "product-price-mobile"
        $('#product-price-mobile').text(selectedPrice);

        // También actualizar el atributo data-base-price por si es necesario
        $('#product-price-mobile').attr('data-base-price', selectedPrice);*/

        // Actualizar el precio base en el DOM
        $('#product-price, #product-price-mobile')
            .text(selectedPrice.toFixed(2))
            .attr('data-base-price', selectedPrice);

        //console.log($('#product-price, #product-price-mobile'));

        // Llama a updatePrices para recalcular el total
        updatePrices();
    });

    $('#add-to-cart-btn, #add-to-cart-btn-mobile').on('click', function (e) {
        e.preventDefault();

        let productId = $(this).data('product-id_v2');
        let authCheckUrl = $(this).data('auth-check-url');

        // Obtener el tipo de pizza seleccionado
        let productTypeId = $('input[name="pizza-size"]:checked').val() || null;

        // Recopilar los adicionales seleccionados
        let selectedAdditions = [...$selectedAdditions]; // Copiar los adicionales desde la variable global

        // Recopilar las opciones seleccionadas
        const options = {};
        let valid = true;

        // Función para mostrar mensajes de error
        function showError(message) {
            toastr.error(message, 'Error', {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: "2000",
                extendedTimeOut: "1000",
            });
        }

        $('.option-container').each(function () {
            const $container = $(this);
            const optionId = $container.data('option-id');
            const optionType = $container.data('type');
            const maxQuantity = $container.data('quantity');
            const optionDescription = $container.parent().children('strong').text();

            if (optionType === 'checkbox') {
                // Obtener checkboxes seleccionados
                const selected = $container.find('.form-check-input:checked').map(function () {
                    return {
                        option_id: optionId,
                        selection_id: $(this).data('selection_id'),
                        product_id: $(this).val(),
                        selection_name: $(this).data('selection_product_name'), // Capturamos el texto del label como nombre
                        additional_price: parseFloat($(this).data('selection_price')) || 0
                    };
                }).get();

                // Validar selección mínima y máxima
                if (selected.length === 0 || selected.length < maxQuantity) {
                    showError(`Debes seleccionar productos para la opción: ${optionDescription}.`);
                    valid = false;
                    return false; // Detener iteración
                }

                if (selected.length > maxQuantity) {
                    showError(`Has seleccionado más opciones de las permitidas para: ${optionDescription}.`);
                    valid = false;
                    return false; // Detener iteración
                }

                options[optionId] = selected;
            } else if (optionType === 'radio') {
                // Obtener radio seleccionado
                //const selected = $container.find('.form-check-input:checked').val();
                const selected = $container.find('.form-check-input:checked').map(function () {
                    return {
                        option_id: optionId,
                        selection_id: $(this).data('selection_id'),
                        product_id: $(this).val(),
                        selection_name: $(this).data('selection_product_name'), // Capturamos el texto del label como nombre
                        additional_price: parseFloat($(this).data('selection_price')) || 0
                    };
                }).get();

                console.log("Verificar: selected");
                console.log(selected);
                /*if (!selected) {
                    showError(`Debes seleccionar una opción para: ${optionDescription}.`);
                    valid = false;
                    return false; // Detener iteración
                }*/

                // Validar selección mínima y máxima
                if (selected.length === 0 || selected.length < maxQuantity) {
                    showError(`Debes seleccionar productos para la opción: ${optionDescription}.`);
                    valid = false;
                    return false; // Detener iteración
                }

                if (selected.length > maxQuantity) {
                    showError(`Has seleccionado más opciones de las permitidas para: ${optionDescription}.`);
                    valid = false;
                    return false; // Detener iteración
                }

                options[optionId] = selected;
            } /*else if (optionType === 'select') {
                // Manejar select
                const selected = $container.find('.form-select').val();

                if (!selected) {
                    showError(`Debes seleccionar una opción para: ${optionDescription}.`);
                    valid = false;
                    return false; // Detener iteración
                }

                options[optionId] = selected;
            }*/
        });

        if (!valid) {
            return; // No continuar si las validaciones fallan
        }

        //console.log("Opciones seleccionadas:", options);
        let totalOptionsPrice = 0;
        // Recorrer las opciones en el objeto `options`
        for (const optionId in options) {
            if (options.hasOwnProperty(optionId)) {
                // Sumar los precios adicionales de cada opción seleccionada
                const selectedOptions = options[optionId];
                totalOptionsPrice += selectedOptions.reduce((sum, item) => sum + item.additional_price, 0);
            }
        }
        //console.log("Total opciones: "+totalOptionsPrice);

        let priceTotal = parseFloat($("#product-price, #product-price-mobile").attr('data-base-price'), 2);
        console.log("Precio Total 1: "+priceTotal);

        let totalTotal = priceTotal + totalOptionsPrice;

        // Verificar autenticación
        $.ajax({
            url: authCheckUrl,
            type: "GET",
            success: function (response) {
                // Obtener el carrito actual de localStorage
                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                // Determinar el user_id en función de la autenticación
                let userId = response.authenticated ? response.user_id : null;

                if (response.authenticated) {
                    cart = cart.map(item => ({
                        ...item,
                        user_id: userId
                    }));
                }

                // Buscar si el producto ya está en el carrito
                let existingProduct = cart.find(item =>
                    item.product_id === productId &&
                    item.product_type_id === productTypeId &&
                    JSON.stringify(item.options) === JSON.stringify(options)
                );

                if (existingProduct) {
                    // Si el producto ya existe, incrementar la cantidad
                    existingProduct.quantity += 1;
                } else {
                    // Si el producto no existe, agregarlo como un nuevo elemento
                    cart.push({
                        product_id: productId,
                        product_type_id: productTypeId,
                        options: options,
                        quantity: 1,
                        user_id: userId, // Añadir el user_id
                        custom: false,
                        reward: false,
                        total: totalTotal,
                        cart_index: generateUUID()
                    });
                }

                // Agregar los productos adicionales como elementos independientes
                selectedAdditions.forEach(addition => {
                    const existingAddition = cart.find(item =>
                        item.product_id === addition.id &&
                        item.product_type_id === addition.productTypeId // Asegúrate de que sea un adicional
                    );

                    if (existingAddition) {
                        // Si el adicional ya existe, incrementar la cantidad
                        existingAddition.quantity += 1;
                    } else {
                        // Si el adicional no existe, agregar como nuevo elemento
                        if ( addition.productTypeId == "" )
                        {
                            cart.push({
                                product_id: addition.id,
                                product_type_id: null,
                                name: addition.name,
                                price: addition.price,
                                quantity: 1,
                                user_id: userId,
                                options: {}, // Adicionales no tienen opciones
                                custom: false, // Marcado como adicional
                                reward: false,
                                total: addition.price,
                                cart_index: generateUUID()
                            });
                        } else {
                            cart.push({
                                product_id: addition.id,
                                product_type_id: addition.productTypeId,
                                name: addition.name,
                                price: addition.price,
                                quantity: 1,
                                user_id: userId,
                                options: {}, // Adicionales no tienen opciones
                                custom: false, // Marcado como adicional
                                reward: false,
                                total: addition.price,
                                cart_index: generateUUID()
                            });
                        }

                    }
                });

                // Guardar el carrito actualizado en localStorage
                localStorage.setItem('cart', JSON.stringify(cart));

                // Actualizar la cantidad del carrito
                updateCartQuantity();

                toastr.success("Producto agregado al carrito.", "Éxito",  {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: "2000",
                    extendedTimeOut: "1000",
                });

                //window.location.reload();

            },
            error: function (error) {
                console.error("Error al verificar la autenticación:", error);
            }
        });

        //location.reload();
    });

    $(document).on('click', '[data-add_to_cart_adicional]', function (e) {
        e.preventDefault();

        const productId = $(this).data('product-id_v2');
        const authCheckUrl = $(this).data('auth-check-url');
        const addCartUrl = $(this).data('add-cart-url');
        const productTypeId = null;

        // Recopilar las opciones seleccionadas
        const options = {};

        // Función para mostrar mensajes de error
        function showError(message) {
            toastr.error(message, 'Error', {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: "2000",
                extendedTimeOut: "1000",
            });
        }

        // Verificar autenticación
        $.ajax({
            url: authCheckUrl,
            type: "GET",
            success: function (response) {
                // Obtener el carrito actual de localStorage
                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                // Determinar el user_id en función de la autenticación
                let userId = response.authenticated ? response.user_id : null;

                if (response.authenticated) {
                    cart = cart.map(item => ({
                        ...item,
                        user_id: userId
                    }));
                }

                // Buscar si el producto ya está en el carrito
                let existingProduct = cart.find(item =>
                    item.product_id === productId &&
                    item.product_type_id === productTypeId &&
                    JSON.stringify(item.options) === JSON.stringify(options)
                );

                if (existingProduct) {
                    // Si el producto ya existe, incrementar la cantidad
                    existingProduct.quantity += 1;
                } else {
                    // Si el producto no existe, agregarlo como un nuevo elemento
                    cart.push({
                        product_id: productId,
                        product_type_id: productTypeId,
                        options: options,
                        quantity: 1,
                        user_id: userId, // Añadir el user_id
                        custom: false,
                        reward: false,
                        total: 0,
                        cart_index: generateUUID()
                    });
                }

                // Guardar el carrito actualizado en localStorage
                localStorage.setItem('cart', JSON.stringify(cart));

                // Actualizar la cantidad del carrito
                updateCartQuantity();

                toastr.success("Producto agregado al carrito.", "Éxito",  {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: "2000",
                    extendedTimeOut: "1000",
                });
            },
            error: function (error) {
                console.error("Error al verificar la autenticación:", error);
            }
        });
    });

    // Manejar cambios en checkboxes
    $(".option-container").on("change", ".form-check-input[type='checkbox'], .form-check-input[type='radio']", function () {
        console.log("Entre");
        const $container = $(this).closest(".option-container"); // Encontrar el contenedor de la opción
        const maxQuantity = parseInt($container.data("quantity"), 10);  // Obtener la cantidad máxima permitida
        const checkedCount = $container.find(".form-check-input:checked").length;  // Contar checkboxes seleccionados
        let totalPrice = parseFloat($("#product-price").data("base-price")); // Precio base inicial
        //console.log(totalPrice);
        let totalRealPrice = parseFloat($("#product-price-real").data("real-base-price")) || 0; // Precio real base inicial
        //console.log(totalRealPrice);
        //console.log(`Seleccionados: ${checkedCount}, Máximo permitido: ${maxQuantity}`);

        // Verificar si se ha excedido la cantidad permitida
        if (checkedCount > maxQuantity) {
            $(this).prop("checked", false);  // Desmarcar el checkbox que se acaba de seleccionar
            toastr.error(`Solo puedes seleccionar hasta ${maxQuantity} opciones.`, 'Error',
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

        // Recalcular el precio total
        totalRealPrice = parseFloat($("#product-price-real").data("real-base-price")) || 0; // Reiniciar precio real total
        //console.log(totalRealPrice);
        $(".form-check-input:checked").each(function () {
            console.log($(this));
            totalPrice += parseFloat($(this).data("selection_price")) || 0; // Sumar precio adicional
            totalRealPrice += parseFloat($(this).data("selection_product_price")) || 0; // Sumar precio real adicional

        });

        updatePrices();
        //console.log("totalPrice " +totalPrice);
        //console.log("totalPrice " +totalRealPrice);
        // Actualizar el precio mostrado
        //$("#product-price, #product-price-mobile").text(totalPrice.toFixed(2));
        //$priceTotal = totalPrice;
        $("#product-price-real").text("Precio normal: S/. "+totalRealPrice.toFixed(2)); // Actualizar el precio real
        //console.log(totalRealPrice.toFixed(2));
    });

    function updateCartQuantity() {
        const authCheckUrl = '/auth/check'; // URL para verificar autenticación

        $.ajax({
            url: authCheckUrl,
            type: "GET",
            success: function (response) {

                // Si no está autenticado, obtener la cantidad desde localStorage
                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                // Contar el número de productos únicos
                let totalItems = cart.length;

                // Actualizar el contenido del span
                $("#quantityCart").html(`(${totalItems})`);
                $("#quantityCart2").html(`(${totalItems})`);

                // Variables para el parpadeo
                let blinkCount = 0;
                let blinkInterval = setInterval(function() {
                    if (blinkCount < 5) { // Repetir 5 veces (azul, rojo, azul, rojo, azul)
                        if (blinkCount % 2 === 0) {
                            $("#cartButton").css("background-color", "red");
                        } else {
                            $("#cartButton").css("background-color", "#007bff"); // Azul
                        }
                        blinkCount++;
                    } else {
                        clearInterval(blinkInterval); // Detener el intervalo después de 5 cambios
                        // Asegurarse de que el color final sea azul
                        $("#cartButton").css("background-color", "#007bff");
                    }
                }, 300); // Cambiar cada 300ms (puedes ajustar el tiempo según prefieras)


            },
            error: function (error) {
                console.error("Error al verificar autenticación:", error);
                $("#quantityCart").html(`(0)`);
            }
        });
    }

    function generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
});
