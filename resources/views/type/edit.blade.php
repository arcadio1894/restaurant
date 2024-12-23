@extends('layouts.admin')

@section('openTypes')
    menu-open
@endsection

@section('activeTypes')
    active
@endsection

@section('activeLCreateTypes')
    active
@endsection

@section('title')
    Tipo de productos
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
    <h1 class="page-title">Tipo de Productos</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Modificar tipo de producto</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('types.index') }}"><i class="fa fa-archive"></i> Tipo de Productos</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Editar</li>
    </ol>
@endsection

@section('content')
    <form id="formEdit" class="form-horizontal" data-url="{{ route('types.update', $type->id) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type_id" value="{{ $type->id }}">

        <div class="form-group row">
            <div class="col-md-4">
                <label for="name" class="col-12 col-form-label">Nombre <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="name" id="name" value="{{ $type->name }}" placeholder="Tipo">
                </div>
            </div>
            <div class="col-md-4">
                <label for="size" class="col-12 col-form-label">Tamaño <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="size" id="size" value="{{ $type->size }}" placeholder="20cm">
                </div>
            </div>
            <div class="col-md-4">
                <label for="price" class="col-12 col-form-label">Precio referencial <span class="right badge badge-danger">(*)</span></label>
                <div class="col-sm-12">
                    <input type="number" class="form-control" name="price" id="price" value="{{ $type->price }}" placeholder="0.00">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a href="{{ route('types.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="button" id="btn-submit" class="btn btn-outline-success float-right">Guardar tipo</button>
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
            /*$('#category').select2({
                placeholder: "Selecione categoría",
            });*/

            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });
        })
    </script>
    <script src="{{ asset('js/type/edit.js') }}?v={{ time() }}"></script>
@endsection
