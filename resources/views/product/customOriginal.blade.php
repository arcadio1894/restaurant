@extends('layouts.app')

@section('menu-active', 'active')

@section('text-header')
    <h2 class="pt-5">
        Personaliza tu Pizza
    </h2>

@endsection

@section('styles')
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.5.6/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('accordion/css/flaticon.css') }}">
    {{--<link rel="stylesheet" href="{{ asset('accordion/css/style.css') }}">--}}
    <style>

        .ftco-section {
            padding: 2em 0; }

        .ftco-no-pt {
            padding-top: 0; }

        .ftco-no-pb {
            padding-bottom: 0; }

        .heading-section {
            font-size: 28px;
            color: #000; }

        .img {
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center; }

        .myaccordion {
            margin: 0 auto;
            -webkit-box-shadow: 0px 10px 29px -16px rgba(0, 0, 0, 0.12);
            -moz-box-shadow: 0px 10px 29px -16px rgba(0, 0, 0, 0.12);
            box-shadow: 0px 10px 29px -16px rgba(0, 0, 0, 0.12);
            background: #fff;
            border-radius: 5px;
            overflow: hidden; }

        .wrap {
            background: #e69c00;
        }
        .wrap h3 {
            color: #fff;
        }

        .myaccordion .card,
        .myaccordion .card:last-child .card-header {
            border: none;
            background: transparent; }

        .myaccordion .card-header {
            border: none;
            background: transparent;
            text-align: left;
        }
        .myaccordion .card-header p {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 1.2rem;
            font-weight: 400;
            position: relative;
            padding-left: 60px;
            z-index: 0;
            text-align: left;
        }
        .myaccordion .card-header p .icon {
            position: absolute;
            top: 0;
            left: 0;
            font-size: 36px;
            margin-top: -10px;
            z-index: 0;
        }
        .myaccordion .card-header p .icon:after {
            position: absolute;
            top: 50%;
            left: 0;
            content: '';
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #feba2c;
            z-index: -1;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%); }

        .myaccordion .fa {
            font-size: 16px;
            color: rgba(0, 0, 0, 0.3); }

        .myaccordion .btn {
            width: 100%;
            font-weight: normal;
            color: #000;
            padding: 0;
            letter-spacing: 0;
            font-size: 18px;
            border-radius: 0 !important;
            -webkit-box-shadow: 0px 8px 18px -16px rgba(0, 0, 0, 0.19);
            -moz-box-shadow: 0px 8px 18px -16px rgba(0, 0, 0, 0.19);
            box-shadow: 0px 8px 18px -16px rgba(0, 0, 0, 0.19); }
        .myaccordion .btn:hover {
            -webkit-box-shadow: 0px 8px 18px -16px rgba(0, 0, 0, 0.19) !important;
            -moz-box-shadow: 0px 8px 18px -16px rgba(0, 0, 0, 0.19) !important;
            box-shadow: 0px 8px 18px -16px rgba(0, 0, 0, 0.19) !important; }
        .myaccordion .btn:focus {
            -webkit-box-shadow: 0px 12px 18px -16px rgba(0, 0, 0, 0.37) !important;
            -moz-box-shadow: 0px 12px 18px -16px rgba(0, 0, 0, 0.37) !important;
            box-shadow: 0px 12px 18px -16px rgba(0, 0, 0, 0.37) !important; }

        .myaccordion .btn-link:hover,
        .myaccordion .btn-link:focus {
            text-decoration: none; }

        [data-toggle="collapse"] .fa:before {
            content: "\f3d8";
            font-family: "Ionicons";
            font-style: normal; }

        [data-toggle="collapse"].collapsed .fa:before {
            content: "\f3d0";
            font-family: "Ionicons";
            font-style: normal; }

        .card-body {
            background: rgba(0, 0, 0, 0.02); }
        .card-body .menus {
            width: 100%;
            padding: 0;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 5px;
            -webkit-transition: 0.3s;
            -o-transition: 0.3s;
            transition: 0.3s; }
        /*@media (prefers-reduced-motion: reduce) {
            .card-body .menus {
                -webkit-transition: none;
                -o-transition: none;
                transition: none; } }*/
        .card-body .menus:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border: none; }
        .card-body .menus .text {
            width: calc(100% - 100px);
            padding-left: 20px; }
        @media (max-width: 767.98px) {
            .card-body .menus .text {
                width: 100%;
                padding-left: 0; } }
        .card-body .menus .text .one-half {
            width: calc(100% - 110px); }
        .card-body .menus .text .one-half h3 {
            font-size: 22px;
            font-weight: 500; }
        .card-body .menus .text .one-forth {
            width: 110px;
            text-align: right; }
        .card-body .menus .text .one-forth .price {
            font-size: 20px;
            color: rgba(0, 0, 0, 0.2);
            color: #000;
            font-weight: 600;
            line-height: 1.3;
            -webkit-transition: 0.3s;
            -o-transition: 0.3s;
            transition: 0.3s;
            border-bottom: 2px solid #feba2c; }
        /*@media (prefers-reduced-motion: reduce) {
            .card-body .menus .text .one-forth .price {
                -webkit-transition: none;
                -o-transition: none;
                transition: none; } }*/
        .card-body .menus .text p {
            margin-bottom: 0;
            color: rgba(0, 0, 0, 0.4); }
        .card-body .menus .menu-img {
            width: 100px;
            height: 100px;
            border-radius: 50%; }
        @media (max-width: 767.98px) {
            .card-body .menus .menu-img {
                margin-bottom: 20px; } }

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

        /* Estilo para los botones */
        .btn-group-toggle .btn {
            background-color: transparent; /* Fondo transparente */
            border-color: #ccc; /* Borde gris */
            color: #333; /* Color de texto */
            padding: 6px 12px; /* Espacio alrededor del texto */
            font-size: 16px; /* Aumentar el tamaño de la fuente */
            border-radius: 5px; /* Bordes redondeados */
            transition: background-color 0.3s, border-color 0.3s, color 0.3s;
        }

        /* Estilo para el radio seleccionado */
        .btn-group-toggle .btn.active,
        .btn-group-toggle .btn:active {
            background-color: #e69c00 !important; /* Fondo de color cuando está seleccionado */
            border-color: #e69c00 !important; /* Borde del mismo color */
            color: white; /* Color de texto cuando está seleccionado */
        }

        /* Hover effect para los botones */
        .btn-group-toggle .btn:hover {
            background-color: #f1f1f1; /* Color de fondo al pasar el ratón */
        }


        input[name=interruptor]{
            display: none;
        }

        input[name=interruptorQ]{
            display: none;
        }

        label{
            color: transparent;
            width: 100%;
            height: 100%;
        }

        .interruptor-cuerpo{
            width: 40px;
            height: 22px;
            box-shadow: 0px 0px 5px #8A8A8A inset;
            border-radius: 10px;
            background-color: white;
            transition: background-color .25s;
        }

        .interruptor-tecla{
            position: relative;
            left:0;
            text-align: center;
            width: 22px;
            height: 22px;
            box-shadow: 0 0 2px #8A8A8A;
            background-color: white;
            border-radius: 50%;
            transition: left .25s;
        }

        #apagado:checked ~ .interruptor-cuerpo .interruptor-tecla{
            left:0px;
        }

        #apagado:checked ~ .interruptor-cuerpo .interruptor-tecla label[for=apagado]{
            display: none
        }

        #prendido:checked ~ .interruptor-cuerpo .interruptor-tecla{
            left:20px;
        }

        #prendido:checked ~ .interruptor-cuerpo{
            background-color: #A2DA6C;
        }

        #prendido:checked ~ .interruptor-cuerpo .interruptor-tecla label[for=prendido]{
            display: none
        }

        /* QUESO */
        #apagadoQ:checked ~ .interruptor-cuerpo .interruptor-tecla{
            left:0px;
        }

        #apagadoQ:checked ~ .interruptor-cuerpo .interruptor-tecla label[for=apagadoQ]{
            display: none
        }

        #prendidoQ:checked ~ .interruptor-cuerpo .interruptor-tecla{
            left:20px;
        }

        #prendidoQ:checked ~ .interruptor-cuerpo{
            background-color: #A2DA6C;
        }

        #prendidoQ:checked ~ .interruptor-cuerpo .interruptor-tecla label[for=prendidoQ]{
            display: none
        }

        /* Contenedor para alinear en una sola línea */
        .switch-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px; /* Espacio entre el interruptor y el texto */
            font-size: 18px; /* Tamaño del texto */
        }

        /* Estilo para el span que contiene el texto "CON SALSA" o "SIN SALSA" */
        #salsaText {
            font-weight: bold;
            margin-left: 10px;
        }



        /* Ocultar los radios en los botones de pizza */
        .custom-pizza-group .custom-pizza-btn input[type="radio"] {
            display: none;
        }

        /* Ajuste de los botones con clases personalizadas */
        .custom-pizza-group .custom-pizza-btn {
            padding: 5px;
            margin: 0; /* Eliminamos el margen entre los botones */
            width: 60px; /* Reducido para que todo sea más compacto */
            text-align: center;
            font-size: 12px;
        }

        .custom-pizza-group .custom-pizza-btn img {
            width: 30px; /* Reducido el tamaño de las imágenes */
            height: 30px;
            margin-bottom: 5px;
        }

        /* Alineación en línea de los botones y el interruptor */
        .custom-pizza-group {
            display: flex;
            justify-content: flex-start; /* Alinea los botones hacia la izquierda */
            align-items: center;
            gap: 5px; /* Espacio entre los botones */
        }

        /* Asegura que los botones estén alineados horizontalmente y no ocupen más espacio del necesario */
        .custom-pizza-group .custom-pizza-btn p {
            margin: 0;
        }



        /* Estilos para el contenedor y el interruptor */
        .switch-container_extra {
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .switch_extra {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            margin-right: 10px;
        }

        .switch_extra input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* Estilos para el slider */
        .slider_extra {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider_extra:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            border-radius: 50%;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
        }

        /* Cuando el interruptor está activado */
        input:checked + .slider_extra {
            background-color: #4CAF50; /* Color verde cuando está activado */
        }

        input:checked + .slider_extra:before {
            transform: translateX(26px); /* Mueve el círculo a la derecha */
        }

        /* Estilo para el texto */
        .switch-text_extra {
            font-weight: bold;
            transition: color 0.3s;
        }

        /* Cambiar el color del texto cuando el switch está activado */
        input:checked ~ #salsaText {
            color: #4CAF50; /* Color verde cuando está activado */
        }

        /* Cambiar el texto según el estado */
        input:not(:checked) ~ #salsaText {
            color: #ccc; /* Color gris cuando está desactivado */
        }

    </style>

