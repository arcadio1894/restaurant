@extends('layouts.app')

@section('menu-active', 'active')

@section('text-header')
    <h2 class="pt-5">
        Completa tu compra
    </h2>

@endsection

@section('styles')
    <link href="{{ asset('css/checkout/form-validation.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .hidden {
            display: none !important; /* Asegura que sobreescriba cualquier estilo */
        }
    </style>
@endsection

@section('content')
    <div id="auth-status" data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"></div>
    <div class="container formulario">
        <div class="row mt-4 mb-4 ">
            <div class="col-md-6 order-md-2">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Tu pedido</span>
                    {{--<span class="badge badge-secondary badge-pill">{{ count($cart->details) }}</span>--}}
                </h4>
                <ul class="list-group mb-3">

                    <div id="body-items">
                        <div id="loading-indicator" style="display: none;">
                            <p>Cargando...</p>
                        </div>
                    </div>
                    {{--@foreach( $cart->details as $detail )
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <img src="{{ asset('images/products/'.$detail->product->image) }}" alt="{{ $detail->product->full_name }}" class="img-thumbnail mr-2" style="width: 50px; height: 50px; object-fit: cover;">
                        </div>
                        <div>
                            {{ $detail->product->full_name }} x{{ $detail->quantity }}
                        </div>
                        <span class="text-muted">S/ {{ number_format($detail->subtotal, 2, '.', '') }}</span>
                    </li>
                    @endforeach--}}
                    <li id="info_code" class="list-group-item d-flex justify-content-between bg-light hidden" style="display: none;">
                        <div class="text-success">
                            <h6 class="my-0">Código de promoción</h6>
                            <small id="name_code">EXAMPLECODE</small>
                        </div>
                        <span class="text-success" id="amount_code">-$5</span>
                    </li>
                    <li id="info_shipping" class="list-group-item d-flex justify-content-between bg-light hidden">
                        <div class="text-danger">
                            <h6 class="my-0">Costo de Envío</h6>
                        </div>
                        <span class="text-danger" id="amount_shipping">+$5</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total </span>
                        <strong id="total_amount">{{--S/ {{ number_format($cart->total_cart, 2, '.', '') }}--}}</strong>
                    </li>
                </ul>

                <div class="input-group">
                    <input type="text" class="form-control" id="promo_code" placeholder="Código de Promocíon">
                    <div class="input-group-append">
                        <button type="button" id="btn-promo_code" class="btn btn-secondary">Aplicar</button>
                    </div>
                </div>
                <a href="{{ route('home') }}" class="btn btn-light w-100 border mt-2"> Volver al menú </a>
            </div>
            <div class="col-md-6 order-md-1">
                <h4 class="mb-3">Dirección de envío</h4>
                <form class="needs-validation" novalidate id="checkoutForm">
                    @csrf
                    <input type="hidden" name="cart_id" value="{{--{{ $cart->id }}--}}">
                    <input type="hidden" name="coupon_name" id="coupon_name" value="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName">Nombres</label>
                            <input type="text" class="form-control" id="firstName" name="first_name" placeholder=""
                                   value="{{ $defaultAddress ? $defaultAddress->first_name : '' }}" required>
                            <div class="invalid-feedback">
                                Sus nombres son obligatorios.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName">Apellidos</label>
                            <input type="text" class="form-control" id="lastName" name="last_name" placeholder=""
                                   value="{{ $defaultAddress ? $defaultAddress->last_name : '' }}" required>
                            <div class="invalid-feedback">
                                Sus apellidos son obligatorios.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="username">Teléfono</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder=""
                                   value="{{ $defaultAddress ? $defaultAddress->phone : '' }}" required>
                            <div class="invalid-feedback" style="width: 100%;">
                                Su teléfono es obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email">Correo Eletrónico</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope-open"></i></span>
                            </div>
                            <input type="email" class="form-control" id="email" name="email" placeholder=""
                                   value="{{ auth()->check() ? ($defaultAddress ? ($defaultAddress->email ?: auth()->user()->email) : auth()->user()->email) : '' }}" required>
                            <div class="invalid-feedback" style="width: 100%;">
                                Su email es obligatorio.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="district">Seleccione su Distrito</label>
                        <select class="form-control" name="district" id="district" required>
                            <option value="" selected>Ninguno</option>
                            @foreach( $districts as $district )
                            <option value="{{ $district->id }}" data-shipping_cost="{{ $district->shipping_cost }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" style="width: 100%;">
                            Su distrito es obligatorio.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address">Dirección</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Av. Larco 333"
                               value="{{ $defaultAddress ? $defaultAddress->address_line : '' }}" required>
                        <button type="button" class="btn btn-link" id="btn-selectAddress">
                            Encuentra tu dirección
                        </button>
                        <div class="invalid-feedback">
                            Por favor ingrese su dirección de envío.
                        </div>
                        <input type="hidden" id="latitude">
                        <input type="hidden" id="longitude">
                    </div>

                    <div class="mb-3">
                        <label for="address2">Referencia <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control" id="reference" name="reference" placeholder="Al costado de Ittsa"
                               value="{{ $defaultAddress ? $defaultAddress->reference : '' }}">
                    </div>

                    {{--<hr class="mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="save-info" name="save-info" checked>
                        <label class="custom-control-label" for="save-info">Guardar esta información.</label>
                    </div>--}}
                    <hr class="mb-4">

                    <h4 class="mb-3">Método de pago</h4>

                    <div class="d-block my-3">
                        @foreach( $payment_methods as $method )
                        <div class="custom-control custom-radio">
                            <input id="method_{{$method->code}}" name="paymentMethod" type="radio" value="{{$method->id}}" class="custom-control-input payment-method" required data-code="{{ $method->code }}">
                            <label class="custom-control-label" for="method_{{$method->code}}">{{$method->name}}</label>
                        </div>
                        @endforeach
                    </div>
                    <!-- Sección para método de pago en efectivo -->
                    <div id="cash-section" style="display: none; margin-top: 15px;">
                        <label for="cashAmount">Monto con el que paga</label>
                        <input type="number" class="form-control" id="cashAmount" placeholder="Ingrese monto en efectivo">
                    </div>

                    <!-- Sección para método de pago Yape/Plin -->
                    <div id="yape-section" style="display: none; margin-top: 15px;">
                        <p>Escanee el código QR para pagar con Yape o Plin:</p>
                        <div class="text-center">
                            <img src="{{ asset('images/checkout/qr_yape.jpg') }}" alt="QR para Yape/Plin" style="width: 200px;">
                        </div>
                        <br>
                        <label for="operationCode">Código de operación</label>
                        <input type="text" class="form-control" id="operationCode" placeholder="Ingrese el código de operación">
                    </div>

                    <!-- Sección para método de pago Mercado Pago -->
                    {{--<div id="mercado_pago-section" style="display: none; margin-top: 15px;">

                            <input type="hidden" name="cart_id" value="{{ $cart->id }}">
                            <!-- Información del titular de la tarjeta -->
                            <div class="mb-3">
                                <label for="address">Número de la tarjeta</label>
                                <input type="text" class="form-control" name="cardNumber" id="cardNumber" placeholder="Número de la tarjeta" data-checkout="cardNumber" required />
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="cardExpirationMonth">Mes de expiración</label>
                                        <input type="text" class="form-control" name="cardExpirationMonth" id="cardExpirationMonth" placeholder="Mes de expiración" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="cardExpirationYear">Año de expiración</label>
                                        <input type="text" class="form-control" name="cardExpirationYear" id="cardExpirationYear" placeholder="Año de expiración" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="securityCode">Código de seguridad</label>
                                        <input type="text" class="form-control" name="securityCode" id="securityCode" placeholder="Código de seguridad" required />
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="cardholderName">Nombre del titular</label>
                                <input type="text" class="form-control" name="cardholderName" id="cardholderName" placeholder="Nombre del titular" required />
                            </div>
                            <div class="mb-3">
                                <label for="cardholderEmail">Email</label>
                                <input type="email" class="form-control" name="cardholderEmail" id="cardholderEmail" placeholder="Email" required />
                            </div>

                        <div class="mb-3">
                            <label for="identificationType">Tipo de documento</label>
                            <select class="form-control" name="identificationType" id="identificationType">
                                <option value="DNI" selected>DNI</option>
                                <option value="RUC">RUC</option>
                                <option value="CE">Carné de extranjería</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="identificationNumber">Número de documento</label>
                            <input type="text" class="form-control" name="identificationNumber" id="identificationNumber" placeholder="Número de documento" required />
                        </div>

                            <!-- Campo para cuotas -->
                            <div class="mb-3">
                                <label for="installments">Cuotas</label>
                                <select class="form-control" name="installments" id="installments" >
                                    <option value="">Seleccione cuotas</option>
                                </select>
                            </div>

                            <!-- Campo para banco emisor -->
                            <div class="mb-3">
                                <label for="issuer">Banco Emisor</label>
                                <select class="form-control" name="issuer" id="issuer" >
                                    <option value="">Seleccione el banco</option>
                                </select>
                            </div>


                            <!-- Token de tarjeta -->
                            <input type="hidden" name="token" id="token" />

                    </div>--}}
                    <hr class="mb-4">
                    <button class="btn btn-primary btn-lg btn-block button-submit" type="button" id="btn-submit">COMPRAR</button>
                </form>
            </div>
        </div>
    </div>

    <template id="template-detail">
        <li class="list-group-item d-flex justify-content-between lh-condensed">
            <div>
                <img data-image src="{{--{{ asset('images/products/'.$detail->product->image) }}--}}" alt="{{--{{ $detail->product->full_name }}--}}" class="img-thumbnail mr-2" style="width: 50px; height: 50px; object-fit: cover;">
            </div>
            <div data-full_name>
                {{--{{ $detail->product->full_name }} x{{ $detail->quantity }}--}}
            </div>
            <span class="text-muted" data-subtotal>{{--S/ {{ number_format($detail->subtotal, 2, '.', '') }}--}}</span>
        </li>
    </template>

    <!-- Modal de atención -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">¡Atención!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Para aplicar cupones, debes iniciar sesión en tu cuenta.</p>
                    <p>¿Aún no tienes una cuenta? ¡Regístrate ahora y aprovecha nuestras promociones exclusivas!</p>
                </div>
                <div class="modal-footer">
                    <button id="btn-login" class="btn btn-primary">Iniciar Sesión</button>
                    <button id="btn-register" class="btn btn-success">Registrarse</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifyModalLabel">¡Atención! 📢</h5>
                    <button type="button" class="close closeModalVerify" ><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Antes de finalizar tu compra, asegúrate de que los datos en los campos de celular y correo electrónico estén correctos.</p>
                    <p>📱: <span id="showPhone"></span></p>
                    <p>✉: <span id="showEmail"></span></p>
                    <p>✅ Estos son los medios que utilizaremos para informarte sobre el estado de tu pedido y coordinar la entrega. 🚗🍕</p>
                    <p>🔄 Verifica tus datos ahora para una experiencia sin inconvenientes.</p>
                </div>
                <div class="modal-footer">
                    <button id="btn-continue" class="btn btn-primary">Continuar</button>
                    <button id="btn-cancel" class="btn btn-secondary closeModalVerify">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapModalLabel">Encuentra tu dirección</h5>
                    <button type="button" class="close closeModalVerify" ><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <!-- Campo de búsqueda -->
                    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Escribe tu dirección...">
                    <!-- Mapa -->
                    <div id="map" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="selectAddress">Seleccionar esta dirección</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    {{--<script src="https://sdk.mercadopago.com/js/v2"></script>--}}
    {{--<script src="{{ asset('js/cart/cart.js') }}"></script>--}}
    <script src="{{ asset('js/cart/checkout3.js') }}?v={{ time() }}"></script>
    <!-- Carga la API de Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBG5pTai_rF775fdoi3-9X8K462l1-aMo&libraries=places&callback=initAutocomplete" async defer></script>

@endsection
