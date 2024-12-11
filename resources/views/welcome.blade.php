<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-RLQWDGJ81N"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-RLQWDGJ81N');
        </script>
        <!-- Basic -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- Site Metas -->
        <title>FUEGO Y MASA | La Mejor Pizza en Trujillo - Delivery Rápido y Delicioso</title>
        <meta name="description" content="Descubre las pizzas más deliciosas en Trujillo. FUEGO Y MASA ofrece delivery rápido, promociones irresistibles y calidad insuperable. ¡Haz tu pedido ahora!">
        <meta name="keywords" content="pizzas Trujillo, delivery pizza Trujillo, pizzerías en Trujillo, FUEGO Y MASA, mejor pizza Trujillo">
        <meta name="author" content="FUEGO Y MASA">
        <meta name="robots" content="index, follow">

        <!-- Open Graph para redes sociales -->
        <meta property="og:title" content="FUEGO Y MASA | La Mejor Pizza en Trujillo">
        <meta property="og:description" content="Disfruta las mejores pizzas en Trujillo. Delivery rápido y promociones exclusivas en FUEGO Y MASA. ¡Prueba el sabor que encanta!">
        <meta property="og:image" content="https://www.fuegoymasa.com/images/pizza-destacada.jpg">
        <meta property="og:url" content="https://www.fuegoymasa.com">
        <meta property="og:type" content="website">
        <meta property="og:locale" content="es_PE">

        <!-- Twitter Cards -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="FUEGO Y MASA | La Mejor Pizza en Trujillo">
        <meta name="twitter:description" content="Haz tu pedido online en FUEGO Y MASA y disfruta de las mejores pizzas en Trujillo. ¡Promociones especiales todos los días!">
        <meta name="twitter:image" content="https://www.fuegoymasa.com/images/pizza-destacada.jpg">

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('landing/images/favicon.png') }}" type="image/x-icon">

        <!-- Canonical URL -->
        <link rel="canonical" href="https://www.fuegoymasa.com">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" href="{{ asset('landing/images/favicon.png') }}" type="">

        <title> Fuego y Masa </title>

        <!-- bootstrap core css -->
        <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/bootstrap.css') }}" />

        <!--owl slider stylesheet -->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
        <!-- nice select  -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
        <!-- font awesome style -->
        <link href="{{ asset('landing/css/fontawesome-all.min.css') }}" rel="stylesheet" />

        <!-- Custom styles for this template -->
        <link href="{{ asset('landing/css/style.css') }}" rel="stylesheet" />
        <!-- responsive style -->
        <link href="{{ asset('landing/css/responsive.css') }}" rel="stylesheet" />

    </head>
    <body>
        <div class="hero_area">
            <div class="bg-box">
                <img src="{{ asset('landing/images/hero-bg2.jpg') }}" alt="">
            </div>
            <!-- header section strats -->
            <header class="header_section">
                <div class="container">
                    <nav class="navbar navbar-expand-lg custom_nav-container ">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <span>
                              Fuego y Masa
                            </span>
                        </a>

                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class=""> </span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav  mx-auto ">
                                <li class="nav-item active">
                                    <a class="nav-link" href="{{ url('/') }}">Inicio <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('home') }}">Menu</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('about') }}">Nosotros</a>
                                </li>
                                @auth
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('orders.index') }}">Pedidos</a>
                                    </li>
                                @endauth
                            </ul>
                            <div class="user_option">
                                @if (Route::has('login'))
                                    @auth
                                        {{--<a href="{{ url('/home') }}">Usuario</a>--}}
                                        <a href="{{ route('home') }}" class="user_link">
                                            {{ Auth::user()->name }}
                                        </a>
                                        <a title="Cerrar sesión" class="user_link" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt" style="color: #ff0000;"></i>
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="user_link">
                                            <i class="fas fa-user"></i>
                                        </a>
                                    @endguest
                                @endif

                                <a class="cart_link" href="{{ route('cart.show') }}">
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                                        <g>
                                            <g>
                                                <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                                           c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                   C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                   c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                   C457.728,97.71,450.56,86.958,439.296,84.91z" />
                                            </g>
                                        </g>
                                        <g>
                                            <g>
                                                <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                   c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                                            </g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                        <g>
                                        </g>
                                    </svg>
                                </a>
                                @auth
                                    @if( Auth::user()->is_admin )
                                    <a href="{{ route('dashboard.principal') }}" class="order_online">
                                        Dashboard
                                    </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </nav>
                </div>
            </header>
            <!-- end header section -->
            <!-- slider section -->
            <section class="slider_section ">
                <div id="customCarousel1" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="container ">
                                <div class="row">
                                    <div class="col-md-7 col-lg-6 ">
                                        <div class="detail-box">
                                            <h1>
                                                Paraíso Tropical
                                            </h1>
                                            <p>
                                                Disfruta de una pizza ligera llena de frescura. Margarita del Campo realza la esencia de la albahaca fresca sobre una capa de queso derretido y salsa de tomate, en un bocado que celebra lo natural.
                                            </p>
                                            <div class="btn-box">
                                                <a href="{{ route('home') }}" class="btn1">
                                                    Ordena ahora
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item ">
                            <div class="container ">
                                <div class="row">
                                    <div class="col-md-7 col-lg-6 ">
                                        <div class="detail-box">
                                            <h1>
                                                Aloha Suprema
                                            </h1>
                                            <p>
                                                Una explosión de sabores tropicales. La Aloha Suprema combina el clásico jamón y piña con el toque único del durazno dulce y la intensidad de la aceituna verde. Un festín para los sentidos en cada rebanada, que lleva la experiencia hawaiana a nuevas alturas.
                                            </p>
                                            <div class="btn-box">
                                                <a href="{{ route('home') }}"  class="btn1">
                                                    Ordena ahora
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="container ">
                                <div class="row">
                                    <div class="col-md-7 col-lg-6 ">
                                        <div class="detail-box">
                                            <h1>
                                                Chorizo del Huerto
                                            </h1>
                                            <p>
                                                Fusión perfecta de lo intenso y lo fresco. Chorizo del Huerto une el chorizo ahumado con aceitunas verdes sobre una base de salsa de tomate y queso, creando un bocado lleno de sabor y un toque de frescura en cada mordisco.
                                            </p>
                                            <div class="btn-box">
                                                <a href="{{ route('home') }}" class="btn1">
                                                    Ordena ahora
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <ol class="carousel-indicators">
                            <li data-target="#customCarousel1" data-slide-to="0" class="active"></li>
                            <li data-target="#customCarousel1" data-slide-to="1"></li>
                            <li data-target="#customCarousel1" data-slide-to="2"></li>
                        </ol>
                    </div>
                </div>

            </section>
            <!-- end slider section -->
        </div>

        <!-- offer section -->

        <section class="offer_section layout_padding-bottom">
            <div class="offer_container">
                <div class="container ">
                    <div class="row">
                        <div class="col-md-6 offset-sm-3">
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
                        {{--<div class="col-md-6  ">
                            <div class="box ">
                                <div class="img-box">
                                    <img src="{{ asset('landing/images/o2.jpg') }}" alt="">
                                </div>
                                <div class="detail-box">
                                    <h5>
                                        Pizza Days
                                    </h5>
                                    <h6>
                                        <span>15%</span> Off
                                    </h6>
                                    <a href="">
                                        Order Now
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                                            <g>
                                                <g>
                                                    <path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248
                                             c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z" />
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48
                     C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064
                     c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4
                     C457.728,97.71,450.56,86.958,439.296,84.91z" />
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296
                     c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z" />
                                                </g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>--}}
                    </div>
                </div>
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
                            <div class="col-sm-6 col-lg-4 all category{{ $product->category_id }}">
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
                                                {{--<a href="{{ route('product.show', ['id' => $product->id]) }}"
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
                                                </a>--}}
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
                            </div>
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

        <!-- footer section -->
        <footer class="footer_section">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 footer-col">
                        <div class="footer_contact">
                            <h4>
                                Contáctanos
                            </h4>
                            <div class="contact_link_box">
                                <a href="">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    <span>
                                        Trujillo
                                    </span>
                                </a>
                                <a href="https://wa.me/51906343258?text=Hola%20FUEGO%20Y%20MASA,%20quiero%20comprar%20una%20pizza.%20%F0%9F%8D%95" target="_blank">
                                    <i class="fab fa-whatsapp" aria-hidden="true"></i>
                                    <span>
                                        Whatsapp  906-343-258
                                    </span>
                                </a>
                                <a href="mailto:fuegoymasaperu@gmail.com" target="_blank">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    <span>
                                        fuegoymasaperu@gmail.com
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 footer-col">
                        <div class="footer_detail">
                            <a href="" class="footer-logo">
                                Fuego y Masa
                            </a>
                            <p>
                                Encendemos la pasión en cada creación, con ingredientes frescos y dedicación, para llevar calidad y sabor a cada mesa.
                            </p>
                            <div class="footer_social">
                                <a href="https://www.facebook.com/people/Fuego-y-Masa/61568065745757/" target="_blank">
                                    <i class="fab fa-facebook"></i>
                                </a>
                                <a href=" https://www.linkedin.com/company/fuego-y-masa" target="_blank">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="https://www.instagram.com/fuegoymasaperu/" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="https://wa.me/51906343258?text=Hola%20FUEGO%20Y%20MASA,%20quiero%20comprar%20una%20pizza.%20%F0%9F%8D%95" target="_blank">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="https://www.tiktok.com/@fuegoymasa" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="enable-background:new 0 0 456.029 456.029;padding: 7px;">
                                        <path d="M448,209.2c-5.4,0.5-10.8,0.8-16.2,0.8c-55.3,0-102.4-36.2-118.8-85.5v204c0,65.4-53,118.4-118.4,118.4c-65.4,0-118.4-53-118.4-118.4c0-63.1,49.3-114.6,111.4-118.2v62.6c-27.3,3.4-48.4,26.7-48.4,55.6c0,30.8,25,55.8,55.8,55.8c30.8,0,55.8-25,55.8-55.8V0h62.6c8.5,59,58.6,104.7,118.4,104.7V209.2z"/>
                                    </svg>
                                </a>                             
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 footer-col">
                        <h4>
                            Horarios
                        </h4>
                        <p>
                            Lunes a Viernes
                        </p>
                        <p>
                            6:30 PM - 11:00 PM
                        </p>
                        
                        <p>
                            Sábados y Domingos
                        </p>
                        <p>
                            3:30 PM - 11:30 Pm
                        </p>
                        
                    </div>
                </div>
                <div class="footer-info">
                    <p>
                        &copy; <span id="displayYear"></span> All Rights Reserved By
                        <a href="https://edesce.com/">EDESCE</a><br><br>
                        &copy; <span id="displayYear"></span> Distributed By
                        <a href="https://edesce.com/" target="_blank">EDESCE</a>
                    </p>
                </div>
            </div>
        </footer>
        <!-- footer section -->

        <div id="business-status" style="display: none; position: fixed; top: 20px; left: 20px; background-color: rgba(255, 0, 0, 0.9); border: 1px solid #cc0000; padding: 15px; border-radius: 5px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); z-index: 9999; color: #fff;">
            <button id="close-business-status" style="background: none; border: none; font-size: 16px; font-weight: bold; color: #fff; float: right; cursor: pointer;">&times;</button>
            <p id="business-message" style="margin: 0; font-size: 14px; font-weight: bold;"></p>
        </div>

        <!-- jQery -->
        <script src="{{ asset('landing/js/jquery-3.4.1.min.js') }}"></script>
        <!-- popper js -->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
        <!-- bootstrap js -->
        <script src="{{ asset('landing/js/bootstrap.js') }}"></script>
        <!-- owl slider -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
        </script>
        <!-- isotope js -->
        <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
        <!-- nice select -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
        <!-- custom js -->
        <script src="{{ asset('landing/js/custom.js') }}"></script>
        <!-- Google Map -->
        {{--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
        </script>--}}
        <!-- End Google Map -->
        <script src="{{ asset('landing/js/welcome.js') }}"></script>

    </body>
</html>