@endsection

@section('content')
    <section class="py-2">
        <div class="container">
            <section class="ftco-section">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6">
                            <div id="accordion" class="myaccordion w-100">
                                <div class="p-2 wrap text-center">
                                    <h3>Selecciona</h3>
                                </div>
                                <div class="card">
                                    <div class="card-header p-0" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="d-flex py-4 px-4 align-items-center justify-content-between btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                <p class="mb-0"><img src="{{ asset('accordion/icons/pizza.png') }}" alt="Tamaño de pizza" class="icon" style="width: 40px; height: 40px;"> Tamaño</p>
                                                <i class="fa" aria-hidden="true"></i>
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body px-4">
                                            <div class="d-flex justify-content-center">
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-outline-secondary">
                                                        <input type="radio" name="exampleRadios" id="radio1" value="option1" autocomplete="off" /> Personal
                                                    </label>
                                                    <label class="btn btn-outline-secondary">
                                                        <input type="radio" name="exampleRadios" id="radio2" value="option2" autocomplete="off" /> Grande
                                                    </label>
                                                    <label class="btn btn-outline-secondary">
                                                        <input type="radio" name="exampleRadios" id="radio3" value="option3" autocomplete="off" /> Familiar
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header p-0" id="headingTwo">
                                        <h2 class="mb-0">
                                            <button class="d-flex py-4 px-4 align-items-center justify-content-between btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" id="accordionToggleButton">
                                                <p class="mb-0">
                                                    <img src="{{ asset('accordion/icons/salsa-de-tomate.png') }}" alt="Salsa de Tomate" class="icon" style="width: 40px; height: 40px;"> Salsa
                                                </p>
                                                <i class="fa" aria-hidden="true"></i>
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                        <div class="card-body px-1">
                                            <div class="switch-container">
                                                <input type="radio" name="interruptor" id="prendido">
                                                <input type="radio" name="interruptor" id="apagado" checked>

                                                <div class="interruptor-cuerpo">
                                                    <div class="interruptor-tecla">
                                                        <label for="prendido" title="Desactivado">___</label>
                                                        <label for="apagado" title="Activado">___</label>
                                                    </div>
                                                </div>

                                                <!-- Span para mostrar "CON SALSA" o "SIN SALSA" -->
                                                <span id="salsaText">SIN SALSA</span>
                                            </div>

                                            <!-- 3 botones que aparecerán cuando el interruptor esté activado -->
                                            <div id="extraOptions" style="display: none;">
                                                <div class="d-flex align-items-center justify-content-center mt-3" style="width: 100%;">
                                                    <div class="custom-pizza-group d-flex align-items-center mt-3" data-toggle="buttons">
                                                        <!-- Botón Izquierda -->
                                                        <label class="custom-pizza-btn btn btn-outline-secondary">
                                                            <input type="radio" name="pizzaPosition" id="pizzaIzquierda" autocomplete="off">
                                                            <img src="{{ asset('accordion/icons/pizzaLeft.png') }}" alt="Izquierda" class="img-fluid" style="width: 30px; height: 30px;">
                                                            <p>Izq.</p>
                                                        </label>

                                                        <!-- Botón Todo -->
                                                        <label class="custom-pizza-btn btn btn-outline-secondary">
                                                            <input type="radio" name="pizzaPosition" id="pizzaTodo" autocomplete="off">
                                                            <img src="{{ asset('accordion/icons/pizzaAll.png') }}" alt="Todo" class="img-fluid" style="width: 30px; height: 30px;">
                                                            <p>Todo</p>
                                                        </label>

                                                        <!-- Botón Derecha -->
                                                        <label class="custom-pizza-btn btn btn-outline-secondary">
                                                            <input type="radio" name="pizzaPosition" id="pizzaDerecha" autocomplete="off">
                                                            <img src="{{ asset('accordion/icons/pizzaRight.png') }}" alt="Derecha" class="img-fluid" style="width: 30px; height: 30px;">
                                                            <p>Der.</p>
                                                        </label>

                                                    </div>
                                                    <!-- Interruptor Extra -->
                                                    <div class="switch-container_extra ml-3 mt-3">
                                                        <label class="switch_extra">
                                                            <input type="checkbox" id="salsaSwitchExtra" />
                                                            <span class="slider_extra round"></span>
                                                        </label><br>
                                                        <span id="salsaSwitchExtra" class="switch-text_extra">Extra</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header p-0" id="headingThree">
                                        <h2 class="mb-0">
                                            <button class="d-flex py-4 px-4 align-items-center justify-content-between btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                <p class="mb-0"><img src="{{ asset('accordion/icons/queso.png') }}" alt="Tamaño de pizza" class="icon" style="width: 40px; height: 40px;"> Queso</p>
                                                <i class="fa" aria-hidden="true"></i>
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                        <div class="card-body py-5 px-4">
                                            <div class="switch-container">
                                                <input type="radio" name="interruptorQ" id="prendidoQ">
                                                <input type="radio" name="interruptorQ" id="apagadoQ" checked>

                                                <div class="interruptor-cuerpo">
                                                    <div class="interruptor-tecla">
                                                        <label for="prendidoQ" title="Desactivado">___</label>
                                                        <label for="apagadoQ" title="Activado">___</label>
                                                    </div>
                                                </div>

                                                <!-- Span para mostrar "CON SALSA" o "SIN SALSA" -->
                                                <span id="quesoText">SIN QUESO</span>
                                            </div>

                                            <!-- 3 botones que aparecerán cuando el interruptor esté activado -->
                                            <div id="extraOptionsQueso" style="display: none;">
                                                <div class="d-flex align-items-center justify-content-center mt-3" style="width: 100%;">
                                                    <div class="custom-pizza-group d-flex align-items-center mt-3" data-toggle="buttons">
                                                        <!-- Botón Izquierda -->
                                                        <label class="custom-pizza-btn btn btn-outline-secondary">
                                                            <input type="radio" name="quesoPosition" id="quesoIzquierda" autocomplete="off">
                                                            <img src="{{ asset('accordion/icons/izquierdaPizza.jpg') }}" alt="Izquierda" class="img-fluid" style="width: 30px; height: 30px;">
                                                            <p>Izq.</p>
                                                        </label>

                                                        <!-- Botón Todo -->
                                                        <label class="custom-pizza-btn btn btn-outline-secondary">
                                                            <input type="radio" name="quesoPosition" id="quesoTodo" autocomplete="off">
                                                            <img src="{{ asset('accordion/icons/todoPizza.jpg') }}" alt="Todo" class="img-fluid" style="width: 30px; height: 30px;">
                                                            <p>Todo</p>
                                                        </label>

                                                        <!-- Botón Derecha -->
                                                        <label class="custom-pizza-btn btn btn-outline-secondary">
                                                            <input type="radio" name="quesoPosition" id="quesoDerecha" autocomplete="off">
                                                            <img src="{{ asset('accordion/icons/derechaPizza.jpg') }}" alt="Derecha" class="img-fluid" style="width: 30px; height: 30px;">
                                                            <p>Der.</p>
                                                        </label>

                                                    </div>
                                                    <!-- Interruptor Extra -->
                                                    <div class="switch-container_extra ml-3 mt-3">
                                                        <label class="switch_extra">
                                                            <input type="checkbox" id="quesoSwitchExtra" />
                                                            <span class="slider_extra round"></span>
                                                        </label><br>
                                                        <span class="switch-text_extra">Extra</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header p-0" id="headingFour">
                                        <h2 class="mb-0">
                                            <button class="d-flex py-4 px-4 align-items-center justify-content-between btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                <p class="mb-0"><img src="{{ asset('accordion/icons/pepperoni.png') }}" alt="Carnes" class="icon" style="width: 40px; height: 40px;"> Carnes</p>
                                                <i class="fa" aria-hidden="true"></i>
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                                        <div class="card-body py-5 px-4">
                                            <div class="menus d-md-flex">
                                                <div class="menu-img img" style="background-image: url({{ asset('accordion/images/drink-1.jpg') }});"></div>
                                                <div class="text">
                                                    <div class="d-flex">
                                                        <div class="one-half">
                                                            <h3>Grilled Beef with potatoes</h3>
                                                        </div>
                                                        <div class="one-forth">
                                                            <span class="price">$29</span>
                                                        </div>
                                                    </div>
                                                    <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
                                                </div>
                                            </div>
                                            <div class="menus d-md-flex">
                                                <div class="menu-img img" style="background-image: url({{ asset('accordion/images/drink-2.jpg') }});"></div>
                                                <div class="text">
                                                    <div class="d-flex">
                                                        <div class="one-half">
                                                            <h3>Grilled Beef with potatoes</h3>
                                                        </div>
                                                        <div class="one-forth">
                                                            <span class="price">$29</span>
                                                        </div>
                                                    </div>
                                                    <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
                                                </div>
                                            </div>
                                            <div class="menus d-md-flex">
                                                <div class="menu-img img" style="background-image: url({{ asset('accordion/images/drink-3.jpg') }});"></div>
                                                <div class="text">
                                                    <div class="d-flex">
                                                        <div class="one-half">
                                                            <h3>Grilled Beef with potatoes</h3>
                                                        </div>
                                                        <div class="one-forth">
                                                            <span class="price">$29</span>
                                                        </div>
                                                    </div>
                                                    <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header p-0" id="headingFive">
                                        <h2 class="mb-0">
                                            <button class="d-flex py-4 px-4 align-items-center justify-content-between btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                                <p class="mb-0"><img src="{{ asset('accordion/icons/champinon.png') }}" alt="Carnes" class="icon" style="width: 40px; height: 40px;"> Vegetales</p>
                                                <i class="fa" aria-hidden="true"></i>
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                                        <div class="card-body py-5 px-4">
                                            <div class="menus d-md-flex">
                                                <div class="menu-img img" style="background-image: url({{ asset('accordion/images/wine-1.jpg') }});"></div>
                                                <div class="text">
                                                    <div class="d-flex">
                                                        <div class="one-half">
                                                            <h3>Grilled Beef with potatoes</h3>
                                                        </div>
                                                        <div class="one-forth">
                                                            <span class="price">$29</span>
                                                        </div>
                                                    </div>
                                                    <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
                                                </div>
                                            </div>
                                            <div class="menus d-md-flex">
                                                <div class="menu-img img" style="background-image: url({{ asset('accordion/images/wine-2.jpg') }});"></div>
                                                <div class="text">
                                                    <div class="d-flex">
                                                        <div class="one-half">
                                                            <h3>Grilled Beef with potatoes</h3>
                                                        </div>
                                                        <div class="one-forth">
                                                            <span class="price">$29</span>
                                                        </div>
                                                    </div>
                                                    <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
                                                </div>
                                            </div>
                                            <div class="menus d-md-flex">
                                                <div class="menu-img img" style="background-image: url({{ asset('accordion/images/wine-3.jpg') }});"></div>
                                                <div class="text">
                                                    <div class="d-flex">
                                                        <div class="one-half">
                                                            <h3>Grilled Beef with potatoes</h3>
                                                        </div>
                                                        <div class="one-forth">
                                                            <span class="price">$29</span>
                                                        </div>
                                                    </div>
                                                    <p><span>Meat</span>, <span>Potatoes</span>, <span>Rice</span>, <span>Tomatoe</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
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
            </div>
        </div>
    </section>
    <!-- content -->

