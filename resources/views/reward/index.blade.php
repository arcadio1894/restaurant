@extends('layouts.app')

@section('reward-active', 'active')

@section('text-header', '')

@section('styles')
    <link href="{{ asset('css/welcome/reclamaciones.css') }}" rel="stylesheet">
    <style>
        .btn-read {
            display: inline-block;
            padding: 10px 45px;
            background-color: #ffbe33;
            color: #ffffff;
            border-radius: 45px;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
            border: none;
            margin-top: 15px;
        }
        .nav-link-perfil {
            color: #ffbe33;
            transition: color 0.3s ease;
        }

        .nav-link-perfil.clicked {
            color: #000 !important;
        }

        /* Estilos de Milestone */
        .milestone-progress {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding: 0 20px;
            position: relative;
        }

        .milestone-progress::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 20px;
            right: 20px;
            height: 4px;
            background-color: #ccc;
            z-index: 0;
        }

        .milestone-point {
            z-index: 1;
            text-align: center;
            width: 40px;
            position: relative;
        }

        .milestone-point.active .dot {
            background-color: #ffbe33;
        }

        .dot {
            width: 16px;
            height: 16px;
            background-color: #e0e0e0;
            border-radius: 50%;
            margin: 0 auto 5px;
        }

        .tab-custom-reward .nav-link.active {
            border-bottom: 3px solid #007a33;
            font-weight: bold;
            color: #000;
        }

        .tab-custom-reward .nav-link {
            color: #333;
            padding: 6px 10px;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }

        .milestone-content img {
            max-height: 100px;
        }

        .milestone-content {
            background-color: #eaf4f2;
            padding: 2rem;
            border-radius: 10px;
            /*margin-top: 1rem;*/
            display: none;
        }

        .milestone-content.active {
            display: block;
        }

        .flame-icon {
            width: 40px;
            height: 40px;
            margin-left: 5px;
            vertical-align: bottom;
        }

        .flame-icon-small {
            width: 24px;
            height: 24px;
            margin-left: 5px;
            vertical-align: middle;
        }

        @media (max-width: 576px) {
            #milestoneTabs {
                justify-content: flex-start !important;
            }

            .tab-custom-reward {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                position: relative;
            }

            .tab-custom-reward::-webkit-scrollbar {
                display: none;
            }

            .tab-custom-reward .nav-item {
                flex: 0 0 auto;
                margin: 0 4px;
            }

            .tab-custom-reward .nav-link {
                font-size: 13px;
                padding: 2px 6px;
                min-width: 40px;
                text-align: center;
            }

            .flame-icon-small {
                width: 14px;
                height: 14px;
                margin-left: 3px;
            }

            .small-text {
                font-size: 16px;
            }
        }

        @media (max-width: 767.98px) {
            .nav-link-perfil {
                font-size: 14px;
                color: #007a33;
                text-decoration: none;
                display: inline-block;
                margin-left: 10px;
                margin-bottom: 10px;
            }

            #mobileTabs {
                display: flex;
                width: 100%;
            }

            #mobileTabs .nav-link {
                flex: 1 1 50%;
                background-color: transparent;
                padding: 10px 0;
                text-align: center;
                font-size: 14px;
                border: none;
                border-bottom: 2px solid transparent;
                color: #333;
            }

            #mobileTabs .nav-link.active {
                border-bottom: 3px solid #007a33;
                font-weight: bold;
                background-color: transparent;
                color: #000;
            }
        }

        .card-img-icon {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
        }

        /* Si NO quieres líneas entre tarjetas en escritorio */
        @media (min-width: 768px) {
            .card-info {
                border-bottom: none !important;
            }
        }

        /* Opcional: texto más pequeño en móviles */
        @media (max-width: 767.98px) {
            .title-mobile {
                font-size: 1.1rem;
            }

            .desc-mobile {
                font-size: 0.9rem;
            }

            .without-mt {
                margin-top: 0px !important;
            }
        }

        .milestone-icon {
            width: 24px;
            height: 24px;
            display: block;
            margin: 0 auto 5px auto;
        }
    </style>
@endsection

