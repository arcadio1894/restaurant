@extends('layouts.auth')

@section('styles')
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Open+Sans:wght@400;600;700&display=swap");

        h1, h2 {
            font-family: 'Dancing Script', cursive;
            font-size: 2.5rem !important;
            font-weight: bold;
        }

        .btn-submit{
            display: inline-block !important;
            padding: 10px 55px !important;
            background-color: #ffbe33 !important;
            color: #ffffff !important;
            border-radius: 45px !important;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
            border: none !important;
        }

        /*.wrap .img {
            min-height: 350px;   !* ajusta este valor según necesites *!
            background-size: contain; !* o cover, según prefieras *!
            background-repeat: no-repeat;
            background-position: center;
        }*/
    </style>
@endsection

@section('content')
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-3">
                    <h2 class="heading-section">En Mantenimiento Temporal</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="wrap d-md-flex">
                        <div class="img" style="
                                background-image: url({{ asset('landing/images/about-img1.png') }});
                                min-height: 350px;
                                background-size: contain;
                                background-repeat: no-repeat;
                                background-position: center;
                                ">
                        </div>
                        <div class="login-wrap p-4 p-md-5">

                            <p style="font-size: 1.1rem; line-height: 1.6; color:#444;">
                                Estamos realizando ajustes para brindarte una mejor experiencia.
                                <br>
                                Gracias por tu paciencia y preferencia 💛🍕
                                <br>
                                Muy pronto estaremos de regreso con todo el sabor de <strong>Fuego y Masa</strong>.
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
