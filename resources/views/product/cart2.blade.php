@extends('layouts.app')

@section('menu-active', 'active')

@section('text-header')
    <h2 class="pt-5">
        Tu Carrito de compras
    </h2>

@endsection

@section('styles')
    <style>
        .icon-hover-primary:hover {
            border-color: #3b71ca !important;
            background-color: white !important;
        }

        .icon-hover-primary:hover i {
            color: #3b71ca !important;
        }
        .icon-hover-danger:hover {
            border-color: #dc4c64 !important;
            background-color: white !important;
        }

        .icon-hover-danger:hover i {
            color: #dc4c64 !important;
        }
    </style>
@endsection

@section('content')

    <section class="bg-light my-5">
        <div class="container">
            <div class="row">
                <!-- cart -->
                <div class="col-lg-9">
                    <div class="card border shadow-0">
                        <div class="m-4" id="body-items">
                            <div id="loading-indicator" style="display: none;">
                                <p>Cargando...</p>
                            </div>
                        {{--@if($cart && $cart->details->isNotEmpty())
                            <!-- cart_detail -->
                            @foreach( $cart->details as $detail )
                                <div class="row gy-3 mb-4">
                                    <div class="col-md-5">
                                        <div class="me-lg-5">
                                            <div class="d-flex">
                                                <img src="{{ asset('images/products/'.$detail->product->image) }}" class="border rounded me-3" style="width: 96px; height: 96px;" />
                                                <div class="">
                                                    <a href="#" class="nav-link">{{ $detail->product->full_name }}</a>
                                                    @if( $detail->productType != null )
                                                    <p class="text-muted ml-3 mb-0">
                                                        Tipo: {{ $detail->productType->type->name }}
                                                        @if($detail->productType->type && $detail->productType->type->size)
                                                            ( {{ $detail->productType->type->size }} )
                                                        @endif
                                                    </p>
                                                    @endif
                                                    <div class="text-muted ml-1 small">
                                                        @if($detail->options->isNotEmpty())
                                                            <ul class="mb-0 ps-3">
                                                                @foreach($detail->options as $option)
                                                                    <li>{{ $option->option->name }}
                                                                        @if($option->product)
                                                                            {{ $option->product->full_name }}
                                                                        @endif
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group mb-3" style="width: 140px;">
                                                    <button data-minus data-detail_id="{{ $detail->id }}" class="btn btn-white border border-secondary" type="button" data-mdb-ripple-color="dark">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input data-quantity data-detail_id="{{ $detail->id }}" type="text" class="form-control text-center border border-secondary" placeholder="14" aria-label="Example text with button addon" aria-describedby="button-addon1" value="{{ $detail->quantity }}"/>
                                                    <button data-plus data-detail_id="{{ $detail->id }}" class="btn btn-white border border-secondary" type="button" data-mdb-ripple-color="dark">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <text class="h6">S/ {{ $detail->subtotal }}</text> <br />
                                                @if( $detail->productType != null )
                                                    <small class="text-muted text-nowrap"> S/ {{ $detail->productType->price }} / por item </small>
                                                @else
                                                    <small class="text-muted text-nowrap"> S/ {{ $detail->product->price_default }} / por item </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="float-md-end">
                                            <a href="#!" data-material_id="{{ $detail->product->id }}" class="btn btn-light border icon-hover-primary"><i class="fas fa-heart fa-lg text-secondary"></i></a>
                                            <button type="button" data-delete_item data-detail_id="{{ $detail->id }}" class="btn btn-light border text-danger icon-hover-danger"> <i class="fas fa-trash fa-lg text-secondary"></i></button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- cart_empty -->
                            <div class="text-center py-5">
                                <h5>Tu carrito está vacío</h5>
                                <a href="{{ route('home') }}" class="btn btn-primary mt-3">Volver a la tienda</a>
                            </div>
                        @endif--}}

                        </div>
                    </div>
                    <div id="body-observations">
                        <!-- cart_observations -->
                    </div>
                    {{--@if($cart && $cart->details->isNotEmpty())--}}
                        <!-- cart_observations -->
                    {{--@endif--}}
                </div>
                <!-- cart -->


                <div class="col-lg-3" id="body-summary">
                    <!-- summary -->

                </div>

            </div>

        </div>
    </section>

    <template id="template-cart_empty">
        <div class="text-center py-5">
            <h5>Tu carrito está vacío</h5>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Volver a la tienda</a>
        </div>
    </template>

    <template id="template-cart_summary">
        <div class="card shadow-0 border">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <p class="mb-2">Sub Total:</p>
                    <p class="mb-2" id="subtotal_cart" data-subtotal_cart>{{--S/ {{ number_format($cart->subtotal_cart, 2) }}--}}</p>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="mb-2">Descuento:</p>
                    <p class="mb-2 text-success" data-discount_cart>S/ {{ number_format(0, 2) }}</p>
                </div>
                <div class="d-flex justify-content-between">
                    <p class="mb-2">IGV:</p>
                    <p class="mb-2" id="taxes_cart" data-taxes_cart>{{--S/ {{ number_format($cart->taxes_cart, 2) }}--}}</p>
                </div>
                <hr />
                <div class="d-flex justify-content-between">
                    <p class="mb-2">Precio Total:</p>
                    <p class="mb-2 fw-bold" id="total_cart" data-total_cart>{{--S/ {{ number_format($cart->total_cart, 2) }}--}}</p>
                </div>

                <div class="mt-3">
                    <a href="{{ route('cart.checkout') }}" class="btn btn-success w-100 shadow-0 mb-2"> Ir a Pagar </a>
                    @auth()
                        <a href="{{ route('home') }}" class="btn btn-light w-100 border mt-2"> Seguir comprando </a>
                    @else
                        <a href="{{ route('welcome') }}" class="btn btn-light w-100 border mt-2"> Seguir comprando </a>
                    @endif
                </div>
            </div>
        </div>
    </template>

    <template id="template-observations">
        <div class="card border shadow-0 mt-3 pt-3 pb-3">
            <div class="col-sm-12">
                <label for="observations" class="col-form-label">Ingrese alguna información de sus pedido</label>
                <button type="button" id="btn-observations" class="btn btn-outline-success btn-sm float-right"><i class="far fa-save"></i> Guardar</button>
            </div>
            <div class="col-sm-12">
                <textarea name="observations" data-cart_observations id="observations" rows="3" class="form-control"></textarea>
            </div>
        </div>
    </template>

    <template id="template-cart_detail">
        <div class="row gy-3 mb-4">
            <div class="col-md-5">
                <div class="me-lg-5">
                    <div class="d-flex">
                        <img data-image src="{{--{{ asset('images/products/'.$detail->product->image) }}--}}" class="border rounded me-3" style="width: 96px; height: 96px;" />
                        <div class="">
                            <a href="#" class="nav-link" data-product_name>{{--{{ $detail->product->full_name }}--}}</a>
                            <p class="text-muted ml-3 mb-0" data-detail_productType>
                                {{--Tipo: {{ $detail->productType->type->name }}
                                @if($detail->productType->type && $detail->productType->type->size)
                                    ( {{ $detail->productType->type->size }} )
                                @endif--}}
                            </p>
                            <div class="text-muted ml-1 small">
                                <ul class="mb-0 ps-3" data-body_options>
                                    {{--@foreach($detail->options as $option)
                                        <li>{{ $option->option->name }}
                                            @if($option->product)
                                                {{ $option->product->full_name }}
                                            @endif
                                        </li>
                                    @endforeach--}}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group mb-3" style="width: 140px;">
                            <button data-minus data-detail_id="{{--{{ $detail->id }}--}}" class="btn btn-white border border-secondary" type="button" data-mdb-ripple-color="dark">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input data-quantity data-detail_id="{{--{{ $detail->id }}--}}" type="text" class="form-control text-center border border-secondary" placeholder="14" aria-label="Example text with button addon" aria-describedby="button-addon1" value="{{--{{ $detail->quantity }}--}}" readonly/>
                            <button data-plus data-detail_id="{{--{{ $detail->id }}--}}" class="btn btn-white border border-secondary" type="button" data-mdb-ripple-color="dark">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <text class="h6" data-detail_subtotal>{{--S/ {{ $detail->subtotal }}--}}</text> <br />
                        <small class="text-muted text-nowrap" data-detail_price> {{--S/ {{ $detail->product->price_default }} / por item--}} </small>

                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="float-md-end">
                    {{--<a href="#!" data-material_id="--}}{{--{{ $detail->product->id }}--}}{{--" class="btn btn-light border icon-hover-primary"><i class="fas fa-heart fa-lg text-secondary"></i></a>--}}
                    <button type="button" data-delete_item data-detail_id="{{--{{ $detail->id }}--}}" class="btn btn-light border text-danger icon-hover-danger"> <i class="fas fa-trash fa-lg text-secondary"></i></button>
                </div>
            </div>
        </div>
    </template>

    <template id="template-option">
        <li data-option>
            {{--{{ $option->option->name }}
            @if($option->product)
                {{ $option->product->full_name }}
            @endif--}}
        </li>
    </template>
