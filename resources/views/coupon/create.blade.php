@extends('layouts.admin')

@section('openCoupons')
    menu-open
@endsection

@section('activeCoupons')
    active
@endsection

@section('activeCreateCoupons')
    active
@endsection

@section('title')
    Cupones
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/summernote/summernote-bs4.css') }}">

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Cupones</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Crear nuevo cupón</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('coupons.index') }}"><i class="fa fa-archive"></i> Cupón</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <form id="formCreate" class="form-horizontal" data-url="{{ route('coupons.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
            <div class="col-md-4">
                <label for="name" class="col-12 col-form-label">Nombre <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>
            <div class="col-md-4">
                <label for="description" class="col-12 col-form-label">Descripcion</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="description" id="description">
                </div>
            </div>
            <div class="col-md-4">
                <label for="type" class="col-12 col-form-label">Tipo <span class="right badge badge-danger">(*)</span></label>
                <input type="hidden" name="type" value="off"> <!-- Valor predeterminado si no está seleccionado -->
                <input id="type" type="checkbox" name="type" data-bootstrap-switch data-off-color="primary" data-on-text="TOTAL" data-off-text="DETALLE" data-on-color="success">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-4">
                <label for="inputEmail3" class="col-12 col-form-label">Monto <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="amount">
                </div>
            </div>
            <div class="col-md-4">
                <label for="inputEmail3" class="col-12 col-form-label">Porcentaje <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="percentage">
                </div>
            </div>
            <div class="col-md-4">
                <label for="special" class="col-12 col-form-label">Especial <span class="right badge badge-danger">(*)</span></label>
                <input type="hidden" name="special" value="off"> <!-- Valor predeterminado si no está seleccionado -->
                <input id="special" type="checkbox" name="special" value="on" data-bootstrap-switch data-off-color="primary" data-on-text="ESPECIAL" data-off-text="NORMAL" data-on-color="success">
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
                <button type="button" id="btn-submit" class="btn btn-outline-success float-right">Guardar cupón</button>
            </div>
        </div>
        <!-- /.card-footer -->
    </form>
@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('admin/plugins/summernote/lang/summernote-es-ES.js')}}"></script>

@endsection

@section('scripts')
    <script>
        $(function () {
            $("input[data-bootstrap-switch]").bootstrapSwitch();
        })
    </script>
    <script src="{{ asset('js/coupon/create.js') }}?v={{ time() }}"></script>
@endsection
