@extends('layouts.app')

@section('menu-active', 'active')

@section('styles')
    <style>
        .sin-padding {
            height: 250px !important;
        }

        .card-text, .card-title, .fw-bold {
            font-size: 14px;
        }
        /* Ajuste del tamaño de fuente según el tamaño del dispositivo */
        .description-text {
            font-size: 0.8rem; /* Tamaño base */
        }

        @media (max-width: 768px) {
            .description-text {
                font-size: 0.9rem; /* Reducir tamaño en pantallas pequeñas */
            }
        }

        @media (max-width: 576px) {
            .description-text {
                font-size: 0.8rem; /* Más pequeño en pantallas extra pequeñas */
            }
        }

        /* Estilo para eliminar el color azul y mantener negro */
        .card-link {
            text-decoration: none; /* Elimina el subrayado */
            color: black; /* Color inicial */
        }

        .card-link:hover,
        .card-link:focus,
        .card-link:active {
            color: black; /* Mantiene negro en todos los estados */
        }

        .card {
            height: 500px; /* Ajusta este valor según lo que necesites */
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Distribuye el contenido de manera uniforme */
            overflow: hidden; /* Oculta cualquier contenido extra */
        }
        .card-body {

            overflow: hidden; /* Asegura que el contenido largo no sobresalga */
        }
    </style>
@endsection

