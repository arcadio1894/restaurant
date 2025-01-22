@extends('layouts.app')

@section('menu-active', 'active')

@section('text-header')
    {{--<h2 class="pt-5">
        {{ $product->full_name }}
    </h2>--}}

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
            font-size: 0.9rem;
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

        .pizza-options {
            display: flex;
            gap: 1rem;
        }

        .radio-button {
            position: relative;
            display: inline-block;
            cursor: pointer;
            border: 1px solid #ccc; /* Borde inicial */
            border-radius: 5px;
            width: 120px; /* Ajusta según tu diseño */
            padding: 10px;
            text-align: center;
            background-color: #fff; /* Fondo blanco inicial */
        }

        .radio-button input[type="radio"] {
            display: none; /* Oculta el input original */
        }

        .radio-button .check-icon {
            position: absolute;
            top: -5px; /* Mueve más arriba para que quede al borde */
            right: -5px; /* Mueve más a la derecha para alinearse al borde */
            font-size: 20px;
            color: #e69c00;
            display: none; /* Solo se muestra cuando está seleccionado */
        }

        .radio-button .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .radio-button .size {
            font-weight: bold;
        }

        .radio-button .slices,
        .radio-button .price {
            font-size: 12px;
            color: #666;
        }

        /* Cuando el radio está seleccionado */
        .pizza-options .radio-button.active {
            border: 2px solid #e69c00 !important; /* Forzar la aplicación del borde rojo */
        }

        .radio-button.active .check-icon {
            display: block; /* Muestra el ícono */
        }

        /* Acordeon */
        .card {
            border: 1px solid #ddd; /* Borde del contenedor */
            border-radius: 5px; /* Esquinas redondeadas */
            margin-bottom: 10px; /* Espaciado entre las tarjetas */
        }

        .card-header {
            background-color: #f8f9fa; /* Fondo de los encabezados */
            border-bottom: 1px solid #ddd; /* Borde inferior */
            padding: 10px; /* Espaciado interno */
        }

        .btn-link {
            color: #e69c00; /* Color del texto */
            text-decoration: none; /* Sin subrayado */
            font-weight: bold; /* Texto en negrita */
        }

        .btn-link:hover {
            text-decoration: underline; /* Subrayado al pasar el mouse */
        }

        .collapse.custom-collapse.show {
            background-color: #ffffff; /* Fondo del contenido expandido específico */
        }

        /* ACCORDEON */
        /* Ajustes generales del acordeón */
        .card-header {
            cursor: pointer;
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        /* Íconos de Chevron */
        .toggle-icon {
            transition: transform 0.3s ease; /* Transición suave para el giro */
        }

        /* Giro hacia arriba cuando está expandido */
        .card-header[aria-expanded="true"] .toggle-icon {
            transform: rotate(180deg);
        }

        /* checkbox adicionales */
        /* Grupo de checkboxes */
        .custom-checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Ocultar el checkbox original */
        .custom-checkbox-input {
            position: absolute; /* Posición fuera del flujo */
            opacity: 0; /* Totalmente invisible */
            width: 0;
            height: 0;
        }

        /* Contenedor como label */
        .custom-checkbox-container {
            display: flex;
            align-items: center;
            border: 2px solid #ddd; /* Borde inicial */
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            background-color: #fff; /* Fondo inicial */
            transition: border-color 0.3s ease, background-color 0.3s ease; /* Transiciones suaves */
        }

        /* Hover: Cambiar color al pasar el mouse */
        /*.custom-checkbox-container:hover {
            border-color: #e69c00;
            background-color: #fff8e5; !* Fondo amarillo claro *!
        }*/

        /* Estilo cuando está seleccionado */
        .custom-checkbox-container.selected {
            /*border-color: #e69c00;*/
            border: 2px solid #e69c00;
            background-color: #fff8e5; /* Fondo amarillo claro */
        }

        /* Quitar el estilo de foco para checkboxes */
        .custom-checkbox-input:focus,
        .custom-checkbox-container:focus {
            outline: none;
            box-shadow: none;
        }

        /* Para navegadores basados en WebKit */
        .custom-checkbox-input:focus {
            outline: none !important;
            box-shadow: none !important;
            border: none !important;
        }

        /* Estilos para deshabilitar el borde amarillo */
        .custom-checkbox-input {
            outline: none !important;
            box-shadow: none !important;
        }

        /* Para navegadores móviles */
        @media (max-width: 768px) {
            .custom-checkbox-input {
                outline: none !important;
                box-shadow: none !important;
            }

            .custom-checkbox-input:focus {
                outline: none !important;
                box-shadow: none !important;
            }
        }

        .no-focus:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        /* Cuadro del checkbox */
        .checkbox-box {
            width: 20px;
            height: 20px;
            border: 2px solid #ddd;
            border-radius: 3px;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        /* Check visual dentro del cuadro */
        .checkbox-box::after {
            content: '';
            width: 10px;
            height: 10px;
            background-color: transparent; /* Inicialmente vacío */
            border-radius: 2px;
            display: none; /* Oculto inicialmente */
        }

        /* Checkbox activado: cuadro del checkbox */
        .custom-checkbox-input:checked + .checkbox-box {
            border-color: #e69c00;
            background-color: #e69c00;
        }

        .custom-checkbox-input:checked + .checkbox-box::after {
            display: block;
            background-color: #fff; /* Check blanco dentro del cuadro */
        }

        /* Contenido dentro del label */
        .custom-checkbox-content {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%; /* Extender al ancho total */
        }

        /* Imagen */
        .custom-checkbox-content img {
            width: 48px;
            height: 48px;
            border-radius: 4px;
            object-fit: cover;
        }

        /* Detalles del topping */
        .custom-checkbox-details {
            display: flex;
            flex-direction: column;
        }

        .topping-name {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .topping-price {
            font-size: 12px;
            color: #555;
        }

        /* Remover el estilo de enfoque para los checkboxes */
        .custom-checkbox-input:focus {
            outline: none;
            box-shadow: none;
        }

        /* Scroll */
        .scrollable-container {
            max-height: 600px; /* Ajusta según tus necesidades */
            overflow-y: auto;
            scrollbar-width: thin; /* Para navegadores modernos */
        }

        /* Ver Mas */
        .hidden-elements {
            display: none; /* Inicialmente oculto */
        }

        .hidden-elements.visible {
            display: flex; /* O block, según tu diseño */
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

        .text-custom {
            padding: 0px 0px 4px;
            margin: 0px;
            font-weight: bold;
            font-size: 40px;
            text-decoration: none;
            line-height: 40px;
            color: rgb(35, 31, 32);
        }
    </style>
@endsection

@section('content')
    <div id="clickHere"></div>
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                <aside class="col-lg-5 offset-xl-1">
                    <div class="d-flex flex-row">
                        <div class="mb-1 me-2 text-uppercase text-custom">
                            <h2>
                                {{ $product->full_name }}
                            </h2>

                        </div>
                        {{--<span class="text-muted"><i class="fas fa-shopping-basket fa-sm mx-1 pl-2"></i>154 orders</span>--}}
                        {{--<span class="text-success ms-2 pl-2"> In stock</span>--}}
                        <small class="ml-5 text-danger {{ ($product->visibility_price_real == 0) ? 'd-none' : '' }}" style="text-decoration: line-through;"><span data-real-price="" id="product-price-real"></span></small>

                    </div>
                    <p>
                        {{ $product->description }}
                    </p>
                    <div class="border rounded-4 mb-3 d-flex justify-content-center">
                        <a data-fslightbox="mygalley" class="rounded-4" target="_blank" data-type="image" href="{{ asset('images/products/'.$product->image) }}">
                            <img style="max-width: 100%; max-height: 100vh; margin: auto;" class="rounded-4 fit" src="{{ asset('images/products/'.$product->image) }}" />
                        </a>
                    </div>
                    <div class="d-flex justify-content-center mb-3 d-lg-flex d-none">
                        <button type="button" class="btn btn-danger btn-block py-3"
                                id="add-to-cart-btn"
                                data-product-category="{{ $product->category_id }}"
                                data-product-id_v2="{{ $product->id }}"
                                data-product-id="{{ $product->slug }}"
                                data-auth-check-url="{{ route('auth.check') }}"
                                data-add-cart-url="{{ route('cart.manage') }}">
                            Agregar al carrito
                            <span class="h5">S/.
                                <span data-base-price="{{ isset($defaultProductType->price) ? $defaultProductType->price : $product->price_default }}" id="product-price">{{ isset($defaultProductType->price) ? $defaultProductType->price : $product->price_default }}
                                </span>
                            </span>
                        </button>
                    </div>
                    <!-- thumbs-wrap.// -->
                    <!-- gallery-wrap .end// -->
                    <!-- Div fijo para móviles -->
                    <div class="mobile-fixed-cart d-lg-none">
                        <button type="button" class="btn btn-danger btn-block py-3"
                                id="add-to-cart-btn-mobile"
                                data-product-category="{{ $product->category_id }}"
                                data-product-id_v2="{{ $product->id }}"
                                data-product-id="{{ $product->slug }}"
                                data-auth-check-url="{{ route('auth.check') }}"
                                data-add-cart-url="{{ route('cart.manage') }}">
                            Agregar al carrito
                            <span class="h5">S/.
                                <span data-base-price="{{ isset($defaultProductType->price) ? $defaultProductType->price : $product->price_default }}" id="product-price-mobile">{{ isset($defaultProductType->price) ? $defaultProductType->price : $product->price_default }}
                                </span>
                            </span>
                        </button>
                    </div>
                </aside>
                <main class="col-lg-5">
                    <div class="ps-lg-3">

                        <div id="accordionExample">
                            <!-- Tamaños -->
                            @if (count($productTypes) > 0)
                            <div class="card">
                                <div class="card-header toggle-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <h5 class="mb-0 d-flex justify-content-between align-items-center">
                                        <span>Tamaños</span>
                                        <i class="fas fa-chevron-down toggle-icon"></i>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse custom-collapse show" aria-labelledby="headingOne">
                                    <div class="card-body">
                                        <div class="pizza-options">
                                            @foreach($productTypes as $productType)
                                                <div class="radio-button" data-value="{{ $productType->id }}">
                                                    <input type="radio" name="pizza-size" value="{{ $productType->id }}" data-price="{{ $productType->price }}" {{ $productType->default ? 'checked' : '' }}>
                                                    <span class="check-icon"><i class="fas fa-check-circle"></i></span>
                                                    <div class="content">
                                                        <span class="size">{{ $productType->type->name }} </span>
                                                        <span class="slices">{{ ($productType->type->size == null) ? "":"(".$productType->type->size.")" }}</span>
                                                        <span class="price">S/ {{ $productType->price }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Opciones -->
                            @if(isset($product->options) && count($product->options) > 0)
                            <div class="card">
                                <div class="card-header toggle-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    <h5 class="mb-0 d-flex justify-content-between align-items-center">
                                        <span>Opciones para seleccionar</span>
                                        <i class="fas fa-chevron-down toggle-icon"></i>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse custom-collapse show" aria-labelledby="headingTwo">
                                    <div class="card-body scrollable-container">
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
                                                            @if ($selection->product->enable_status == 1)
                                                                <div class="form-check mb-2 custom-radio-checkbox">
                                                                    <input class="form-check-input option-input"
                                                                           type="checkbox"
                                                                           data-option_id="{{$option->id}}"
                                                                           data-selection_id="{{$selection->id}}"
                                                                           data-selection_product_price="{{ isset($selection->product_price) ? $selection->product_price : 0 }}"
                                                                           data-selection_product_name="{{$selection->product->full_name}}"
                                                                           data-selection_price="{{$selection->additional_price}}"
                                                                           data-selection_product_id="{{$selection->product_id}}"
                                                                           name="option_{{ $option->id }}[]"
                                                                           value="{{ $selection->product_id }}"
                                                                           id="checkbox_{{ $option->id }}_{{ $loop->index }}" />
                                                                    <label class="form-check-label" for="checkbox_{{ $option->id }}_{{ $loop->index }}">
                                                                        {{ $selection->product->full_name }}
                                                                        @if ($selection->additional_price > 0)
                                                                            <span class="text-muted">( + S/. {{ number_format($selection->additional_price, 2) }} )</span>
                                                                        @endif
                                                                    </label>
                                                                </div>
                                                            @endif
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
                                </div>
                            </div>
                            @endif

                            <!-- Adicionales -->
                            @if (count($adicionales) > 0)
                            <div class="card">
                                <div class="card-header toggle-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                    <h5 class="mb-0 d-flex justify-content-between align-items-center">
                                        <span>Adicionales </span>
                                        <i class="fas fa-chevron-down toggle-icon"></i>
                                    </h5>
                                </div>
                                <div id="collapseThree" class="collapse custom-collapse show" aria-labelledby="headingThree">
                                    <div class="card-body scrollable-container">
                                        <div class="custom-checkbox-group">
                                            <!-- Opción 1 -->
                                            @foreach ($adicionales as $adicional)
                                            <label class="custom-checkbox-container {{ $loop->index >= 4 ? 'hidden-elements' : '' }}" for="topping{{$adicional->id}}">
                                                <input type="checkbox" data-product_id = "{{$adicional->id}}" data-price="{{ $adicional->price_default }}" data-product_name="{{ $adicional->full_name }}" id="topping{{$adicional->id}}" class="custom-checkbox-input">
                                                <span class="checkbox-box"></span>
                                                <div class="custom-checkbox-content">
                                                    <img src="{{ asset('images/products/'.$adicional->image) }}" alt="{{ $adicional->full_name }}">
                                                    <div class="custom-checkbox-details">
                                                        <span class="topping-name">{{ $adicional->full_name }}</span>
                                                        <span class="topping-price">+S/. {{ $adicional->price_default }}</span>
                                                    </div>
                                                </div>
                                            </label>
                                            @endforeach
                                            @if(count($adicionales) > 4)
                                                <button id="showMoreBtn" class="btn btn-link">Ver más</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif


                        </div>

                        <hr />

                        <div class="row mb-2">
                            {{--@if (count($productTypes) > 0)
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
                            @endif--}}
                            <!-- col.// -->
                            {{--<div class="col-md-4 col-6 mb-3">
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
                            </div>--}}
                        </div>
                    </div>
                </main>
            </div>
            {{--<div class="row">
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
            </div>--}}
        </div>
    </section>
    <!-- content -->

@endsection

@section('scripts')

    <script>
        let $selectedAdditions = [];
        $(document).ready(function () {
            if (/Mobi|Android/i.test(navigator.userAgent)) {
                $(".custom-checkbox-input").addClass("no-focus");
            }

            $(".custom-checkbox-input").on("focus", function (e) {
                e.preventDefault(); // Previene el comportamiento de foco
                $("#clickHere").blur(); // Quita el foco del checkbox
            });

            // Aplica la clase 'active' al contenedor del radio button ya seleccionado
            $(".radio-button input[type='radio']:checked").each(function () {
                $(this).closest(".radio-button").addClass("active");
                console.log("Clases del div contenedor:", $(this).closest(".radio-button").attr("class"));
            });

            // Maneja el evento de clic en el contenedor del radio button
            $(".radio-button").on("click", function () {
                // Elimina la clase 'active' de todos los contenedores
                $(".radio-button").removeClass("active");

                // Marca el radio button actual y su contenedor
                $(this).addClass("active");
                const radioInput = $(this).find("input[type='radio']");
                radioInput.prop("checked", true);

                // Dispara manualmente el evento 'change' del radio
                radioInput.trigger("change");
            });

            // Manejar el cambio de icono
            $(".toggle-header").on("click", function () {
                const target = $(this).attr("data-target");
                $(target).collapse("toggle");
            });

            // Cambiar el estado del icono dinámicamente
            $(".collapse").on("show.bs.collapse", function () {
                $(this).prev(".card-header").attr("aria-expanded", "true").find(".toggle-icon").addClass("rotate");
            });

            $(".collapse").on("hide.bs.collapse", function () {
                $(this).prev(".card-header").attr("aria-expanded", "false").find(".toggle-icon").removeClass("rotate");
            });



            // Evento cuando se hace clic en un checkbox
            $(".custom-checkbox-input").on("change", function () {
                const container = $(this).closest(".custom-checkbox-container");
                const productId = $(this).data("product_id");
                const productName = $(this).data("product_name");
                const productPrice = parseFloat($(this).data("price")); // Asegurarse de que sea número

                if ($(this).is(":checked")) {
                    // Añadir el adicional al array
                    $selectedAdditions.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                    });

                    container.addClass("selected"); // Añade la clase cuando está seleccionado
                } else {
                    // Remover el adicional del array
                    $selectedAdditions = $selectedAdditions.filter(item => item.id !== productId);
                    $(this).addClass("no-focus");
                    container.removeClass("selected"); // Remueve la clase cuando no está seleccionado
                }

                // Simula un clic o cambio de foco
                $(this).blur(); // Intenta quitar el foco
                setTimeout(() => {
                    $("#clickHere").trigger("click"); // Simula un clic en el body
                }, 50);

                // Actualizar los precios dinámicamente
                updatePrices();
            });

            // Evento del Ver Mas
            $('#showMoreBtn').on('click', function() {
                const hiddenElements = $('.hidden-elements');

                hiddenElements.toggleClass('visible'); // Alterna la clase visible

                // Cambiar el texto del botón
                $(this).text($(this).text() === 'Ver más' ? 'Ver menos' : 'Ver más');
            });


        });

        // Función para actualizar los precios en el DOM
        function updatePrices() {
            // Obtener el precio base de los spans
            let basePrice = parseFloat($("#product-price").attr("data-base-price")) || 0;

            // Calcular el precio total sumando los adicionales
            let additionsTotal = $selectedAdditions.reduce((total, item) => total + item.price, 0);
            let totalPrice = basePrice + additionsTotal;

            // Actualizar los elementos del precio
            $("#product-price, #product-price-mobile").text(totalPrice.toFixed(2));
        }

    </script>
    <script src="{{ asset('js/product/showV3.js') }}?v={{ time() }}"></script>
@endsection
