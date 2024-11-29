/*const mp = new MercadoPago('TEST-43619ea9-0d2d-4976-afba-ba9a8f261549', { locale: 'es-PE', debug: true }); // Reemplaza con tu llave pública de Mercado Pago

const cardForm = mp.cardForm({
    amount: '1.00', // Cambia por el monto de la transacción
    autoMount: true,
    form: {
        id: 'checkoutForm', // Este ID debe coincidir con el del formulario
        cardholderName: { id: 'cardholderName', placeholder: 'Nombre del titular' },
        cardholderEmail: { id: 'cardholderEmail', placeholder: 'Correo electrónico' },
        cardNumber: { id: 'cardNumber', placeholder: 'Número de tarjeta' },
        cardExpirationMonth: { id: 'cardExpirationMonth', placeholder: 'MM' },
        cardExpirationYear: { id: 'cardExpirationYear', placeholder: 'AA' },
        securityCode: { id: 'securityCode', placeholder: 'CVV' },
        installments: { id: 'installments', placeholder: 'Cuotas' },
        issuer: { id: 'issuer', placeholder: 'Banco' },
        identificationType: { id: 'identificationType', placeholder: 'Tipo de documento' },
        identificationNumber: { id: 'identificationNumber', placeholder: 'Número de documento' }
    },
    callbacks: {
        onFormMounted: error => {
            if (error) console.warn('Error al montar formulario:', error);
        },
        onSubmit: event => {
            event.preventDefault();
            const formData = cardForm.getCardFormData();
            console.log(formData);

            if (!formData.token) {
                toastr.error('No se pudo generar el token. Verifica los datos ingresados.', 'Error');
                return;
            }

            // Envía los datos al backend
            submitFormAjax({
                token: formData.token,
                installments: formData.installments || '1',
                issuerId: formData.issuerId || 'default',
                paymentMethodId: formData.paymentMethodId
            });
        }
    }
});*/