@endsection

{{--@section('content')
    <div class="container">
        <h1 class="mb-4">Carrito de Compras</h1>

        <!-- Contenedor para los productos del carrito -->
        <div id="cart-container">
            <!-- Los productos se generarán dinámicamente -->
        </div>

        <!-- Template del producto del carrito -->
        <template id="template-item">
            <div class="cart-item d-flex align-items-center mb-3 border-bottom pb-3">
                <img src="" alt="Producto" class="cart-item-image me-3" style="width: 80px; height: 80px; object-fit: cover;">
                <div class="cart-item-info">
                    <h5 class="cart-item-name"></h5>
                    <p class="cart-item-options text-muted"></p>
                    <p><strong>Precio:</strong> $<span class="cart-item-price"></span></p>
                    <p><strong>Cantidad:</strong> <span class="cart-item-quantity"></span></p>
                </div>
                <button class="btn btn-danger btn-sm ms-auto remove-item" data-product-id="">Eliminar</button>
            </div>
        </template>

        <div class="d-flex justify-content-between mt-4">
            <h3>Total: $<span id="cart-total">0.00</span></h3>
            <a href="{{ route('cart.checkout') }}" class="btn btn-success btn-lg">Ir al Checkout</a>
        </div>
    </div>
@endsection--}}

@section('scripts')
    <script src="{{ asset('js/cart/cart2.js') }}"></script>
@endsection
