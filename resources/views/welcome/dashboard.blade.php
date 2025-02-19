@extends('layouts.admin')

@section('title')
    Dashboard
@endsection

@section('styles-plugins')
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        /* Aseguramos que el modal tenga un z-index siempre por encima de otros elementos */
        .modal {
            z-index: 9999 !important; /* Usamos 9999 para asegurarnos de que siempre esté por encima */
        }

        .modal-backdrop {
            z-index: 9998 !important; /* Fondo del modal debajo */
        }

        /* Aseguramos que las tarjetas se muevan sin alterar el z-index del modal */
        .card {
            position: relative; /* Esto ayuda a que las tarjetas tengan un contexto de apilamiento sin interferir */
            z-index: 1;
        }
    </style>
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

    <div class="form-group">
        <label for="btn-status"> Estado de la tienda </label> <br>
        <input id="btn-status" type="checkbox" data-status="{{ $status }}" name="status" {{ ($status == 1) ? 'checked':'' }} data-bootstrap-switch data-off-color="danger" data-on-text="ABIERTA" data-off-text="CERRADA" data-on-color="success">
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 id="activeUsersCount">0</h3> <!-- Se actualizará dinámicamente -->

                    <p>Usuarios Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3 id="registerUsersCount">0</h3> <!-- Se actualizará dinámicamente -->

                    <p>Usuarios Registrados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <p class="text-center">
                <strong>Clientes con Más Pedidos</strong>
            </p>

            <div id="topClientsContainer">
                <p>Cargando...</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <!-- TIPO USUARIO CHART -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Tipo Usuario</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm filter-btn" data-filter="daily">Diario</button>
                        <button type="button" class="btn btn-secondary btn-sm filter-btn" data-filter="weekly">Semanal</button>
                        <button type="button" class="btn btn-warning btn-sm filter-btn" data-filter="monthly">Mensual</button>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#dateRangeModal">
                            Por Fechas
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" >
                        <div class="chart">
                            <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-transparent">
                    <div class="row">
                        <div class="col-4 text-center">
                            <input type="text" class="knob" id="knobWhatsapp" data-readonly="true" value="20" data-width="60" data-height="60"
                                   data-fgColor="#dc3545">

                            <div class="text-danger">Whatsapp <br> <span id="quantityKnobWhatsapp"></span></div>
                        </div>
                        <!-- ./col -->
                        <div class="col-4 text-center">
                            <input type="text" class="knob" id="knobWeb" data-readonly="true" value="50" data-width="60" data-height="60"
                                   data-fgColor="#007bff">

                            <div class="text-primary">Web <br> <span id="quantityKnobWeb"></span></div>
                        </div>
                        <!-- ./col -->
                        <div class="col-4 text-center">
                            <input type="text" class="knob" id="knobTotal" data-readonly="true" value="30" data-width="60" data-height="60"
                                   data-fgColor="#6f42c1">

                            <div class="text-purple">Total <br> <span id="quantityKnobTotal"></span></div>
                        </div>
                        <!-- ./col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Promociones usadas</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm filter-btn-promo" data-filter="daily">Diario</button>
                        {{--<button type="button" class="btn btn-secondary btn-sm filter-btn-promo" data-filter="weekly">Semanal</button>
                        <button type="button" class="btn btn-warning btn-sm filter-btn-promo" data-filter="monthly">Mensual</button>
                        --}}<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#dateRangeModalPromo">
                            Por Fechas
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive" >
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Código de promoción</th>
                                <th>Progreso</th>
                                <th style="width: 40px">Cantidades</th>
                            </tr>
                            </thead>
                            <tbody id="body-promos">

                            </tbody>
                        </table>
                        <h5 id="title-promo" class="text-center text-bold" style="font-size: 0.8rem"></h5>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>


@endsection

@section('content-report')
    <template id="template-promo">
        <tr>
            <td data-i>1.</td>
            <td data-code>Update software</td>
            <td>
                <div class="progress progress-xs">
                    <div class="progress-bar progress-bar-danger" data-progress style="width: 55%"></div>
                </div>
            </td>
            <td><span class="badge p-2" data-percentage>55%</span></td>
        </tr>
    </template>

    <template id="template-promo-empty">
        <tr>
            <td colspan="4" align="center">No se ha encontrado ningún dato</td>
        </tr>
    </template>

    <div class="modal fade" id="dateRangeModal" role="dialog" aria-labelledby="dateRangeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dateRangeModalLabel">Seleccionar Rango de Fechas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="dateRangeForm">
                        <div class="form-group">
                            <label for="start_date">Fecha Inicio</label>
                            <input type="date" class="form-control" id="start_date">
                        </div>
                        <div class="form-group">
                            <label for="end_date">Fecha Fin</label>
                            <input type="date" class="form-control" id="end_date">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary filter-btn" data-filter="date_range" data-dismiss="modal">Aplicar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="dateRangeModalPromo" role="dialog" aria-labelledby="dateRangeModalPromoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dateRangeModalLabel">Seleccionar Rango de Fechas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="dateRangeFormPromo">
                        <div class="form-group">
                            <label for="start_date_promo">Fecha Inicio</label>
                            <input type="date" class="form-control" id="start_date_promo">
                        </div>
                        <div class="form-group">
                            <label for="end_date_promo">Fecha Fin</label>
                            <input type="date" class="form-control" id="end_date_promo">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary filter-btn-promo" data-filter="date_range_promo" data-dismiss="modal">Aplicar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('admin/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('admin/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/jquery_loading/loadingoverlay.min.js')}}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch();
            });


        })
    </script>
    <script src="{{ asset('js/dashboard/dashboard.js')}}"></script>
    <script src="{{ asset('js/dashboard/ordersChart.js')}}"></script>

    <script src="{{ asset('js/dashboardPusher.js')}}"></script>
@endsection