$(document).ready(function() {

    // Ocultamos inicialmente el li del código de promoción
    $('#info_code').addClass('hidden'); // Ocultar

    // Acción al presionar el botón "Aplicar"
    $('#btn-promo_code').click(function () {
        $('#info_code').addClass('hidden'); // Ocultar
        $('#coupon_name').val(""); // Limpiar el campo del nombre del cupón
        const promoCode = $('#promo_code').val();
        const cartId = $('input[name="cart_id"]').val(); // Obtener cart_id
        const district = $('#district').val(); // Obtener el distrito elegido (ajustar el selector si es diferente)

        /*if (promoCode.trim() === '') {
            toastr.error('Por favor, ingrese un código de promoción.', 'Error',
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
            return;
        }*/

        // Realizamos la solicitud al servidor para validar el código
        $.get('/apply-coupon', { code: promoCode, cart_id: cartId, district: district }, function (response) {
            if (response.success) {
                // Si el código es válido, actualizamos los elementos
                $('#info_code').removeClass('hidden'); // Mostrar
                $('#name_code').text(response.code_name); // Nombre del código
                $('#amount_code').text(response.discount_display); // Monto de descuento
                $('#total_amount').text(`S/ ${response.new_total}`); // Nuevo total con envío y descuento
                $('#coupon_name').val(response.code_name); // Nombre del código
                toastr.success(response.message, 'Éxito',
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
            } else {
                // Si hay error, ocultamos el código y restauramos el total con envío
                $('#info_code').addClass('hidden');
                $('#total_amount').text(`S/ ${response.new_total}`); // Devolver el total con envío sin descuento
                $('#coupon_name').val(""); // Limpiar el nombre del cupón
                toastr.error(response.message, 'Error',
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
        }).fail(function () {
            toastr.error('Ocurrió un error al procesar el código de promoción.', 'Error',
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
        });
    });

    $('.payment-method').on('change', function() {
        let selectedMethod = $(this).data('code');

        // Ocultar ambas secciones al cambiar la selección
        $('#cash-section, #yape-section').hide();

        // Mostrar la sección correspondiente al método de pago
        if (selectedMethod === 'efectivo') {
            $('#cash-section').show();
            $('#cashAmount').val("");
        } else if (selectedMethod === 'yape_plin') {
            $('#yape-section').show();
            $('#operationCode').val("");
        }  /*else if (selectedMethod === 'mercado_pago') {
            $('#mercado_pago-section').show();
            $('#cardExpirationMonth').val("");
            $('#cardExpirationYear').val("");
            $('#securityCode').val("");
            $('#cardholderName').val("");
            $('#cardholderEmail').val("");
        }*/
    });
    // Al hacer clic en el botón de enviar
    $('#btn-submit').on('click', function(event) {
        event.preventDefault();
        $('#btn-submit').attr("disabled", true);
        // Llamar al método para verificar horario de atención
        $.ajax({
            url: '/api/business-hours', // Cambia la ruta si es necesario
            method: 'GET',
            success: function (response) {
                if (!response.is_open) {
                    // Mostrar mensaje si el negocio está cerrado
                    $('#business-message').text(response.message);
                    $('#business-status').fadeIn();
                    $('#btn-submit').attr("disabled", true); // Deshabilitar el botón
                    return; // Detener el flujo del código
                } else {
                    // Si el negocio está abierto, continuar con el flujo
                    $('#btn-submit').attr("disabled", false); // Habilitar el botón
                    procesarFormulario(); // Llamar a la función para validar y enviar el formulario
                }
            },
            error: function () {
                console.error('No se pudo verificar el horario de atención.');
                $('#btn-submit').attr("disabled", false); // Permitir envío si ocurre un error inesperado
            }
        });

        // Cerrar el mensaje al hacer clic en el botón "X"
        $('#close-business-status').on('click', function () {
            $('#business-status').fadeOut();
            $('#btn-submit').attr("disabled", false); // Habilitar el botón de envío
        });

    });

    $('#district').on('change', function () {
        const districtId = $(this).val();
        const cartId = $('input[name="cart_id"]').val();
        const couponName = $('#coupon_name').val();

        if (!districtId) {
            // Caso cuando se selecciona "Ninguno"
            $('#info_shipping').addClass('hidden'); // Ocultar costo de envío
            const defaultShippingCost = 0;

            $.ajax({
                url: '/checkout/shipping',
                method: 'POST',
                data: {
                    district_id: null, // Enviar nulo al backend
                    cart_id: cartId,
                    coupon_name: couponName,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.success) {
                        // Restablecer el costo de envío
                        $('#amount_shipping').text(`+ S/ ${defaultShippingCost.toFixed(2)}`);
                        // Actualizar el total
                        $('#total_amount').text(`S/ ${data.new_total.toFixed(2)}`);
                    }
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Error inesperado.', 'Error');
                }
            });
        } else {
            // Caso normal
            $('#info_shipping').addClass('hidden'); // Ocultar mientras carga
            $.ajax({
                url: '/checkout/shipping',
                method: 'POST',
                data: {
                    district_id: districtId,
                    cart_id: cartId,
                    coupon_name: couponName,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.success) {
                        $('#info_shipping').removeClass('hidden');
                        $('#amount_shipping').text(`+ S/ ${data.shipping_cost.toFixed(2)}`);
                        $('#total_amount').text(`S/ ${data.new_total.toFixed(2)}`);
                    } else {
                        toastr.error(data.message, 'Error');
                    }
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Error inesperado.', 'Error');
                }
            });
        }
    });
});

function procesarFormulario() {
    // Validar el formulario
    let form = $('#checkoutForm');
    if (form[0].checkValidity() === false) {
        event.stopPropagation();
        form.addClass('was-validated');
    } else {
        // Verificar el método de pago seleccionado y validar campos adicionales
        let selectedMethod = $("input[name='paymentMethod']:checked").attr('id');

        if (selectedMethod === 'method_pos') {
            // Enviar directamente si es "POS"
            submitFormAjax();
        } else if (selectedMethod === 'method_efectivo') {
            // Validar si el monto ingresado es mayor a cero
            let cashAmount = $('#cashAmount').val();
            console.log(cashAmount);
            if (parseFloat(cashAmount) > 0 || cashAmount !== "" ) {
                console.log('Enviamos formulario');
                submitFormAjax({ cashAmount: cashAmount });
            } else {
                //alert('Por favor, ingrese un monto válido en efectivo.');
                toastr.error('Por favor, ingrese un monto válido en efectivo.', 'Error',
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
        } else if (selectedMethod === 'method_yape_plin') {
            // Validar si el código de operación está presente
            let operationCode = $('#operationCode').val();
            if (operationCode) {
                submitFormAjax({ operationCode: operationCode });
            } else {
                //alert('Por favor, ingrese el código de operación para Yape o Plin.');
                toastr.error('Por favor, ingrese el código de operación para Yape o Plin.', 'Error',
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
        } else if (selectedMethod === 'method_mercado_pago') {

            /*// Validar si los campos necesarios tienen valores
            const installments = $('#installments').val();
            const issuer = $('#issuer').val();

            if (!installments) {
                $('#installments').val('1'); // Valor por defecto de una cuota
            }

            if (!issuer) {
                $('#issuer').val('default'); // Asegúrate de usar un ID válido para el banco
            }

            // Extrae los datos del formulario de tarjeta
            console.log(cardForm);
            const { token, issuerId, paymentMethodId } = cardForm.getCardFormData();
            console.log({ token, issuerId, paymentMethodId });

            if (!token) {
                toastr.error('No se pudo generar el token. Verifica los datos ingresados Erorr.', 'Error');
                return;
            }

            if (!issuerId) {
                toastr.error('Por favor selecciona el banco.', 'Error');
                return;
            }

            if (!$('#installments').val()) {
                toastr.error('Por favor selecciona las cuotas.', 'Error');
                return;
            }

            // Envía los datos al backend
            submitFormAjax({
                token: token,
                installments: $('#installments').val(),
                issuerId: issuerId,
                paymentMethodId: paymentMethodId
            });*/
            /*$.ajax({
                url: '/crear-preferencia',
                method: 'POST',
                success: function(data) {
                    const mp = new MercadoPago('APP_USR-39c9eccc-78c3-42fc-b730-f1ddab6d5f39', {
                        locale: 'es-PE'
                    });
                    mp.checkout({
                        preference: {
                            id: data.id
                        },
                        autoOpen: true // Abre el checkout automáticamente
                    });
                },
                error: function(error) {
                    console.error('Error al crear la preferencia:', error);
                }
            });*/

        }
    }
}

function submitFormAjax(extraData = {}) {
    let formData = $('#checkoutForm').serializeArray(); // Serializa los datos del formulario
    // Convertimos el formData a un objeto para poder agregar extraData
    let dataObj = {};
    formData.forEach(item => {
        dataObj[item.name] = item.value;
    });

    // Añadir datos adicionales al objeto de datos
    $.extend(dataObj, extraData);

    $.ajax({
        url: '/checkout/pagar', // Ruta configurada en Laravel
        type: 'POST',
        data: dataObj,
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        success: function(data) {
            if (data.success) {
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
                    //location.reload();
                    window.location.href = data.redirect_url;
                }, 2000 )
            } else {

                toastr.error('Hubo un problema con tu compra. Intenta de nuevo.', 'Error',
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
                $('#btn-submit').attr("disabled", false);
            }
        },
        error: function(data) {
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

            $('#btn-submit').attr("disabled", false);
        }
    });
}