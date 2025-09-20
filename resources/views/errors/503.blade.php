<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="{{ secure_asset('landing/images/favicon.png') }}" type="">

    <title> Fuego y Masa </title>

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link href="{{ secure_asset('landing/css/fontawesome-all.min.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{ secure_asset('auth/css/style.css') }}">

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
</head>
<body>

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
                            background-image: url({{ secure_asset('landing/images/about-img1.png') }});
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

<!-- jQery -->
<script src="{{ secure_asset('auth/js/jquery.min.js') }}"></script>
<script src="{{ secure_asset('auth/js/popper.js') }}"></script>
<script src="{{ secure_asset('auth/js/bootstrap.min.js') }}"></script>
<script src="{{ secure_asset('auth/js/main.js') }}"></script>

</body>
</html>

