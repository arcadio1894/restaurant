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
                                                    {{--<p class="text-muted">Yellow, Jeans</p>--}}
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
                                                <small class="text-muted text-nowrap"> S/ {{ $detail->product->unit_price }} / per item </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="float-md-end">
                                            <a href="#!" data-material_id="{{ $detail->product->id }}" class="btn btn-light border icon-hover-primary"><i class="fas fa-heart fa-lg text-secondary"></i></a>
                                            <a href="#" data-material_id="{{ $detail->product->id }}" class="btn btn-light border text-danger icon-hover-danger"> <i class="fas fa-trash fa-lg text-secondary"></i></a>
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

                        @if($cart && $cart->details->isNotEmpty())
                            <div class="border-top pt-4 mx-4 mb-4">
                                <p><i class="fas fa-truck text-muted fa-lg"></i> Entrega gratuita en 1-2 semanas</p>
                                <p class="text-muted">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit...
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- cart -->
                <!-- summary -->

                <div class="col-lg-3">
                    @if($cart && $cart->details->isNotEmpty())
                    <div class="card mb-3 border shadow-0">
                        <div class="card-body">

                            {{--<form>--}}
                                <div class="form-group">
                                    <label class="form-label">Have coupon?</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border" name="" placeholder="Coupon code" />
                                        <button class="btn btn-light border">Apply</button>
                                    </div>
                                </div>
                            {{--</form>--}}

                        </div>
                    </div>
                    @endif
                    @if($cart && $cart->details->isNotEmpty())
                        <div class="card shadow-0 border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">Total price:</p>
                                    <p class="mb-2" id="subtotal_cart">S/ {{ number_format($cart->subtotal_cart, 2) }}</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">Discount:</p>
                                    <p class="mb-2 text-success">S/ {{ number_format(0, 2) }}</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">TAX:</p>
                                    <p class="mb-2" id="taxes_cart">S/ {{ number_format($cart->taxes_cart, 2) }}</p>
                                </div>
                                <hr />
                                <div class="d-flex justify-content-between">
                                    <p class="mb-2">Total price:</p>
                                    <p class="mb-2 fw-bold" id="total_cart">S/ {{ number_format($cart->total_cart, 2) }}</p>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('cart.checkout') }}" class="btn btn-success w-100 shadow-0 mb-2"> Make Purchase </a>
                                    <a href="{{ route('home') }}" class="btn btn-light w-100 border mt-2"> Back to shop </a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <!-- summary -->
            </div>
        </div>
    </section>
    <section>
        <div class="container my-5">
            <header class="mb-4">
                <h3>Recommended items</h3>
            </header>

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card px-4 border shadow-0 mb-4 mb-lg-0">
                        <div class="mask px-2" style="height: 50px;">
                            <div class="d-flex justify-content-between">
                                <h6><span class="badge bg-danger pt-1 mt-3 ms-2">New</span></h6>
                                <a href="#"><i class="fas fa-heart text-primary fa-lg float-end pt-3 m-2"></i></a>
                            </div>
                        </div>
                        <a href="#" class="">
                            <img src="https://mdbootstrap.com/img/bootstrap-ecommerce/items/7.webp" class="card-img-top rounded-2" />
                        </a>
                        <div class="card-body d-flex flex-column pt-3 border-top">
                            <a href="#" class="nav-link">Gaming Headset with Mic</a>
                            <div class="price-wrap mb-2">
                                <strong class="">$18.95</strong>
                                <del class="">$24.99</del>
                            </div>
                            <div class="card-footer d-flex align-items-end pt-3 px-0 pb-0 mt-auto">
                                <a href="#" class="btn btn-outline-primary w-100">Add to cart</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card px-4 border shadow-0 mb-4 mb-lg-0">
                        <div class="mask px-2" style="height: 50px;">
                            <a href="#"><i class="fas fa-heart text-primary fa-lg float-end pt-3 m-2"></i></a>
                        </div>
                        <a href="#" class="">
                            <img src="https://mdbootstrap.com/img/bootstrap-ecommerce/items/5.webp" class="card-img-top rounded-2" />
                        </a>
                        <div class="card-body d-flex flex-column pt-3 border-top">
                            <a href="#" class="nav-link">Apple Watch Series 1 Sport </a>
                            <div class="price-wrap mb-2">
                                <strong class="">$120.00</strong>
                            </div>
                            <div class="card-footer d-flex align-items-end pt-3 px-0 pb-0 mt-auto">
                                <a href="#" class="btn btn-outline-primary w-100">Add to cart</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card px-4 border shadow-0">
                        <div class="mask px-2" style="height: 50px;">
                            <a href="#"><i class="fas fa-heart text-primary fa-lg float-end pt-3 m-2"></i></a>
                        </div>
                        <a href="#" class="">
                            <img src="https://mdbootstrap.com/img/bootstrap-ecommerce/items/9.webp" class="card-img-top rounded-2" />
                        </a>
                        <div class="card-body d-flex flex-column pt-3 border-top">
                            <a href="#" class="nav-link">Men's Denim Jeans Shorts</a>
                            <div class="price-wrap mb-2">
                                <strong class="">$80.50</strong>
                            </div>
                            <div class="card-footer d-flex align-items-end pt-3 px-0 pb-0 mt-auto">
                                <a href="#" class="btn btn-outline-primary w-100">Add to cart</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card px-4 border shadow-0">
                        <div class="mask px-2" style="height: 50px;">
                            <a href="#"><i class="fas fa-heart text-primary fa-lg float-end pt-3 m-2"></i></a>
                        </div>
                        <a href="#" class="">
                            <img src="https://mdbootstrap.com/img/bootstrap-ecommerce/items/10.webp" class="card-img-top rounded-2" />
                        </a>
                        <div class="card-body d-flex flex-column pt-3 border-top">
                            <a href="#" class="nav-link">Mens T-shirt Cotton Base Layer Slim fit </a>
                            <div class="price-wrap mb-2">
                                <strong class="">$13.90</strong>
                            </div>
                            <div class="card-footer d-flex align-items-end pt-3 px-0 pb-0 mt-auto">
                                <a href="#" class="btn btn-outline-primary w-100">Add to cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/cart/cart.js') }}"></script>
@endsection
