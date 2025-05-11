@extends('layouts.app')

@section('reward-active', 'active')

@section('text-header', '')

@section('styles')
    <link href="{{ asset('css/reward/index.css') }}" rel="stylesheet">
    <style>
        .nav-link-perfil {
            color: #007a33;
            transition: color 0.3s ease;
        }

        .nav-link-perfil.clicked {
            color: #000 !important;
        }

        /* Estilos de Milestone */
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

        @media (max-width: 576px) {

            .tab-custom-reward .nav-item {
                flex: 0 0 auto;
                margin: 0 4px;
            }

            .tab-custom-reward .nav-link {
                font-size: 13px;
                padding: 2px 6px;
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

        /* Estilos de Floating Label */
        .floating-label {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .floating-label label {
            position: absolute;
            top: 50%;
            left: 12px;
            font-size: 14px;
            color: #999;
            transition: 0.2s ease all;
            pointer-events: none;
            transform: translateY(-50%);
            background-color: white; /* Fondo blanco para que no pase la línea del input */
            padding: 0 5px;
        }

        .floating-label input:focus ~ label,
        .floating-label input:not(:placeholder-shown) ~ label {
            top: -8px;
            left: 10px;
            font-size: 12px;
            color: #007a33;
        }

        .floating-label select:focus ~ label,
        .floating-label select:not([value=""]) ~ label {
            top: -8px;
            left: 10px;
            font-size: 12px;
            color: #007a33;
        }

        .form-control {
            height: 45px;
            padding: 10px 12px;
            border-radius: 10px;
            box-shadow: none !important;
            outline: none !important;
        }
        .btn-success {
            background-color: #007a33;
            border-color: #007a33;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
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
                            <a class="nav-link-perfil" href="{{ route('rewards') }}"><strong><i class="far fa-arrow-alt-circle-left"></i> Ir a Mis Premios </strong></a>

                        </div>
                        <div class="content block-collapsible-nav-content mt-3">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link nav-link-reward active" href="{{ route('perfil.usuario') }}">Perfil</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-link-reward" href="{{ route('orders.index') }}">Pedidos</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs móviles visibles solo en pantallas pequeñas -->
            <div class="d-md-none mt-4 col-12 mb-5">
                <div class="text-left mb-2">
                    <a class="nav-link-perfil" href="{{ route('rewards') }}"><strong><i class="far fa-arrow-alt-circle-left"></i> Ir a Mis Premios </strong></a>
                </div>
                <div class="nav nav-tabs" id="mobileTabs" role="tablist">
                    <a class="nav-link nav-link-reward active w-50 text-center" href="{{ route('perfil.usuario') }}">Perfil</a>
                    <a class="nav-link nav-link-reward w-50 text-center" href="{{ route('orders.index') }}">Pedidos</a>
                </div>
            </div>

            <!-- Contenido de la derecha -->
            <div class="col-md-9">
                <div class="info-reclamacion mb-4">
                    <div class="col-md-6 offset-md-3 my-3">
                        <form data-action="{{ route('profile.update') }}" method="POST" id="profile-form">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <div class="form-group floating-label">
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                <label for="name">Nombre y Apellido</label>
                            </div>

                            {{--<div class="form-group floating-label">
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                <label for="phone">Celular</label>
                                <span class="position-absolute text-success" style="top: 35px; right: 10px; cursor: pointer;">
                                    <i class="fas fa-pen"></i>
                                </span>
                            </div>--}}

                            <div class="form-group floating-label">
                                <input type="text" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                <label for="email">Email</label>
                            </div>

                            <button type="button" id="btn-submit" class="btn btn-success btn-block mt-4">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/reward/profile.js') }}"></script>
@endsection
