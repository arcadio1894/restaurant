@extends('layouts.app')

@section('menu-active', 'active')

@section('text-header')
    Tu Carrito de compras
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
                        <div class="m-4">
                        @if($cart && $cart->details->isNotEmpty())
                            @foreach( $cart->details as $detail )
                                <div class="row gy-3 mb-4">
                                    <div class="col-md-5">
                                        <div class="me-lg-5">
                                            <div class="d-flex">
                                                <img src="{{ asset('images/products/'.$detail->product->image) }}" class="border rounded me-3" style="width: 96px; height: 96px;" />
                                                <div class="">
                                                    <a href="#" class="nav-link">{{ $detail->product->full_name }}</a>
                                                    <p class="text-muted ml-3 mb-0">
                                                        Tipo: {{ $detail->productType->type->name }}
                                                        @if($detail->productType->type && $detail->productType->type->size)
                                                            ( {{ $detail->productType->type->size }} )
                                                        @endif
                                                    </p>
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
                                                <small class="text-muted text-nowrap"> S/ {{ $detail->productType->price }} / por item </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="float-md-end">
                                            {{--<a href="#!" data-material_id="{{ $detail->product->id }}" class="btn btn-light border icon-hover-primary"><i class="fas fa-heart fa-lg text-secondary"></i></a>--}}
                                            <button type="button" data-delete_item data-detail_id="{{ $detail->id }}" class="btn btn-light border text-danger icon-hover-danger"> <i class="fas fa-trash fa-lg text-secondary"></i></button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <h5>Tu carrito está vacío</h5>
                                <a href="{{ route('home') }}" class="btn btn-primary mt-3">Volver a la tienda</a>
                            </div>
                        @endif


                        </div>

                        {{--@if($cart && $cart->details->isNotEmpty())
                            <div class="border-top pt-4 mx-4 mb-4">
                                <p><i class="fas fa-truck text-muted fa-lg"></i> Entrega gratuita en 1-2 semanas</p>
                                <p class="text-muted">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit...
                                </p>
                            </div>
                        @endif--}}
                    </div>
                </div>
                <!-- cart -->
                <!-- summary -->

                <div class="col-lg-3">
                    {{--@if($cart && $cart->details->isNotEmpty())
                    <div class="card mb-3 border shadow-0">
                        <div class="card-body">

                            <div class="form-group">
                                <label class="form-label">Have coupon?</label>
                                <div class="input-group">
                                    <input type="text" class="form-control border" name="" placeholder="Coupon code" />
                                    <button class="btn btn-light border">Apply</button>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif--}}
                    @if($cart && $cart->details->isNotEmpty())
                        <div class="card shadow-0 border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">Sub Total:</p>
                                    <p class="mb-2" id="subtotal_cart">S/ {{ number_format($cart->subtotal_cart, 2) }}</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">Descuento:</p>
                                    <p class="mb-2 text-success">S/ {{ number_format(0, 2) }}</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">IGV:</p>
                                    <p class="mb-2" id="taxes_cart">S/ {{ number_format($cart->taxes_cart, 2) }}</p>
                                </div>
                                <hr />
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">Precio Total:</p>
                                    <p class="mb-2 fw-bold" id="total_cart">S/ {{ number_format($cart->total_cart, 2) }}</p>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('cart.checkout') }}" class="btn btn-success w-100 shadow-0 mb-2"> Completar la compra </a>
                                    <a href="{{ route('home') }}" class="btn btn-light w-100 border mt-2"> Seguir comprando </a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <!-- summary -->
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/cart/cart.js') }}"></script>
@endsection
