@extends('layouts.appAdmin2')

@section('openMaterial')
    menu-open
@endsection

@section('activeMaterial')
    active
@endsection

@section('activeListMaterial')
    active
@endsection

@section('title')
    Materiales
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

@endsection

@section('styles')
    <style>
        .select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection

@section('page-header')
    <h1 class="page-title">Materiales</h1>
@endsection

@section('page-title')
    <h5 class="card-title">Modificar material {{ $material->code }}</h5>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('material.indexV2') }}"><i class="fa fa-archive"></i> Materiales</a>
        </li>
        <li class="breadcrumb-item"><i class="fa fa-plus-circle"></i> Editar</li>
    </ol>
@endsection

@section('content')
    <form id="formEdit" class="form-horizontal" data-url="{{ route('material.update') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="material_id" value="{{ $material->id }}">

        <input type="hidden" id="category_id" value="{{$material->category_id}}">
        <input type="hidden" id="subcategory_id" value="{{$material->subcategory_id}}">
        <input type="hidden" id="type_id" value="{{$material->material_type_id}}">
        <input type="hidden" id="subtype_id" value="{{$material->subtype_id}}">

        <input type="hidden" id="exampler_id" value="{{$material->exampler_id}}">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Datos generales</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="description">Descripción <span class="right badge badge-danger">(*)</span></label>
                                <input type="text" id="description" {{--onkeyup="mayus(this);"--}} name="description" class="form-control" value="{{ $material->description }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            {{--<div class="col-md-6">
                                <label for="measure">Medida </label>
                                <input type="text" id="measure" --}}{{--onkeyup="mayus(this);"--}}{{-- name="measure" class="form-control" value="{{ $material->measure }}">
                            </div>--}}
                            <div class="col-md-4">
                                <label for="unit_measure">Unidad de medida <span class="right badge badge-danger">(*)</span></label>
                                <select id="unit_measure" name="unit_measure" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $unitMeasures as $unitMeasure )
                                        <option value="{{ $unitMeasure->id }}" {{ ($unitMeasure->id === $material->unit_measure_id) ? 'selected': ''}}>{{ $unitMeasure->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-md-4">
                                <label for="brand">Marca </label>
                                <select id="brand" name="brand" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $brands as $brand )
                                        <option value="{{ $brand->id }}" {{ ($brand->id === $material->brand_id) ? 'selected':'' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="exampler">Modelo </label>
                                <select id="exampler" name="exampler" class="form-control select2" style="width: 100%;">

                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="genero">Genero </label>
                                <select id="genero" name="genero" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $generos as $genero )
                                        <option value="{{ $genero->id }}" {{ ($genero->id === $material->warrant_id) ? 'selected':'' }}>{{ $genero->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="talla">Talla </label>
                                <select id="talla" name="talla" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $tallas as $talla )
                                        <option value="{{ $talla->id }}" {{ ($talla->id === $material->quality_id) ? 'selected':'' }}>{{ $talla->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="perecible">Perecible </label>
                                <select id="perecible" name="perecible" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    <option value="s" {{ ($material->perecible == "s") ? 'selected':'' }}>SI</option>
                                    <option value="n" {{ ($material->perecible == "n") ? 'selected':'' }}>NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="category">Categorías <span class="right badge badge-danger">(*)</span></label>
                                <select id="category" name="category" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $categories as $category )
                                        <option value="{{ $category->id }}" {{ ($category->id === $material->category_id) ? 'selected': ''}}>{{ $category->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-4">
                                <label for="subcategory">Subcategorías <span class="right badge badge-danger">(*)</span></label>
                                <select id="subcategory" name="subcategory" class="form-control select2" style="width: 100%;">
                                    <option></option>

                                </select>

                            </div>
                            <div class="col-md-4">
                                <label for="subcategory">Cantidad por paquete </label>
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" name="pack" id="checkboxPack" {{ ($material->isPack == 1) ? 'checked':'' }}>
                                        <label for="checkboxPack">Es paquete</label>
                                        <input type="number" class="form-control form-control-sm d-inline ml-2" style="width: 70px;" id="inputPack" name="inputPack" value="{{ $material->quantityPack  }}" min="0" {{ ($material->isPack == 0) ? 'disabled':'' }}>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{--<div class="form-group row" id="feature-body" style="display: none">
                            <div class="col-md-3">
                                <label for="type">Tipo </label>
                                <select id="type" name="type" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                </select>

                            </div>
                            <div class="col-md-3">
                                <label for="subtype">Subtipo </label>
                                <select id="subtype" name="subtype" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                </select>

                            </div>
                            <div class="col-md-3">
                                <label for="warrant">Cédula </label>
                                <select id="warrant" name="warrant" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    <option value="" selected>Ninguno</option>
                                    @foreach( $warrants as $warrant )
                                        <option value="{{$warrant->id}}" {{ ($warrant->id === $material->warrant_id) ? 'selected': ''}}>{{ $warrant->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-3">
                                <label for="quality">Calidad </label>
                                <select id="quality" name="quality" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    <option value="" selected>Ninguno</option>
                                    @foreach( $qualities as $quality )
                                        <option value="{{$quality->id}}" {{ ($quality->id === $material->quality_id) ? 'selected' : '' }}>{{ $quality->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>--}}

                        <div class="form-group">
                            <label for="name">Nombre completo</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control rounded-0" id="name" {{--onkeyup="mayus(this);"--}} name="name" value="{{ $material->full_name }}" readonly>
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-info btn-flat" id="btn-generate"> <i class="fa fa-redo"></i> Actualizar</button>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="unit_price">Precio Unitario </label>
                                <input type="number" id="unit_price" name="unit_price" class="form-control" placeholder="0.00" min="0" value="{{ $material->unit_price }}" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                    " >
                            </div>
                            <div class="col-md-4">
                                <label for="codigo">Código del producto </label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control rounded-0" id="codigo" name="codigo" value="{{ $material->codigo }}">
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-info btn-flat" id="btn-generateCode"> <i class="fas fa-random"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="tipo_venta">Tipo de Venta </label>
                                <select id="tipo_venta" name="tipo_venta" class="form-control select2" style="width: 100%;">
                                    <option></option>
                                    @foreach( $tipoVentas as $tipo )
                                        <option value="{{$tipo->id}}" {{ ($tipo->id === $material->tipo_venta_id) ? 'selected': ''}}>{{ $tipo->description }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="image">Imagen </label>
                            <input type="file" id="image" name="image" class="form-control">
                            <img src="{{ asset('images/material/'.$material->image) }}" width="100px" height="100px" alt="{{ $material->description }}">
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-4">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Categoría y Stock</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="stock_max">Stock Máximo <span class="right badge badge-danger">(*)</span></label>
                            <input type="number" id="stock_max" name="stock_max" class="form-control" placeholder="0.00" min="0" value="{{ $material->stock_max }}" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                    ">
                        </div>
                        <div class="form-group">
                            <label for="stock_min">Stock Mínimo <span class="right badge badge-danger">(*)</span></label>
                            <input type="number" id="stock_min" name="stock_min" class="form-control" placeholder="0.00" min="0" value="{{ $material->stock_min }}" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                    ">
                        </div>

                        <div class="form-group">
                            <label for="stock_current">Stock Actual <span class="right badge badge-danger">(*)</span></label>
                            <input type="number" id="stock_current" name="stock_current" class="form-control" placeholder="0.00" min="0" value="{{ $material->stock_current }}" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" onblur="
                                    this.style.borderColor=/^\d+(?:\.\d{1,2})?$/.test(this.value)?'':'red'
                                    " readonly>
                        </div>


                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Promociones</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach( $discountQuantities as $discountQuantity )
                            @php
                                $isChecked = isset($materialsDiscounts[$discountQuantity->id]);
                                $percentage = $isChecked ? $materialsDiscounts[$discountQuantity->id] : $discountQuantity->percentage;
                            @endphp
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" name="discount[{{$discountQuantity->id}}]" id="checkboxPrimary{{$discountQuantity->id}}" {{ $isChecked ? 'checked' : '' }}>
                                    <label for="checkboxPrimary{{$discountQuantity->id}}">
                                        {{$discountQuantity->description}} - {{ $percentage }}%
                                    </label>
                                    <input type="number" class="form-control form-control-sm d-inline ml-2" style="width: 70px;" id="percentageInput{{$discountQuantity->id}}" name="percentage[{{$discountQuantity->id}}]" value="{{ $percentage }}" min="0" max="100"> %
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <!-- /.card-body -->
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <a href="{{ route('material.index') }}" class="btn btn-outline-secondary">Cancelar</a>
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
@endsection

@section('scripts')
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('#material_type').select2({
                placeholder: "Selecione tipo de material",
            });
            $('#category').select2({
                placeholder: "Selecione categoría",
            });
            $('#subcategory').select2({
                placeholder: "Selecione subcategoría",
            });
            $('#brand').select2({
                placeholder: "Selecione una marca",
            });
            $('#exampler').select2({
                placeholder: "Selecione un modelo",
            });
            $('#priority').select2({
                placeholder: "Selecione una prioridad",
            });
            $('#feature').select2({
                placeholder: "Seleccione característica",
            });
            $('#type').select2({
                placeholder: "Elija",
            });
            $('#subtype').select2({
                placeholder: "Elija",
            });
            $('#warrant').select2({
                placeholder: "Elija",
            });
            $('#quality').select2({
                placeholder: "Elija",
            });
            $('#unit_measure').select2({
                placeholder: "Elija",
            });
            $('#perecible').select2({
                placeholder: "Seleccione ",
                allowClear: true,
            });
            $('#genero').select2({
                placeholder: "Seleccione género",
                allowClear: true,
            });
            $('#talla').select2({
                placeholder: "Seleccione talla",
                allowClear: true,
            });
            $('#tipo_venta').select2({
                placeholder: "Seleccione Tipo Venta",
                allowClear: true,
            });
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });
        })
    </script>
    <script src="{{ asset('js/material/edit.js') }}?v={{ time() }}"></script>
@endsection