@section('content')
    <!-- offer section -->
    <section class="offer_section layout_padding-bottom pt-0">
        <div class="offer_container">
            <div class="row">
                <div class="col-md-12">
                    @if ($slidersLarges->isNotEmpty())
                        <div id="carouselLarge" class="carousel slide d-none d-md-block" data-ride="carousel">
                            <ol class="carousel-indicators">
                                {{--<li data-target="#carouselLarge" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselLarge" data-slide-to="1"></li>
                                <li data-target="#carouselLarge" data-slide-to="2"></li>--}}
                                @foreach ($slidersLarges as $index => $slider)
                                    <li data-target="#carouselLarge" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @foreach ($slidersLarges as $index => $slider)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        @if (!empty($slider->link))
                                            <a href="{{ $slider->link }}">
                                                <img src="{{ asset('images/slider/' . $slider->image) }}" class="d-block w-100" alt="...">
                                            </a>
                                        @else
                                            <img src="{{ asset('images/slider/' . $slider->image) }}" class="d-block w-100" alt="...">
                                        @endif
                                    </div>
                                @endforeach
                                {{--<div class="carousel-item active">
                                    <a href="http://restaurant.site:8080/producto/margarita-del-campo">
                                        <img src="{{ asset('images/slider/slide1_g.webp') }}" class="d-block w-100" alt="...">
                                    </a>
                                </div>

                                <div class="carousel-item">
                                    <img src="{{ asset('images/slider/slide1_g.webp') }}" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/slider/slide1_g.webp') }}" class="d-block w-100" alt="...">
                                </div>--}}
                            </div>
                            <a class="carousel-control-prev" href="#carouselLarge" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselLarge" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    @endif
                    <!-- Carrusel para dispositivos pequeños -->
                    @if ($slidersSmalls->isNotEmpty())
                        <div id="carouselSmall" class="carousel slide d-block d-md-none" data-ride="carousel">
                            <ol class="carousel-indicators">
                                {{--<li data-target="#carouselSmall" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselSmall" data-slide-to="1"></li>
                                <li data-target="#carouselSmall" data-slide-to="2"></li>--}}
                                @foreach ($slidersSmalls as $index => $slider)
                                    <li data-target="#carouselSmall" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
                                @endforeach
                            </ol>
                            <div class="carousel-inner">
                                @foreach ($slidersSmalls as $index => $slider)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        @if (!empty($slider->link))
                                            <a href="{{ $slider->link }}">
                                                <img src="{{ asset('images/slider/' . $slider->image) }}" class="d-block w-100" alt="...">
                                            </a>
                                        @else
                                            <img src="{{ asset('images/slider/' . $slider->image) }}" class="d-block w-100" alt="...">
                                        @endif
                                    </div>
                                @endforeach
                                {{--<div class="carousel-item active">
                                    <a href="http://restaurant.site:8080/producto/margarita-del-campo">
                                        <img src="{{ asset('images/slider/slide1_p.webp') }}" class="d-block w-100" alt="...">
                                    </a>
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/slider/slide1_p.webp') }}" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('images/slider/slide1_p.webp') }}" class="d-block w-100" alt="...">
                                </div>--}}
                            </div>
                            <a class="carousel-control-prev" href="#carouselSmall" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselSmall" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            {{--<div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="box ">
                        <div class="img-box">
                            <img src="{{ asset('images/products/7.png') }}" alt="">
                        </div>
                        <div class="detail-box">
                            <h5>
                                <b>¿Es tu primera pizza?</b>
                            </h5>
                            <h6>
                                <span>50%</span> descuento
                            </h6>
                            <a href="{{ route('home') }}" class="text-center">
                                Usa el codigo <b>MiPrimeraPizza</b>
                            </a>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
    </section>

    <!-- end offer section -->

    <!-- food section -->

    <section class="food_section layout_padding-bottom">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>
                    Galería de Sabores
                </h2>
            </div>

            <ul class="filters_menu">
                <li class="active" data-filter="*">Todos</li>
                @foreach($categories as $category)
                    <li data-filter=".category{{ $category->id }}">{{ $category->name }}</li>
                @endforeach
            </ul>

            <div class="filters-content">
                <div class="row grid">
                    @foreach($products as $product)
                        <div class="col-12 col-sm-6 col-lg-3 mb-2 all category{{ $product->category_id }}">
                            <a href="{{ route('product.show', $product->slug) }}" class="card-link">
                                <div class="card">
                                    <img src="{{ asset('images/products/'.$product->image) }}" class="card-img-top" alt="{{ $product->full_name }}">
                                    <div class="card-body d-flex flex-column p-3">
                                        <h5 class="card-title text-black fw-bold" style="font-size: 1.2rem; font-weight: bold; text-transform: uppercase;">
                                            {{ $product->full_name }}
                                        </h5>
                                        <p class="card-text flex-grow-1 description-text">
                                            {{ \Illuminate\Support\Str::limit($product->description, 80, '...') }}
                                        </p>
                                        <div class="mt-3">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-sm-5 col-lg-5 mb-2">
                                                    <span style="font-size: 0.9rem;">Desde</span><br>
                                                    <span style="font-size: 1.2rem; color: red; font-weight: bold;">
                                                            S/ {{ $product->price_default }}
                                                        </span>
                                                </div>
                                                <div class="col-12 col-sm-7 col-lg-7">
                                                    <button class="btn btn-danger w-100 btn-lg" style="padding: 15px 0; font-size: 1rem;">
                                                        Agregar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        {{--<div class="col-sm-6 col-lg-4 all category{{ $product->category_id }}">
                            <div class="box">
                                <div>
                                    <div class="img-box">
                                        <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->full_name }}">
                                    </div>
                                    <div class="detail-box">
                                        <h5>{{ $product->full_name }}</h5>
                                        <p>
                                            {{ \Illuminate\Support\Str::limit($product->description, 150, '...') }}
                                            <a href="{{ route('product.show', $product->slug) }}">Ver detalles</a>

                                        </p>
                                        <div class="options">
                                            <h6>Desde S/. {{ $product->price_default }}</h6>
                                            --}}{{--<a href="{{ route('product.show', ['id' => $product->id]) }}"
                                                   data-auth-check-url="{{ route('auth.check') }}"
                                                   onclick="event.preventDefault(); checkAuthentication({{ $product->id }}, this);">
                                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                                        <g>
                                            <g>
                                                <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                                            </g>
                                        </g>
                                                        <g>
                                                            <g>
                                                                <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4C457.728,97.71,450.56,86.958,439.296,84.91z" />
                                                            </g>
                                                        </g>
                                                        <g>
                                                            <g>
                                                                <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                                                            </g>
                                                        </g>
                                    </svg>
                                                </a>--}}{{--
                                            <a href="{{ route('product.show', $product->slug) }}">
                                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                                        <g>
                                            <g>
                                                <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                                            </g>
                                        </g>
                                                    <g>
                                                        <g>
                                                            <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4C457.728,97.71,450.56,86.958,439.296,84.91z" />
                                                        </g>
                                                    </g>
                                                    <g>
                                                        <g>
                                                            <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                                                        </g>
                                                    </g>
                                    </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>--}}
                    @endforeach
                </div>
            </div>
            {{--<div class="btn-box">
                <a href="">
                    View More
                </a>
            </div>--}}
        </div>
    </section>

    <!-- end food section -->
@endsection

@section('scripts')
    <script src="{{ asset('landing/js/welcome.js') }}"></script>
    <script>
        $('#Carousel').carousel({
            interval: 5000
        })
    </script>
@endsection