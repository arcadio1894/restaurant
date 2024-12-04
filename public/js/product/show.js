$(document).ready(function () {

    $('#pizza-type-select').on('change', function () {
        let selectedPrice = parseFloat($(this).find(':selected').data('price'))
        $('#product-price').text(selectedPrice.toFixed(2));
    });

    /*$('#add-to-cart-btn').on('click', function (e) {
        e.preventDefault();

        const productId = $(this).data('product-id');
        const authCheckUrl = $(this).data('auth-check-url');
        const addCartUrl = $(this).data('add-cart-url');
        const productTypeId = $('#pizza-type-select').val(); // Obtener el tipo de pizza seleccionado

        // Recopilar las opciones seleccionadas
        const options = {};
        let valid = true;

        $('.option-container').each(function () {
            const $container = $(this);
            const optionId = $container.data('option-id');
            const optionType = $container.data('type');
            const maxQuantity = $container.data('quantity');
            const optionDescription = $container.parent().children('strong').text();
            console.log( optionDescription );

            if (optionType === 'checkbox') {
                // Obtener checkboxes seleccionados
                const selected = $container.find('.form-check-input:checked').map(function () {
                    return $(this).val();
                }).get();

                if (selected.length === 0) {
                    toastr.error(`Debes seleccionar al menos una opción para: ${optionDescription}.`, 'Error',
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
                    //alert(`Debes seleccionar al menos una opción para ${$container.find('strong').text()}.`);
                    valid = false;
                    return false; // Detener iteración
                }

                if (selected.length > maxQuantity) {
                    toastr.error(`Has seleccionado más opciones de las permitidas para: ${optionDescription}.`, 'Error',
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
                    //alert(`Has seleccionado más opciones de las permitidas para ${$container.find('strong').text()}.`);
                    valid = false;
                    return false; // Detener iteración
                }

                options[optionId] = selected;
            } else if (optionType === 'radio') {
                // Obtener radio seleccionado
                const selected = $container.find('.form-check-input:checked').val();

                if (!selected) {
                    toastr.error(`Debes seleccionar una opción para: ${optionDescription}.`, 'Error',
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
                    //alert(`Debes seleccionar una opción para ${$container.find('strong').text()}.`);
                    valid = false;
                    return false; // Detener iteración
                }

                options[optionId] = selected;
            } /!*else if (optionType === 'select') {
                // Obtener valor seleccionado del select
                const selected = $container.find('.form-select').val();

                if (!selected) {
                    toastr.error(`Debes seleccionar una opción para ${optionDescription}.`, 'Error',
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
                    //alert(`Debes seleccionar una opción para ${$container.find('strong').text()}.`);
                    valid = false;
                    return false; // Detener iteración
                }

                options[optionId] = selected;
            }*!/
        });

        if (!valid) {
            return; // No continuar si las validaciones fallan
        }

        console.log(options);

        // Verificar autenticación
        $.ajax({
            url: authCheckUrl,
            type: "GET",
            success: function (response) {
                if (response.authenticated) {
                    // Usuario autenticado, agregar al carrito
                    addToCart(productId, productTypeId, options, addCartUrl);
                } else {
                    // Redirigir al login
                    window.location.href = `/login?redirect_to=producto/${productId}`;
                }
            },
            error: function (error) {
                console.error("Error al verificar la autenticación:", error);
            }
        });
    });

    function addToCart(productId, productTypeId, options, url) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: url,
            type: "POST",
            data: {
                product_id: productId,
                product_type_id: productTypeId, // Enviar el tipo de producto
                options: options, // Enviar las opciones seleccionadas
                _token: csrfToken
            },
            success: function (data) {
                // Redirigir al carrito
                window.location.href = data.redirect;
            },
            error: function (error) {
                console.error("Error al agregar al carrito:", error);
            }
        });
    }*/
    $('#add-to-cart-btn').on('click', function (e) {
        e.preventDefault();

        const productId = $(this).data('product-id');
        const authCheckUrl = $(this).data('auth-check-url');
        const addCartUrl = $(this).data('add-cart-url');
        const productTypeId = $('#pizza-type-select').val(); // Obtener el tipo de pizza seleccionado

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
                    return $(this).val();
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
            }
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
                if (response.authenticated) {
                    // Usuario autenticado, agregar al carrito
                    addToCart(productId, productTypeId, options, addCartUrl);
                } else {
                    // Redirigir al login
                    window.location.href = `/login?redirect_to=producto/${productId}`;
                }
            },
            error: function (error) {
                console.error("Error al verificar la autenticación:", error);
            }
        });
    });

    // Función para agregar al carrito
    function addToCart(productId, productTypeId, options, url) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: url,
            type: "POST",
            data: {
                product_id: productId,
                product_type_id: productTypeId,
                options: options, // Incluir las opciones seleccionadas
                _token: csrfToken
            },
            success: function (data) {
                // Redirigir al carrito
                window.location.href = data.redirect;
            },
            error: function (error) {
                console.error("Error al agregar al carrito:", error);
            }
        });
    }

    /*$('#add-to-cart-btn').on('click', function (e) {
        e.preventDefault();

        const productId = $(this).data('product-id');
        const authCheckUrl = $(this).data('auth-check-url');
        const addCartUrl = $(this).data('add-cart-url');
        const productTypeId = $('#pizza-type-select').val(); // Obtener el tipo de pizza seleccionado

        // Verificar autenticación
        $.ajax({
            url: authCheckUrl,
            type: "GET",
            success: function (response) {
                if (response.authenticated) {
                    // Usuario autenticado, agregar al carrito
                    addToCart(productId, productTypeId, addCartUrl);
                } else {
                    // Redirigir al login
                    window.location.href = `/login?redirect_to=producto/${productId}`;
                }
            },
            error: function (error) {
                console.error("Error al verificar la autenticación:", error);
            }
        });
    });

    function addToCart(productId, productTypeId, url) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: url,
            type: "POST",
            data: {
                product_id: productId,
                product_type_id: productTypeId, // Enviar el tipo de producto
                _token: csrfToken
            },
            success: function (data) {
                // Redirigir al carrito
                window.location.href = data.redirect;
            },
            error: function (error) {
                console.error("Error al agregar al carrito:", error);
            }
        });
    }*/

    // Manejar cambios en checkboxes
    $(".option-container").on("change", ".form-check-input[type='checkbox']", function () {
        const $container = $(this).closest(".option-container"); // Encontrar el contenedor de la opción
        const maxQuantity = parseInt($container.data("quantity"), 10);  // Obtener la cantidad máxima permitida
        const checkedCount = $container.find(".form-check-input:checked").length;  // Contar checkboxes seleccionados

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
    });

});