
$(document).ready(function() {
    const mp = new MercadoPago('TEST-43619ea9-0d2d-4976-afba-ba9a8f261549', { locale: 'es-PE', debug: true }); // Reemplaza con tu llave pública de Mercado Pago

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
        }  else if (selectedMethod === 'mercado_pago') {
            $('#mercado_pago-section').show();
            $('#cardExpirationMonth').val("");
            $('#cardExpirationYear').val("");
            $('#securityCode').val("");
            $('#cardholderName').val("");
            $('#cardholderEmail').val("");
        }
    });
    // Al hacer clic en el botón de enviar
    $('#btn-submit').on('click', function(event) {
        event.preventDefault();

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

                if (parseFloat(cashAmount) > 0 || cashAmount !== "" ) {
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

                // Validar si los campos necesarios tienen valores
                const installments = $('#installments').val();
                const issuer = $('#issuer').val();

                if (!installments) {
                    $('#installments').val('1'); // Valor por defecto de una cuota
                }

                if (!issuer) {
                    $('#issuer').val('default'); // Asegúrate de usar un ID válido para el banco
                }

                // Extrae los datos del formulario de tarjeta
                console.log(cardForm.getCardFormData());
                const { token, issuerId, paymentMethodId } = cardForm.getCardFormData();
                console.log({ token, issuerId, paymentMethodId });

                if (!token) {
                    toastr.error('No se pudo generar el token. Verifica los datos ingresados.', 'Error');
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
                });

            }
        }
    });
});

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

        }
    });
}