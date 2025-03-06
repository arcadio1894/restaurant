@extends('layouts.app')

@section('menu-active', 'active')

@section('text-header')
    <h2 class="pt-5">
        {{--Consigue el local de tu preferencia--}}
        Selecciona tu dirección
    </h2>

@endsection

@section('styles')
    <style>
        /* Asegura que las sugerencias aparezcan correctamente */
        .pac-container {
            z-index: 1051 !important; /* Mayor que el z-index del modal */
        }

        .buttonGoToCheckout {
            display: inline-block;
            padding: 8px 30px;
            background-color: #ffbe33;
            color: #ffffff;
            border-radius: 45px;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
            border: none;
        }

        .buttonGoToCheckout:hover, .buttonGoToCheckout:focus {
            text-decoration: none;
            color: #ffffff;
            background-color: #e6a92e;
        }

        /* Esconder el botón original en dispositivos móviles */
        @media (max-width: 992px) {
            .d-lg-flex {
                display: none !important;
            }
        }

        /* Div fijo para móviles */
        .mobile-fixed-cart {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1050;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            background-color: white;
            padding: 10px 15px;
            border-top: 1px solid #ddd;
        }

        .mobile-fixed-cart .btn {
            width: 100%;
            margin: 0;
        }
    </style>
@endsection

@section('content')

    <section class="bg-light my-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <!-- Campo de búsqueda -->
                    <div class="col-md-12">
                        {{--p>Ten en cuenta el <b>horario de los locales.</b> Tomamos pedidos hasta 30 min antes de su hora de cierre</p>--}}
                        <p>¡Te llevamos la pizza hasta tu puerta! Ingresa tu dirección o búscala en el mapa para que podamos entregarte tu pedido lo más rápido posible.</p>
                        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Escribe tu dirección...">
                        {{--<button type="button" class="btn btn-primary mb-3" id="selectAddress">Seleccionar esta dirección</button>
                    --}}</div>
                    <input type="hidden" name="address" id="address">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div class="col-md-12" id="body-locals">

                    </div>
                </div>

                <div class="col-lg-6" id="body-map">
                    <div class="col-md-12">
                        <div id="map" style="height: 400px;"></div>
                    </div>
                </div>

            </div>
            <div class="row d-flex justify-content-center mt-5">
                <a href="#" id="btn-go_to_checkout" data-href="{{ route('cart.checkout') }}" class="mb-3 buttonGoToCheckout d-lg-flex d-none">Ir a pagar</a>
            </div>


        </div>
    </section>

    <div class="mobile-fixed-cart d-lg-none">
        <button data-href="{{ route('cart.checkout') }}" class="btn btn-danger btn-block py-3" id="go-to-checkout-btn-mobile">
            Ir a Pagar
        </button>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/shop/showLocals.js') }}?v={{ time() }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBG5pTai_rF775fdoi3-9X8K462l1-aMo&libraries=places&callback=initAutocomplete" async defer></script>

@endsection
