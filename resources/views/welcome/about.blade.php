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
        <div class="col-md-6 ">
            <div class="img-box">
                <img src="{{ asset('landing/images/about-img1.png') }}" alt="" class="img-fluid">
            </div>
        </div>
        <div class="col-md-6">
            <div class="detail-box">
                <div class="heading_container">
                    <h2>
                        We Are Fuego y Masa
                    </h2>
                </div>
                <p>
                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration
                    in some form, by injected humour, or randomised words which don't look even slightly believable. If you
                    are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in
                    the middle of text. All
                </p>
                <a href="" class="btn-read">
                    Read More
                </a>
            </div>
        </div>
    </div>
@endsection
