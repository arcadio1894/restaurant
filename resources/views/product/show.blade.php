@extends('layouts.app')

@section('menu-active', 'active')

@section('text-header')
    {{ $product->full_name }}
@endsection

@section('styles')
    <style>
        .icon-hover:hover {
            border-color: #3b71ca !important;
            background-color: white !important;
            color: #3b71ca !important;
        }

        .icon-hover:hover i {
            color: #3b71ca !important;
        }

        .custom-radio-checkbox {
            position: relative;
            padding-left: 2.5rem; /* Espacio para el icono más grande */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .custom-radio-checkbox .form-check-input {
            width: 1.2rem;
            height: 1.2rem;
            margin-left: -2.5rem; /* Coloca el input dentro del padding */
            cursor: pointer;
            accent-color: #e69c00; /* Color personalizado para el icono */
            transition: transform 0.2s ease;
        }

        .custom-radio-checkbox .form-check-input:checked {
            transform: scale(1.2); /* Aumenta el tamaño al seleccionar */
        }

        .custom-radio-checkbox:hover {
            background-color: rgba(230, 156, 0, 0.1); /* Fondo suave al pasar el mouse */
            border-radius: 5px; /* Bordes redondeados */
        }

        .custom-radio-checkbox .form-check-label {
            font-size: 1.2rem;
            color: #333;
        }

        .custom-radio-checkbox .form-check-input:checked + .form-check-label {
            color: #e69c00; /* Cambia el color del texto al seleccionarse */
            font-weight: bold;
        }

        .carousel {
            margin: 50px auto;
            padding: 0 70px;
        }
        .carousel .carousel-item {
            min-height: 330px;
            text-align: center;
            overflow: hidden;
        }
        .carousel .carousel-item .img-box {
            height: 160px;
            width: 100%;
            position: relative;
        }
        .carousel .carousel-item img {
            max-width: 100%;
            max-height: 100%;
            display: inline-block;
            position: absolute;
            bottom: 0;
            margin: 0 auto;
            left: 0;
            right: 0;
        }
        .carousel .carousel-item h4 {
            font-size: 18px;
            margin: 10px 0;
        }
        /*.carousel .carousel-item .btn {
            color: #333;
            border-radius: 0;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
            background: none;
            border: 1px solid #ccc;
            padding: 5px 10px;
            margin-top: 5px;
            line-height: 16px;
        }*/
        .carousel .carousel-item .btn:hover, .carousel .carousel-item .btn:focus {
            color: #fff;
            background: #000;
            border-color: #000;
            box-shadow: none;
        }
        .carousel .carousel-item .btn i {
            font-size: 14px;
            font-weight: bold;
            margin-left: 5px;
        }
        .carousel .thumb-wrapper {
            text-align: center;
        }
        .carousel .thumb-content {
            padding: 15px;
        }
        .carousel-control-prev, .carousel-control-next {
            height: 100px;
            width: 40px;
            background: none;
            margin: auto 0;
            background: rgba(0, 0, 0, 0.2);
        }
        .carousel-control-prev i, .carousel-control-next i {
            font-size: 30px;
            position: absolute;
            top: 50%;
            display: inline-block;
            margin: -16px 0 0 0;
            z-index: 5;
            left: 0;
            right: 0;
            color: rgba(0, 0, 0, 0.8);
            text-shadow: none;
            font-weight: bold;
        }
        .carousel-control-prev i {
            margin-left: -3px;
        }
        .carousel-control-next i {
            margin-right: -3px;
        }
        .carousel .item-price {
            font-size: 13px;
            padding: 2px 0;
        }
        .carousel .item-price strike {
            color: #999;
            margin-right: 5px;
        }
        .carousel .item-price span {
            color: #86bd57;
            font-size: 110%;
        }
        .carousel .carousel-indicators {
            bottom: -50px;
        }
        .carousel-indicators li, .carousel-indicators li.active {
            width: 10px;
            height: 10px;
            margin: 4px;
            border-radius: 50%;
            border-color: transparent;
            border: none;
        }
        .carousel-indicators li {
            background: rgba(0, 0, 0, 0.2);
        }
        .carousel-indicators li.active {
            background: rgba(0, 0, 0, 0.6);
        }
        .star-rating li {
            padding: 0;
        }
        .star-rating i {
            font-size: 14px;
            color: #ffc000;
        }
        /* Aplica a todas las tarjetas */
        .thumb-wrapper {
            height: 350px; /* Ajusta la altura fija según sea necesario */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Limita el alto del nombre del producto */
        .thumb-wrapper h4 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 10px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap; /* Trunca textos largos */
        }

        /* Ajusta la altura del contenedor de imágenes */
        .thumb-wrapper .img-box {
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .thumb-wrapper .img-box img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain; /* Asegura que las imágenes se ajusten correctamente */
        }

        /* Espaciado entre los elementos */
        .thumb-content {
            text-align: center;
            flex-grow: 1; /* Para que ocupe el espacio disponible */
        }

        /* Botón de agregar */
        .thumb-content .btn {
            margin-top: auto; /* Empuja el botón hacia abajo */
        }
        @media (max-width: 767.98px) {
            .carousel .row .col-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                <aside class="col-lg-6">
                    <div class="border rounded-4 mb-3 d-flex justify-content-center">
                        <a data-fslightbox="mygalley" class="rounded-4" target="_blank" data-type="image" href="{{ asset('images/products/'.$product->image) }}">
                            <img style="max-width: 100%; max-height: 100vh; margin: auto;" class="rounded-4 fit" src="{{ asset('images/products/'.$product->image) }}" />
                        </a>
                    </div>
                    <div class="d-flex justify-content-center mb-3">
                        {{--<a data-fslightbox="mygalley" class="border mx-1 rounded-2 item-thumb" target="_blank" data-type="image" href="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big1.webp">
                            <img width="60" height="60" class="rounded-2" src="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big1.webp" />
                        </a>
                        <a data-fslightbox="mygalley" class="border mx-1 rounded-2 item-thumb" target="_blank" data-type="image" href="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big2.webp">
                            <img width="60" height="60" class="rounded-2" src="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big2.webp" />
                        </a>
                        <a data-fslightbox="mygalley" class="border mx-1 rounded-2 item-thumb" target="_blank" data-type="image" href="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big3.webp">
                            <img width="60" height="60" class="rounded-2" src="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big3.webp" />
                        </a>
                        <a data-fslightbox="mygalley" class="border mx-1 rounded-2 item-thumb" target="_blank" data-type="image" href="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big4.webp">
                            <img width="60" height="60" class="rounded-2" src="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big4.webp" />
                        </a>
                        <a data-fslightbox="mygalley" class="border mx-1 rounded-2 item-thumb" target="_blank" data-type="image" href="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big.webp">
                            <img width="60" height="60" class="rounded-2" src="https://mdbcdn.b-cdn.net/img/bootstrap-ecommerce/items/detail1/big.webp" />
                        </a>--}}
                    </div>
                    <!-- thumbs-wrap.// -->
                    <!-- gallery-wrap .end// -->
                </aside>
                <main class="col-lg-6">
                    <div class="ps-lg-3">
                        {{--<h4 class="title text-dark">
                            Quality Men's Hoodie for Winter, Men's Fashion <br />
                            Casual Hoodie
                        </h4>--}}
                        <div class="d-flex flex-row my-3">
                            <div class="text-warning mb-1 me-2">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fas fa-star-half"></i>
                                <span class="ms-1">
                                    4.5
                                </span>
                            </div>
                            {{--<span class="text-muted"><i class="fas fa-shopping-basket fa-sm mx-1 pl-2"></i>154 orders</span>--}}
                            <span class="text-success ms-2 pl-2"> In stock</span>
                        </div>

                        <div class="mb-3">
                            <span class="h5">S/. <span id="product-price">{{ isset($defaultProductType->price) ? $defaultProductType->price : $product->unit_price }}</span></span>
                            <span class="text-muted">/ por unidad</span>
                        </div>

                        <p>
                            {{ $product->description }}
                        </p>

                        <p>
                            {!! nl2br($product->ingredients) !!}
                        </p>

                        <div class="row">
                            {{-- Logica para mostrar las opciones --}}
                            @foreach ($options as $option)
                                <div class="col-md-12 mb-4">
                                    <strong>{{ $option->description }}</strong>
                                    <small>Cantidad máxima: {{ $option->quantity }}</small>
                                    <br><br>
                                    <div class="option-container"
                                         data-option-id="{{ $option->id }}"
                                         data-quantity="{{ $option->quantity }}"
                                         data-type="{{ $option->type }}">

                                        {{-- Según el tipo de la opción, generar dinámicamente los inputs --}}
                                        @if ($option->type == 'radio')
                                            @foreach ($option->selections as $selection)
                                                <div class="form-check mb-2 custom-radio-checkbox">
                                                    <input class="form-check-input option-input"
                                                           type="radio"
                                                           name="option_{{ $option->id }}"
                                                           value="{{ $selection->product_id }}"
                                                           id="radio_{{ $option->id }}_{{ $loop->index }}" />
                                                    <label class="form-check-label" for="radio_{{ $option->id }}_{{ $loop->index }}">
                                                        {{ $selection->product->full_name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @elseif ($option->type == 'checkbox')
                                            @foreach ($option->selections as $selection)
                                                <div class="form-check mb-2 custom-radio-checkbox">
                                                    <input class="form-check-input option-input"
                                                           type="checkbox"
                                                           name="option_{{ $option->id }}[]"
                                                           value="{{ $selection->product_id }}"
                                                           id="checkbox_{{ $option->id }}_{{ $loop->index }}" />
                                                    <label class="form-check-label" for="checkbox_{{ $option->id }}_{{ $loop->index }}">
                                                        {{ $selection->product->full_name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @elseif ($option->type == 'select')
                                            <div class="mb-2">
                                                <select class="form-select option-input" name="option_{{ $option->id }}">
                                                    <option value="">Seleccione una opción</option>
                                                    @foreach ($option->selections as $selection)
                                                        <option value="{{ $selection->product_id }}">
                                                            {{ $selection->product->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr />

                        <div class="row mb-2">
                            @if (count($productTypes) > 0)
                            <div class="col-md-4 col-6">
                                <select id="pizza-type-select" class="form-select border border-secondary" style="height: 35px;">
                                    @foreach($productTypes as $productType)
                                        <option value="{{ $productType->id }}"
                                                data-price="{{ $productType->price }}"
                                                {{ $productType->default ? 'selected' : '' }}>
                                            {{ $productType->type->name }} {{ ($productType->type->size == null) ? "":"(".$productType->type->size.")" }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <!-- col.// -->
                            <div class="col-md-4 col-6 mb-3">
                                <a href="#"
                                   class="btn btn-primary shadow-0"
                                   id="add-to-cart-btn"
                                   data-product-category="{{ $product->category_id }}"
                                   data-product-id_v2="{{ $product->id }}"
                                   data-product-id="{{ $product->slug }}"
                                   data-auth-check-url="{{ route('auth.check') }}"
                                   data-add-cart-url="{{ route('cart.manage') }}">
                                    <i class="me-1 fa fa-shopping-basket"></i> Agregar
                                </a>
                            </div>
                        </div>
                        {{--<a href="#" class="btn btn-warning shadow-0"> Buy now </a>--}}
                        {{--<a href="#" class="btn btn-light border border-secondary icon-hover"> <i class="me-1 fa fa-heart"></i> Save </a>--}}
                    </div>
                </main>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">Productos <b>Adicionales</b></h2>
                    <div class="d-none d-sm-block">
                        <!-- Carrusel para dispositivos grandes (4 productos por slide) -->
                        <div id="carouselLarge" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($adicionales->chunk(4) as $group)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <div class="row">
                                            @foreach ($group as $producto)
                                                <div class="col-sm-3">
                                                    <div class="thumb-wrapper">
                                                        <div class="img-box">
                                                            <img src="{{ asset('images/products/'.$producto->image) }}" class="img-fluid" alt="">
                                                        </div>
                                                        <div class="thumb-content">
                                                            <h4>{{ $producto->full_name }}</h4>
                                                            <p class="item-price">S/. {{ $producto->price_default }}</p>
                                                            <div class="text-warning mb-1 me-2">
                                                                <i class="fa fa-star"></i>
                                                                <i class="fa fa-star"></i>
                                                                <i class="fa fa-star"></i>
                                                                <i class="fa fa-star"></i>
                                                                <i class="fas fa-star-half"></i>
                                                                <span class="ms-1">
                                                                    4.5
                                                                </span>
                                                            </div>
                                                            <a href="#"
                                                               class="btn btn-primary shadow-0"
                                                               data-add_to_cart_adicional
                                                               data-product-category="{{ $producto->category_id }}"
                                                               data-product-id_v2="{{ $producto->id }}"
                                                               data-product-id="{{ $producto->slug }}"
                                                               data-auth-check-url="{{ route('auth.check') }}"
                                                               data-add-cart-url="{{ route('cart.manage2') }}">
                                                                <i class="me-1 fa fa-shopping-basket"></i> Agregar
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Controles del carrusel -->
                            <a class="carousel-control-prev" href="#carouselLarge" role="button" data-slide="prev">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <a class="carousel-control-next" href="#carouselLarge" role="button" data-slide="next">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="d-block d-sm-none">
                        <!-- Carrusel para dispositivos pequeños (1 producto por slide) -->
                        <div id="carouselSmall" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($adicionales as $producto)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <div class="thumb-wrapper text-center">
                                            <div class="img-box">
                                                <img src="{{ asset('images/products/'.$producto->image) }}" class="img-fluid" alt="">
                                            </div>
                                            <div class="thumb-content">
                                                <h4>{{ $producto->full_name }}</h4>
                                                <p class="item-price">S/. {{ $producto->price_default }}</p>
                                                <div class="text-warning mb-1 me-2">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fas fa-star-half"></i>
                                                    <span class="ms-1">4.5</span>
                                                </div>
                                                <a href="#"
                                                   class="btn btn-primary shadow-0"
                                                   data-add_to_cart_adicional
                                                   data-product-category="{{ $product->category_id }}"
                                                   data-product-id_v2="{{ $product->id }}"
                                                   data-product-id="{{ $producto->slug }}"
                                                   data-auth-check-url="{{ route('auth.check') }}"
                                                   data-add-cart-url="{{ route('cart.manage2') }}">
                                                    <i class="me-1 fa fa-shopping-basket"></i> Agregar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Controles del carrusel -->
                            <a class="carousel-control-prev" href="#carouselSmall" role="button" data-slide="prev">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <a class="carousel-control-next" href="#carouselSmall" role="button" data-slide="next">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- content -->

@endsection

@section('scripts')
    <script src="{{ asset('js/product/showV2.js') }}?v={{ time() }}"></script>
@endsection
