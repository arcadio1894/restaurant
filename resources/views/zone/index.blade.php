@extends('layouts.admin')

@section('openZone')
    menu-open
@endsection

@section('activeZone')
    active
@endsection

@section('activeListZone')
    active
@endsection

@section('title')
    Zonas
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
    <h1 class="page-title">Zonas</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Listado de zonas</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('zones.index') }}"><i class="fa fa-archive"></i> Zonas</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Nuevo</li>
    </ol>
@endsection

@section('content')
    <h2>Gestión de Precios por Zona</h2>

    <div class="form-group row">
        <div class="col-md-4">
            <label for="shop_id">Seleccionar Tienda:</label>
            <select id="shop_id" class="form-control">
                <option value="">Seleccione una tienda</option>
                @foreach($shops as $shop)
                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                @endforeach
            </select>
        </div>

    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table mt-3">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Zona</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody id="zonesTable">
                    <tr>
                        <td colspan="4" class="text-center">Seleccione una tienda para ver las zonas</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div id="map" style="height: 500px; width: 100%;"></div>
        </div>
    </div>

@endsection

@section('plugins')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('admin/plugins/summernote/lang/summernote-es-ES.js')}}"></script>

@endsection

@section('scripts')
    <script src="{{ asset('js/zone/index.js') }}?v={{ time() }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBG5pTai_rF775fdoi3-9X8K462l1-aMo&callback=initMap&v=weekly" async defer></script>
@endsection
