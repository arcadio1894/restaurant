@extends('layouts.app')

@section('about-active', 'active')

@section('text-header', '')

@section('styles')
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
    </style>
@endsection

@section('content')

    <div class="row pt-5">
        <div class="col-md-6 pl-5 pr-5">
            <div class="detail-box">
                <div class="heading_container">
                    <h2>
                        Fuego y Masa
                    </h2>
                </div>
                <br><br>
                <p>
                    En Fuego y Masa, somos una pizzería orgullosamente trujillana, apasionada por crear momentos inolvidables a través de sabores únicos.
                </p>

                <p>
                    Nuestro <b>objetivo</b> no es solo ofrecerte las pizzas clásicas que amas, 
                    sino también sorprenderte con experiencias gastronómicas que despierten tus sentidos y redefinan lo que significa disfrutar de una pizza.
                </p>

                <p>
                    Sabemos que las mejores historias comienzan en casa, y por eso llevamos nuestros sabores directamente a tu puerta. 
                    Cada pizza está elaborada con ingredientes cuidadosamente seleccionados y una dedicación que garantiza calidad en cada bocado. 
                    Queremos ser parte de tus momentos especiales, esos que compartes con familia, amigos o simplemente contigo mismo.
                </p>

                <p>
                    Porque en Fuego y Masa, creemos que una pizza es más que comida, es el puente hacia recuerdos inolvidables.
                </p>

                <p style="text-align: center;" >
                    <b>"Una pizza, mil recuerdos compartidos."</b>    
                </p>

            </div>
        </div>
        <div class="col-md-6 ">
            <div class="img-box">
                <img style="margin: -80px 0px 0px 0px;" src="{{ asset('landing/images/about-img1.png') }}" alt="" class="img-fluid">
            </div>
        </div>
    </div>
@endsection
