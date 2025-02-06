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
                        <div class="content block-collapsible-nav-content">
                            <ul class="nav flex-column">
                                <li class="nav-item">
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
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="/reclamaciones">Libro de Reclamaciones</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/estado-reclamos">Estado de reclamos</a>
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
                    <h2 class="font-weight-bold text-center">HOJA DE RECLAMACIÓN</h2>
                    <p class="text-center m-0">Conforme a lo establecido en el código de la Protección y Defensa del consumidor este establecimiento cuenta con un Libro de Reclamaciones a tu disposición. Registra la queja o reclamo aquí.</p>
                    <p class="text-center m-0">Al presentar tu reclamo autorizas el tratamiento de tus datos personales.</p>
                    <div class="datos-reclamacion mt-3">
                        <p><strong>Fecha: 06/02/2025</strong></p>
                        <p><strong>Razón Social: CORPORACIÓN PERUANA DE RESTAURANTES S.A.C.</strong></p>
                        <p><strong>RUC: 20505897812</strong></p>
                        <p><strong>Dirección fiscal: Calle Camino Real 1801, Int A4, Surco</strong></p>
                    </div>
                </div>

                <!-- Formulario -->
                <form id="form-identificacion" method="POST" action="{{--{{ route('reclamaciones.store') }}--}}">
                    @csrf
                    <div class="card mb-5">
                        <div class="card-header text-center">
                            <h5 class="mb-0">1. Identificación del Consumidor Reclamante</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nombre">Nombres:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="apellido">Apellidos:</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="tipo_documento">Tipo documento:</label>
                                    <select class="form-control select2" id="tipo_documento" name="tipo_documento" style="width: 100%;" required>
                                        <option value="">Seleccione tipo de documento</option>
                                        <option value="DNI">DNI</option>
                                        <option value="RUC">RUC</option>
                                        <option value="CE">Carnet de Extranjería</option>
                                        <option value="PAS">Pasaporte</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="numero_documento">N° Doc.:</label>
                                    <input type="text" class="form-control" id="numero_documento" name="numero_documento" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="telefono">Teléfono:</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>

                            {{--<div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="departamento">Departamento:</label>
                                    <select class="form-control" id="departamento" name="departamento" required>
                                        <option value="">Seleccione departamento</option>
                                        <!-- Opciones dinámicas -->
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="provincia">Provincia:</label>
                                    <select class="form-control" id="provincia" name="provincia" required>
                                        <option value="">Seleccione provincia</option>
                                        <!-- Opciones dinámicas -->
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="distrito">Distrito:</label>
                                    <select class="form-control" id="distrito" name="distrito" required>
                                        <option value="">Seleccione distrito</option>
                                        <!-- Opciones dinámicas -->
                                    </select>
                                </div>
                            </div>--}}

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="direccion">Dirección:</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>¿Eres menor de edad?</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="menor_si" name="menor_edad" value="si" required>
                                        <label class="form-check-label" for="menor_si">Sí</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="menor_no" name="menor_edad" value="no" checked required>
                                        <label class="form-check-label" for="menor_no">No</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Sección de datos de los padres o representante -->
                            <div id="datos-representante" class="mt-3" style="display: none;">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nombre_representante">Nombres de uno de los padres o representante:</label>
                                        <input type="text" class="form-control" id="nombre_representante" name="nombre_representante">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="telefono_representante">Teléfono de uno de los padres o representante:</label>
                                        <input type="text" class="form-control" id="telefono_representante" name="telefono_representante">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="direccion_representante">Dirección de uno de los padres o representante:</label>
                                        <input type="text" class="form-control" id="direccion_representante" name="direccion_representante">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="correo_representante">Correo de uno de los padres o representante:</label>
                                        <input type="email" class="form-control" id="correo_representante" name="correo_representante">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-5">
                        <div class="card-header text-center">
                            <h5 class="mb-0">2. Identificación del Bien Contratado</h5>
                        </div>
                        <div class="card-body">
                            <!-- Tipo de bien contratado -->
                            <div class="form-group">
                                <label>Tipo:</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="tipo_producto" name="tipo_bien" value="Producto" checked required>
                                        <label class="form-check-label" for="tipo_producto">Producto</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="tipo_servicio" name="tipo_bien" value="Servicio" required>
                                        <label class="form-check-label" for="tipo_servicio">Servicio</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Monto -->
                            <div class="form-group">
                                <label for="monto">Monto:</label>
                                <input type="text" class="form-control" id="monto" name="monto" placeholder="Ingrese el monto" required>
                            </div>

                            <!-- Descripción del bien contratado -->
                            <div class="form-group">
                                <label for="descripcion">Descripción (Nombre del producto o servicio adquirido):</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" maxlength="300" placeholder="Especificar el producto o servicio adquirido" required></textarea>
                                <small id="charCount" class="form-text text-muted">300 caracteres restantes</small>
                                <small class="form-text text-danger">Máximo permitido 300 caracteres</small>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-5">
                        <div class="card-header text-center">
                            <h5 class="mb-0">3. Detalle de Reclamación y pedido del consumidor</h5>
                        </div>
                        <div class="card-body">
                            <!-- Tipo de reclamación -->
                            <div class="form-group">
                                <label>Reclamación:</label>
                                <div>
                                    <div class="form-check form-check-inline position-relative">
                                        <input class="form-check-input" type="radio" id="tipo_reclamo" name="tipo_reclamacion" value="Reclamo" required>
                                        <label class="form-check-label" for="tipo_reclamo">Reclamo</label>
                                    </div>
                                    <div class="form-check form-check-inline position-relative">
                                        <input class="form-check-input" type="radio" id="tipo_queja" name="tipo_reclamacion" value="Queja" required>
                                        <label class="form-check-label" for="tipo_queja">Queja</label>
                                    </div>
                                </div>

                                <!-- Contenedor para el mensaje emergente -->
                                <div id="info-popup" class="info-popup" style="display: none;">
                                    <p id="popup-text" class="mb-0"></p>
                                </div>

                            </div>

                            <!-- Canal de compra -->
                            <div class="form-group">
                                <label for="canal">Canal (Selecciona dónde realizaste la compra o intentaste realizarla):</label>
                                <select class="form-control" id="canal" name="canal" required>
                                    <option value="">Seleccione canal</option>
                                    <option value="Web">Web</option>
                                    <option value="Tienda Física">Tienda Física</option>
                                    <option value="Aplicación Móvil">Aplicación Móvil</option>
                                </select>
                            </div>

                            <!-- Motivo y Submotivo -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="motivo">Motivo:</label>
                                    <select class="form-control" id="motivo" name="motivo" required>
                                        <option value="">Seleccione motivo</option>
                                        <option value="Producto Defectuoso">Producto Defectuoso</option>
                                        <option value="Servicio Deficiente">Servicio Deficiente</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="submotivo">Submotivo:</label>
                                    <select class="form-control" id="submotivo" name="submotivo" required>
                                        <option value="">Seleccione submotivo</option>
                                        <option value="Entrega Tardía">Entrega Tardía</option>
                                        <option value="Atención Insatisfactoria">Atención Insatisfactoria</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Detalle del Reclamo o Queja -->
                            <div class="form-group">
                                <label for="detalle">Detalle:</label>
                                <textarea class="form-control" id="detalle" name="detalle" rows="4" maxlength="300" placeholder="Explicar el reclamo o queja" required></textarea>
                                <small class="form-text text-danger">Máximo permitido 300 caracteres</small>
                            </div>

                            <!-- Pedido del cliente -->
                            <div class="form-group">
                                <label for="pedido_cliente">Pedido del cliente:</label>
                                <textarea class="form-control" id="pedido_cliente" name="pedido_cliente" rows="4" maxlength="300" placeholder="Detallar lo que solicita" required></textarea>
                                <small class="form-text text-danger">Máximo permitido 300 caracteres</small>
                            </div>

                            <!-- Subir comprobante de pago -->
                            <div class="form-group">
                                <label for="comprobante">Adjuntar comprobante de pago (Opcional):</label>
                                <input type="file" class="form-control-file" id="comprobante" name="comprobante" accept=".jpg,.png,.pdf">
                                <small class="form-text text-muted">Peso máximo (2MB) en formato (jpg, png, pdf)</small>
                            </div>

                            <!-- Notas de reclamo y queja -->
                            <div class="mt-4">
                                <p><strong>RECLAMO:</strong> Disconformidad relacionada con los productos o servicios.</p>
                                <p><strong>QUEJA:</strong> Disconformidad no relacionada a los productos o servicios; o, malestar o descontento respecto a la atención al público.</p>
                                <p><em>* La formulación del reclamo no impide acudir a otras vías de solución de controversias ni es requisito previo para interponer una denuncia ante el INDECOPI.</em></p>
                                <p><em>* El proveedor debe dar respuesta al reclamo o queja en un plazo no mayor a quince (15) días hábiles, el cual es improrrogable.</em></p>
                            </div>

                        </div>
                    </div>

                    <!-- Botón de Envío -->
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('js/welcome/reclamaciones.js') }}"></script>
@endsection
