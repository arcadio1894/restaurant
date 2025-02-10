@extends('layouts.app')

@section('text-header', '')

@section('styles')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <link href="{{ asset('css/welcome/reclamaciones.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar sidebar-main">
                    <div class="block block-collapsible-nav" id="accordion" role="tablist">
                        <div class="title block-collapsible-nav-title">
                            <strong>Centro de Ayuda</strong>
                        </div>
                        <div class="content block-collapsible-nav-content mt-3">
                            <ul class="nav flex-column ">
                                {{--<li class="nav-item">
                                    <a class="nav-link" href="/terminos-y-condiciones">Términos y Condiciones</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/terminos-y-condiciones-de-promociones">Términos y Condiciones de Promociones</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/politicas-de-privacidad">Políticas de Privacidad</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/derechos-arco">Derechos ARCO</a>
                                </li>--}}
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('reclamaciones') }}">Libro de Reclamaciones</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route('estado-reclamos') }}">Estado de reclamos</a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido de la derecha -->
            <div class="col-md-9">
                <!-- Información centrada fuera del card -->
                <div class="info-reclamacion mb-4">
                    <h2 class="font-weight-bold text-center">ESTADO DE RECLAMOS</h2>
                    <p class="text-center m-0">Consulte el estado de su reclamo.</p>
                </div>

                <div class="card mb-3">
                    <div class="card-header text-center">
                        <h5 class="mb-0">Estado de su reclamo</h5>
                    </div>
                    <div class="card-body">

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="codigo">Ingrese el código de su reclamo: <span class="right text-danger">(*)</span></label>
                                <input type="text" class="form-control" id="codigo" name="codigo" required>
                            </div>
                            <!-- Código del formulario -->
                            <div class="form-group text-center col-md-6">
                                <!-- Google reCAPTCHA -->
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                            </div>
                        </div>

                        <!-- Botón de Envío -->
                        <div class="form-group text-center mt-3">
                            <button type="button" id="btn-submitState" class="btn btn-success" data-url="{{ route('reclamos.consultar') }}">Consultar estado de reclamo</button>
                        </div>

                        <div class="form-group text-center mt-3">
                            <!-- Tabla con los datos -->
                            <div class="table-responsive">
                                <table class="table table-bordered letraTabla table-hover table-sm mb-5">
                                    <thead id="header-table">
                                    <tr class="normal-title">
                                        <th>Fecha de Registro</th>
                                        <th>Código de Reclamo</th>
                                        <th>Estado</th>
                                        <th>Solución</th>
                                    </tr>
                                    </thead>
                                    <tbody id="body-table">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="item-table">
        <tr>
            <td data-column="fecha" data-fecha></td>
            <td data-column="codigo" data-codigo></td>
            <td data-column="estado" data-estado></td>
            <td data-column="solucion" data-solucion></td>
        </tr>
    </template>

    <template id="item-table-empty">
        <tr>
            <td colspan="4" align="center">No se ha encontrado ningún dato</td>
        </tr>
    </template>
@endsection

@section('scripts')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script src="{{ asset('js/welcome/estadoReclamos.js') }}"></script>
@endsection
