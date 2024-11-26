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
    <h1 class="page-title">Productos</h1>
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
    <form id="formCreate" class="form-horizontal" data-url="{{ route('product.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Nombre <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" onkeyup="mayus(this);" name="name" placeholder="Ejm: Categoría">
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Descripcion</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" onkeyup="mayus(this);" name="description" placeholder="Ejm: Descripción">
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Monto</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="description" placeholder="Ejm: Descripción">
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputEmail3" class="col-12 col-form-label">Porcentaje</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="description" placeholder="Ejm: Descripción">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button type="reset" class="btn btn-outline-secondary">Cancelar</button>
                <button type="button" id="btn-submit" class="btn btn-outline-success float-right">Guardar material</button>
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
            //Initialize Select2 Elements
            $('#category').select2({
                placeholder: "Selecione categoría",
                allowClear: true,
            });

            $('.textarea_ingredients').summernote({
                lang: 'es-ES',
                placeholder: 'Ingrese los detalles',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['para', ['ul', 'ol']],
                    ['insert', ['link']],
                    ['view', ['codeview', 'help']]
                ]
            });

            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });
        })
    </script>
    <script src="{{ asset('js/product/create.js') }}?v={{ time() }}"></script>
@endsection
