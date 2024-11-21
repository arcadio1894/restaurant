@extends('layouts.admin')

@section('title')
    Dashboard
@endsection

@section('styles-plugins')

@endsection

@section('page-header')
    <h1 class="page-title">Dashboard</h1>
@endsection

@section('page-breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.principal') }}"><i class="fa fa-home"></i> Dashboard</a>
        </li>
    </ol>
@endsection

@section('page-title')
    <h5 class="card-title">PANEL PRINCIPAL</h5>
@endsection

@section('content')
    {{--@hasanyrole('admin|almacen|principal')
    <div class="row">
        @can('list_customer')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $customerCount }}</h3>

                    <p>Clientes</p>
                </div>
                <div class="icon">
                    <i class="ion ion-briefcase"></i>
                </div>
                <a href="{{ route('customer.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_contactName')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $contactNameCount }}</h3>

                    <p>Contactos</p>
                </div>
                <div class="icon">
                    <i class="ion ion-clipboard"></i>
                </div>
                <a href="{{ route('contactName.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_supplier')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $supplierCount }}</h3>

                    <p>Proveedores</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-home-outline"></i>
                </div>
                <a href="{{ route('supplier.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_material')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $materialCount }}</h3>

                    <p>Materiales</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-box"></i>
                </div>
                <a href="{{ route('material.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_entryPurchase')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $entriesCount }}</h3>

                    <p>Entradas a almacén</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-cart"></i>
                </div>
                <a href="{{ route('entry.purchase.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_invoice')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-fuchsia">
                <div class="inner">
                    <h3>{{ $invoiceCount }}</h3>

                    <p>Facturas</p>
                </div>
                <div class="icon">
                    <i class="ion ion-card"></i>
                </div>
                <a href="{{ route('invoice.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
        @can('list_request')
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $outputCount }}</h3>

                    <p>Salidas de almacén</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-exit"></i>
                </div>
                <a href="{{ route('output.request.index') }}" class="small-box-footer">Más detalles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endcan
    </div>
    @endhasanyrole--}}
@endsection

@section('content-report')
    {{--@hasanyrole('admin|almacen|principal')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-info">
                <div class="card-header border-0">
                    <h3 class="card-title">Existencias en almacén </h3>

                    <div class="card-tools">
                        <button type="button" id="btn-refresh" class="btn btn-sm btn-warning float-left"><i class="fas fa-sync text-success"></i> Refrescar</button>

                        <a href="{{ route('report.excel.amount') }}" class="btn btn-sm btn-tool" data-toggle="tooltip" data-placement="top" title="Descargar excel">
                            <i class="fas fa-download text-danger"></i> <span class="text-danger text-bold">Descargar</span>
                        </a>
                    </div>
                </div>
                <div class="card-body" id="element_loader">
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-success text-xl">
                            <i class="fas fa-dollar-sign"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                        <span class="font-weight-bold" id="amount_dollars">

                        </span>
                            <span class="text-muted">MONTO EN DÓLARES</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-warning text-xl bold">
                            S/.
                        </p>
                        <p class="d-flex flex-column text-right">
                        <span class="font-weight-bold" id="amount_soles">

                        </span>
                            <span class="text-muted">MONTO EN SOLES</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <p class="text-danger text-xl">
                            <i class="fas fa-boxes"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                        <span class="font-weight-bold" id="quantity_items">

                        </span>
                            <span class="text-muted">CANTIDAD DE EXISTENCIAS</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1">
                    <a href="{{ route('report.excel.materials') }}">
                        <i class="fas fa-database"></i>
                    </a>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">BASE DE DATOS MATERIALES</span>
                    <a href="{{ route('report.excel.materials') }}">
                        <span class="info-box-number">
                            Descargar <i class="fas fa-cloud-download-alt"></i>
                        </span>
                    </a>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box" id="box">
                <span class="info-box-icon bg-success elevation-1">
                    --}}{{--<a href="--}}{{----}}{{--{{ route('report.excel.materials') }}--}}{{----}}{{--">--}}{{--
                        <i class="fas fa-database"></i>
                    --}}{{--</a>--}}{{--
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">BASE DE DATOS POR ALMACEN</span>
                    <button id="btn-download" class="btn btn-sm btn-outline-success">
                        <span class="info-box-number">
                            Descargar <i class="fas fa-cloud-download-alt"></i>
                        </span>
                    </button>
                </div>
                <!-- /.info-box-content -->
            </div>
            <div class="info-box" id="box">
                <span class="info-box-icon bg-success elevation-1">
                    <i class="fas fa-file-excel"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">DESCARGAR INGRESOS</span>
                    <button id="btn-downloadEntries" class="btn btn-sm btn-outline-success">
                        <span class="info-box-number">
                            Descargar <i class="fas fa-cloud-download-alt"></i>
                        </span>
                    </button>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-info">
                <div class="card-header border-transparent">
                    <h3 class="card-title">5 Últimas Rotaciones</h3>

                    <div class="card-tools">
                        <button type="button" id="btn-newRotation" class="btn btn-sm btn-warning float-left"><i class="fas fa-cut"></i> Nuevo corte</button>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0 table-sm">
                            <thead>
                            <tr>
                                <th>Rotación</th>
                                <th>Fecha</th>
                                <th>Usuario</th>
                            </tr>
                            </thead>
                            <tbody id="body-table">

                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <div class="d-flex flex-stack flex-wrap pt-1">
                        <div class="fs-6 fw-bold text-gray-700" id="textPagination"></div>
                        <!--begin::Pages-->
                        <ul class="pagination" style="margin-left: auto;" id="pagination">

                        </ul>
                        <!--end::Pages-->
                    </div>
                </div>
                <!-- /.card-footer -->
            </div>
        </div>
    </div>
    @endhasanyrole--}}

@endsection

@section('scripts')
    <!-- Select2 -->

    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/jquery_loading/loadingoverlay.min.js')}}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('#location').select2({
                placeholder: "Selecione un almacén",
            });
            $('#typeEntry').select2({
                placeholder: "Selecione Tipo",
                allowClear: true
            });
        })
    </script>
@endsection
