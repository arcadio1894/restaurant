@extends('layouts.app')

@section('orders-active', 'active')

@section('text-header')
    Tus pedidos
@endsection

@section('styles')
    <style>
        .card{
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.10rem
        }
        .card-header:first-child{
            border-radius: calc(0.37rem - 1px) calc(0.37rem - 1px) 0 0
        }
        .card-header{
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1)
        }
        .track{
            position: relative;
            background-color: #ddd;
            height: 7px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            margin-bottom: 60px;
            margin-top: 50px
        }
        .track .step{
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            width: 25%;
            margin-top: -18px;
            text-align: center;
            position: relative
        }
        .track .step.active:before{
            background: #FF5722
        }
        .track .step::before{
            height: 7px;
            position: absolute;
            content: "";
            width: 100%;
            left: 0;top: 18px
        }
        .track .step.active .icon{
            background: #ee5435;
            color: #fff
        }
        .track .icon{
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            position: relative;
            border-radius: 100%;
            background: #ddd
        }
        .track .step.active .text{
            font-weight: 400;
            color: #000
        }
        .track .text{
            display: block;
            margin-top: 7px
        }

    </style>
@endsection

@section('content')
    <div class="row mt-4">
        @foreach($orders as $order)
            <div class="col-md-4">
                <article class="card">
                    <header class="card-header text-center"><strong>{{ $order->status_name }}</strong> </header>
                    <div class="card-body">
                        <h6 class="text-center">Order ID: #{{ $order->id }}</h6>
                        <article class="card">
                            <div class="card-body row">
                                <div class="col"> <strong>Fecha:</strong> <br> {{ $order->formatted_date }} </div>
                            </div>
                        </article>
                        <div class="track">
                            <div class="step {{ $order->active_step >= 1 ? 'active' : '' }}">
                                <span class="icon"> <i class="far fa-file-alt"></i> </span>
                                <span class="text"> Recibido</span>
                            </div>
                            <div class="step {{ $order->active_step >= 2 ? 'active' : '' }}">
                                <span class="icon"> <i class="fas fa-fire"></i> </span>
                                <span class="text"> Cocinando</span>
                            </div>
                            <div class="step {{ $order->active_step >= 3 ? 'active' : '' }}">
                                <span class="icon"> <i class="fa fa-truck"></i> </span>
                                <span class="text"> Enviado </span>
                            </div>
                            <div class="step {{ $order->active_step >= 4 ? 'active' : '' }}">
                                <span class="icon"> <i class="fas fa-home"></i> </span>
                                <span class="text"> Entregado</span>
                            </div>

                        </div>
                        <hr>
                    </div>
                </article>
            </div>
        @endforeach
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/cart/cart.js') }}"></script>
@endsection
