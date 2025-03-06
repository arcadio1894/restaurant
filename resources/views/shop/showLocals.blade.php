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
                        <button type="button" class="btn btn-primary mb-3" id="selectAddress">Seleccionar esta dirección</button>
                    </div>
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
                <a href="{{ route('cart.checkout') }}" class="btn btn-primary mb-3">Ir a pagar</a>
            </div>


        </div>
    </section>

@endsection

@section('scripts')
    <script src="{{ asset('js/shop/showLocals.js') }}?v={{ time() }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBG5pTai_rF775fdoi3-9X8K462l1-aMo&libraries=places&callback=initAutocomplete" async defer></script>

@endsection
