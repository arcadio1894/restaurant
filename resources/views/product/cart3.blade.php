@extends('layouts.app')

@section('menu-active', 'active')

@section('text-header')
    <h2 class="pt-5">
        Tu Carrito de compras
    </h2>

@endsection

@section('styles')
    <style>
        .producto {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #ccc;
            padding: 15px 0;
        }

        .producto img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .producto-info {
            flex: 1;
            margin-left: 5px;
        }

        .producto-nombre {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .producto-detalle {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
            margin-bottom: 2px;
        }

        .producto-precio {
            text-align: right;
        }

        .precio-actual {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .descuento {
            font-size: 12px;
            color: #ff9800;
            background: #fff7e6;
            padding: 2px 4px;
            border-radius: 4px;
        }

        .precio-original {
            font-size: 12px;
            color: #999;
            /*text-decoration: line-through;*/
        }

        .cantidad {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
            max-width: 120px; /* Limita el ancho total */
            margin: 0 auto; /* Centrar si es necesario */
        }

        .cantidad button {
            background: none;
            border: none;
            font-size: 16px; /* Reducimos el tamaño */
            cursor: pointer;
            color: #555;
            width: 30px; /* Tamaño fijo para evitar separación */
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cantidad button:hover {
            color: #000;
        }

        .cantidad span {
            font-size: 14px; /* Reducimos el tamaño de fuente */
            margin: 0 5px; /* Menos espacio entre botones */
            display: inline-block;
            width: 25px; /* Limitamos el ancho */
            text-align: center;
        }

        .eliminar {
            color: red;
            cursor: pointer;
            margin-right: 10px;
        }

        .producto-detalles-link {
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
            display: inline-block;
        }

        .producto-detalles-link:hover {
            text-decoration: underline;
        }

        .cantidad-control {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #000;
            border-radius: 5px;
            padding: 5px;
            gap: 10px;
            max-width: 120px;
            margin-top: 10px;
        }

        .icono-cantidad {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
        }

        .icono-cantidad i {
            color: #555;
        }

        .icono-cantidad i:hover {
            color: #000;
        }

        .cantidad-numero {
            font-size: 16px;
            width: 20px;
            text-align: center;
            font-weight: bold;
        }

        .icono-cantidad:focus {
            outline: 2px solid #ffffff; /* Cambia el borde a un color específico */
            border-radius: 4px; /* Opcional: haz que coincida con el diseño del botón */
        }

        .detalles-popup {
            position: relative;
            display: none; /* Oculto por defecto */
        }

        .burbuja {
            position: absolute;
            top: 20px;
            left: 0;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            width: 250px;
            z-index: 10;
        }

        .burbuja::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 20px;
            width: 0;
            height: 0;
            border: 10px solid transparent;
            border-bottom-color: #ccc;
        }

        .burbuja ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .burbuja ul li {
            font-size: 0.8rem;
            margin-bottom: 5px;
        }

        /* Boton de Agregar carrito */
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
                <!-- cart -->
                <div class="col-lg-9">
                    <div class="card border shadow-0">
                        <div class="m-2" id="body-items">
                            {{--<div class="producto">
                                <img src="{{ asset('images/products/1.png') }}" alt="Imagen del producto">
                                <div class="producto-info">
                                    <p class="producto-nombre">Margarita del campo</p>
                                    <p class="producto-detalle">Familiar (35 cm)</p>
                                    <a href="#" class="producto-detalles-link">Detalles</a>
                                    <div class="detalles-popup">
                                        <div class="burbuja">
                                            <ul>
                                                <li>Ingredientes: Tomate, queso, albahaca</li>
                                                <li>Tamaño: 35 cm</li>
                                                <li>Porciones: 8</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="producto-precio">
                                    <p class="precio-actual">S/22.42</p>
                                    <p class="precio-original">S/29.90</p>
                                    <div class="cantidad">
                                        <div class="cantidad-control">
                                            <button class="icono-cantidad">
                                                <i class="far fa-trash-alt" id="icon-trash"></i>
                                                <i class="fas fa-minus" id="icon-minus" style="display: none;"></i>
                                            </button>
                                            <span class="cantidad-numero">1</span>
                                            <button class="icono-cantidad">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>--}}
                            <div id="loading-indicator" style="display: none;">
                                <p>Cargando...</p>
                            </div>

                        </div>
                    </div>
                    <div id="body-observations">
                        <!-- cart_observations -->
                    </div>
                </div>
                <!-- cart -->


                <div class="col-lg-3" id="body-summary">
                    <!-- summary -->

                </div>

            </div>

        </div>
    </section>

    <div class="mobile-fixed-cart d-lg-none">
        <button data-href="{{ route('cart.checkout') }}" class="btn btn-danger btn-block py-3" id="go-to-checkout-btn-mobile">
            Ir a Pagar
            <span class="h5">S/.
                <span id="product-price-mobile"></span>
            </span>
        </button>
    </div>

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
                    <div class="d-flex justify-content-center">
                        <button data-href="{{ route('cart.checkout') }}" class="btn btn-success shadow-0 mb-2 d-lg-flex d-none text-center w-100" id="go-to-checkout" style="display: flex; justify-content: center; align-items: center;">
                            Ir a Pagar
                        </button>
                    </div>

                    {{--<button data-href="{{ route('cart.checkout') }}" class="btn btn-success w-100 shadow-0 mb-2 d-lg-flex d-none text-center" id="go-to-checkout"> Ir a Pagar </button>
                    --}}
                    @auth()
                        <a href="{{ route('home') }}" class="btn btn-light w-100 border mt-2"> Seguir comprando </a>
                        <button id="btn-delete_cart" class="btn btn-light w-100 border mt-2"> Borrar todo el carrito </button>
                    @else
                        <a href="{{ route('welcome') }}" class="btn btn-light w-100 border mt-2"> Seguir comprando </a>
                        <button id="btn-delete_cart" class="btn btn-light w-100 border mt-2"> Borrar todo el carrito </button>
                    @endif
                </div>
            </div>
        </div>
    </template>

    <template id="template-observations">
        <div class="card border shadow-0 mt-3 pt-3 pb-3">
            <div class="col-sm-12">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header py-2 d-flex justify-content-between align-items-center" id="headingOne" style="border-bottom: none;">
                            <button class="btn btn-link d-flex align-items-center p-0 w-100 justify-content-between text-dark" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="text-decoration: none; color: inherit;">
                                <span>Notas Adicionales</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body py-2">
                                <textarea name="observations" data-cart_observations id="observations" rows="3" class="form-control" maxlength="100"></textarea>
                                <div class="d-flex justify-content-end mt-2">
                                    <small id="charCount" class="text-muted">0/100</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="template-cart_detail">
        <div class="producto">
            <img data-image src="" alt="Imagen del producto">
            <div class="producto-info">
                <p class="producto-nombre" data-product_name>Margarita del campo</p>
                <p class="producto-detalle" data-detail_productType>Familiar (35 cm)</p>
                <a href="#" class="producto-detalles-link">Detalles</a>
                <div class="detalles-popup">
                    <div class="burbuja">
                        <ul data-body_options>
                            {{--<li>Ingredientes: Tomate, queso, albahaca</li>
                            <li>Tamaño: 35 cm</li>
                            <li>Porciones: 8</li>--}}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="producto-precio">
                <p class="precio-actual" data-detail_subtotal>S/22.42</p>
                <p class="precio-original" data-detail_price>S/29.90</p>
                <div class="cantidad">
                    <div class="cantidad-control">
                        <button class="icono-cantidad" data-minus data-detail_id="">
                            <i class="far fa-trash-alt" id="icon-trash" data-delete_item data-detail_id=""></i>
                            <i class="fas fa-minus" id="icon-minus" style="display: none;"></i>
                        </button>
                        <span class="cantidad-numero" data-quantity data-detail_id="">1</span>
                        <button class="icono-cantidad" data-plus data-detail_id="">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="template-option">
        <li data-option>Ingredientes: Tomate, queso, albahaca</li>
        {{--<li data-option>

        </li>--}}
    </template>
@endsection

@section('scripts')
    <script src="{{ asset('js/cart/cart3.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/cart/cartNew.js') }}?v={{ time() }}"></script>
@endsection