@section('content')

    <div class="container without-mt mt-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 d-none d-md-block">
                <div class="sidebar sidebar-main">
                    <div class="block block-collapsible-nav" id="accordion" role="tablist">
                        <div class="title block-collapsible-nav-title">
                            <a class="nav-link-perfil" href="{{ route('perfil.usuario') }}"><strong><i class="far fa-user"></i> Ir a Mi Perfil <i class="far fa-arrow-alt-circle-right"></i></strong></a>

                        </div>
                        <div class="content block-collapsible-nav-content mt-3">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link nav-link-reward active" href="#" data-target="#mis-flamitas">Mis Flamitas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-link-reward" href="#" data-target="#como-funciona">Como funciona</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs móviles visibles solo en pantallas pequeñas -->
            <div class="d-md-none mt-4 col-12 mb-5">
                <div class="text-left mb-2">
                    <a class="nav-link-perfil" href="{{ route('perfil.usuario') }}"><strong><i class="far fa-user"></i> Ir a Mi Perfil <i class="far fa-arrow-alt-circle-right"></i></strong></a>
                </div>
                <div class="nav nav-tabs" id="mobileTabs" role="tablist">
                    <a class="nav-link nav-link-reward active w-50 text-center" href="#" data-target="#mis-flamitas">Mis Flamitas</a>
                    <a class="nav-link nav-link-reward w-50 text-center" href="#" data-target="#como-funciona">Cómo funciona</a>
                </div>
            </div>

            <!-- Contenido de la derecha -->
            <div class="col-md-9">
                <!-- Información centrada fuera del card -->
                <div class="info-reclamacion mb-4" id="mis-flamitas">
                    <!-- Total de flames -->
                    <div class="text-center">
                        <h1>{{ $flames }} <img src="{{ asset('images/icons/fire.png') }}" alt="Flame" class="flame-icon"></h1>
                        <p>Flamitas acumuladas</p>
                    </div>

                    <!-- Barra de progreso de milestones -->
                    <div class="milestone-progress">
                        @foreach($milestones as $milestone)
                            <div class="milestone-point {{ $flames >= $milestone->flames ? 'active' : '' }}">
                                <img
                                    src="{{ asset($flames >= $milestone->flames ? '/images/icons/fire.png' : '/images/reward/fire-black.png') }}"
                                    alt="Milestone flame"
                                    class="milestone-icon"
                                >
                                <small>{{ $milestone->flames }}</small>
                            </div>
                        @endforeach
                    </div>

                    <!-- Tabs personalizados -->
                    <div class="bg-light p-3 mt-5 text-center">
                        <h5 class="small-text"><strong>{{ Auth::user()->name }}</strong>, conoce qué puedes canjear con tus Flamitas</h5>

                        <ul class="nav justify-content-center tab-custom-reward mt-3" id="milestoneTabs" role="tablist">
                            @foreach($milestones as $index => $milestone)
                                <li class="nav-item">
                                    <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                       data-toggle="tab"
                                       href="#milestone-{{ $milestone->id }}"
                                       role="tab">{{ $milestone->flames }} <img src="{{ asset('images/icons/fire.png') }}" class="flame-icon-small"></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Contenido de los milestones -->
                    <div class="tab-content">
                        @foreach($milestones as $index => $milestone)
                            <div class="milestone-content {{ $index == 0 ? 'active' : '' }}" id="milestone-{{ $milestone->id }}">
                                <div class="row align-items-center">
                                    <div class="col-md-3 text-center mb-3 mb-md-0">
                                        <img src="{{ asset('images/reward/' . $milestone->image) }}" alt="{{ $milestone->title }}">
                                    </div>
                                    <div class="col-md-9">
                                        <h4 class="font-weight-bold">{{ $milestone->title }}</h4>
                                        <p>{!! $milestone->description !!}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="info-reclamacion mb-4" id="como-funciona">
                    <p class="m-0">Como miembro del programa Starbucks Rewards, ganas Estrellas por las compras que realizas en nuestras tiendas, desde la web Starbucks.pe o nuestra App.</p>
                    <p class="m-0">Podrás canjear las Estrellas por bebidas, alimentos, café en grano y mucho más.</p>
                    <div class="container my-4">
                        <div class="card-info d-flex align-items-start mb-4 pb-4 border-bottom">
                            <div class="card-img-wrapper mr-3 text-center">
                                <img src="{{ asset('images/reward/headwithphone.svg') }}" alt="Icono 1" class="card-img-icon">
                            </div>
                            <div class="text-content">
                                <h5 class="font-weight-bold title-mobile">Ordena y paga</h5>
                                <p class="mb-1 desc-mobile">Haz tu pedido Delivery, Recojo en tienda o Auto Starbucks; págalo online y gana Estrellas</p>
                                <p class="mb-0 desc-mobile">Ingresa desde nuestra Starbucks App o la web Starbucks.pe</p>
                            </div>
                        </div>

                        <div class="card-info d-flex align-items-start mb-4 pb-4 border-bottom">
                            <div class="card-img-wrapper mr-3 text-center">
                                <img src="{{ asset('images/reward/paystar.svg') }}" alt="Icono 2" class="card-img-icon">
                            </div>
                            <div class="text-content">
                                <h5 class="font-weight-bold title-mobile">Acumula Estrellas</h5>
                                <p class="mb-0 desc-mobile">Gana Estrellas por las compras que realices con tu Starbucks Card u otros medios de pago</p>
                            </div>
                        </div>

                        <div class="card-info d-flex align-items-start mb-4 pb-4 border-bottom">
                            <div class="card-img-wrapper mr-3 text-center">
                                <img src="{{ asset('images/reward/coffeewithbread.svg') }}" alt="Icono 2" class="card-img-icon">
                            </div>
                            <div class="text-content">
                                <h5 class="font-weight-bold title-mobile">Canjea tus Estrellas</h5>
                                <p class="mb-0 desc-mobile">Podrás canjear tus Estrellas acumuladas por bebidas, sándwiches, postres, galletas, café en grano y mucho más</p>
                            </div>
                        </div>

                        <div class="card-info d-flex align-items-start mb-4 pb-4 border-bottom">
                            <div class="card-img-wrapper mr-3 text-center">
                                <img src="{{ asset('images/reward/icecream_reward.svg') }}" alt="Icono 2" class="card-img-icon">
                            </div>
                            <div class="text-content">
                                <h5 class="font-weight-bold title-mobile">Aprovecha los Star Bonus</h5>
                                <p class="mb-0 desc-mobile">Gana Estrellas aún más rápido participando de Star Bonus, Double Star Days y mucho más</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/reward/index.js') }}"></script>
@endsection
