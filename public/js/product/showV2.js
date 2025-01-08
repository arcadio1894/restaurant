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

    $('#pizza-type-select').on('change', function () {
        let selectedPrice = parseFloat($(this).find(':selected').data('price'))
        $('#product-price').text(selectedPrice.toFixed(2));
    });

    $('#add-to-cart-btn').on('click', function (e) {
        e.preventDefault();

        const productId = $(this).data('product-id_v2');
        const authCheckUrl = $(this).data('auth-check-url');
        const addCartUrl = $(this).data('add-cart-url');
        let productTypeId = $('#pizza-type-select').val(); // Obtener el tipo de pizza seleccionado
        const category_id = $(this).data('product-category');

        if ( productTypeId == null )
        {
            productTypeId = null;
        }

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
            } /*else if (optionType === 'radio') {
                // Obtener radio seleccionado
                const selected = $container.find('.form-check-input:checked').val();

                if (!selected) {
                    showError(`Debes seleccionar una opción para: ${optionDescription}.`);
                    valid = false;
                    return false; // Detener iteración
                }

                options[optionId] = selected;
            } else if (optionType === 'select') {
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

        console.log("Opciones seleccionadas:", options);

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
                        total: 0
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
                        total: 0
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
    $(".option-container").on("change", ".form-check-input[type='checkbox']", function () {
        const $container = $(this).closest(".option-container"); // Encontrar el contenedor de la opción
        const maxQuantity = parseInt($container.data("quantity"), 10);  // Obtener la cantidad máxima permitida
        const checkedCount = $container.find(".form-check-input:checked").length;  // Contar checkboxes seleccionados
        let totalPrice = parseFloat($("#product-price").data("base-price")); // Precio base inicial

        let totalRealPrice = parseFloat($("#product-price-real").data("real-base-price")) || 0; // Precio real base inicial

        console.log(`Seleccionados: ${checkedCount}, Máximo permitido: ${maxQuantity}`);

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

        $(".form-check-input:checked").each(function () {
            totalPrice += parseFloat($(this).data("selection_price")) || 0; // Sumar precio adicional
            totalRealPrice += parseFloat($(this).data("selection_product_price")) || 0; // Sumar precio real adicional

        });

        // Actualizar el precio mostrado
        $("#product-price").text(totalPrice.toFixed(2));
        $("#product-price-real").text("Precio normal: S/. "+totalRealPrice.toFixed(2)); // Actualizar el precio real

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

            },
            error: function (error) {
                console.error("Error al verificar autenticación:", error);
                $("#quantityCart").html(`(0)`);
            }
        });
    }
});