@endsection

@section('scripts')
    <script src="{{ asset('accordion/js/main.js') }}"></script>
    <script src="{{ asset('js/product/showV2.js') }}?v={{ time() }}"></script>
    <script>
        document.getElementById('prendido').addEventListener('change', function() {
            const salsaText = document.getElementById('salsaText');
            if (this.checked) {
                salsaText.textContent = "CON SALSA"; // Cambiar a CON SALSA
                // Mostrar las opciones adicionales
                document.getElementById('extraOptions').style.display = 'block';
            }
        });

        document.getElementById('apagado').addEventListener('change', function() {
            const salsaText = document.getElementById('salsaText');
            if (this.checked) {
                salsaText.textContent = "SIN SALSA"; // Cambiar a SIN SALSA
                // Ocultar las opciones adicionales
                document.getElementById('extraOptions').style.display = 'none';
            }
        });

        document.getElementById('prendidoQ').addEventListener('change', function() {
            const salsaText = document.getElementById('quesoText');
            if (this.checked) {
                salsaText.textContent = "CON QUESO"; // Cambiar a CON SALSA
                // Mostrar las opciones adicionales
                document.getElementById('extraOptionsQueso').style.display = 'block';
            }
        });

        document.getElementById('apagadoQ').addEventListener('change', function() {
            const salsaText = document.getElementById('quesoText');
            if (this.checked) {
                salsaText.textContent = "SIN QUESO"; // Cambiar a SIN SALSA
                // Ocultar las opciones adicionales
                document.getElementById('extraOptionsQueso').style.display = 'none';
            }
        });


    </script>
@endsection
