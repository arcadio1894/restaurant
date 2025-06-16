@extends('layouts.app')

@section('text-header', '')

@section('styles')
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Dropzone CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
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
                            <ul class="nav flex-column">
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
                                    <a class="nav-link active" href="{{ route('reclamaciones') }}">Libro de Reclamaciones</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('estado-reclamos') }}">Estado de reclamos</a>
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
                        <p><strong>Fecha: <span id="fecha-actual"></span></strong></p>
                        <p><strong>Razón Social: FUEGO Y MASA S.A.C.</strong></p>
                        <p><strong>RUC: 20613407287</strong></p>
                    </div>
                </div>

                <!-- Formulario -->
                <form id="form-identificacion" method="POST" data-url="{{ route('reclamaciones.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-5">
                        <div class="card-header text-center">
                            <h5 class="mb-0">1. Identificación del Consumidor Reclamante</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nombre">Nombres: <span class="right text-danger">(*)</span></label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="apellido">Apellidos: <span class="right text-danger">(*)</span></label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="tipo_documento">Tipo documento: <span class="right text-danger">(*)</span></label>
                                    <select class="form-control" id="tipo_documento" name="tipo_documento" style="width: 100%;" required>
                                        <option></option>
                                        <option value="DNI">DNI</option>
                                        <option value="RUC">RUC</option>
                                        <option value="CE">Carnet de Extranjería</option>
                                        <option value="PAS">Pasaporte</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="numero_documento">N° Doc.: <span class="right text-danger">(*)</span></label>
                                    <input type="text" class="form-control" id="numero_documento" name="numero_documento" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="telefono">Teléfono: <span class="right text-danger">(*)</span></label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email: <span class="right text-danger">(*)</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="departamento">Departamento: <span class="right text-danger">(*)</span></label>
                                    <select class="form-control" id="departamento" name="departamento" required>
                                        <option value=""></option>
                                        <!-- Opciones dinámicas -->
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="provincia">Provincia: <span class="right text-danger">(*)</span></label>
                                    <select class="form-control" id="provincia" name="provincia" required>
                                        <option value=""></option>
                                        <!-- Opciones dinámicas -->
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="distrito">Distrito: <span class="right text-danger">(*)</span></label>
                                    <select class="form-control" id="distrito" name="distrito" required>
                                        <option value=""></option>
                                        <!-- Opciones dinámicas -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="direccion">Dirección: <span class="right text-danger">(*)</span></label>
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
                                        <label for="nombre_representante">Nombres de uno de los padres o representante: <span class="right text-danger">(*)</span></label>
                                        <input type="text" class="form-control" id="nombre_representante" name="nombre_representante">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="telefono_representante">Teléfono de uno de los padres o representante: <span class="right text-danger">(*)</span></label>
                                        <input type="text" class="form-control" id="telefono_representante" name="telefono_representante">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="direccion_representante">Dirección de uno de los padres o representante: <span class="right text-danger">(*)</span></label>
                                        <input type="text" class="form-control" id="direccion_representante" name="direccion_representante">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="correo_representante">Correo de uno de los padres o representante: <span class="right text-danger">(*)</span></label>
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
                                <label>Tipo: <span class="right text-danger">(*)</span></label>
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
                                <label for="descripcion">Descripción (Nombre del producto o servicio adquirido): <span class="right text-danger">(*)</span></label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" maxlength="600" placeholder="Especificar el producto o servicio adquirido" required></textarea>
                                <small id="charCount" class="form-text text-muted">600 caracteres restantes</small>
                                <small class="form-text text-danger">Máximo permitido 600 caracteres</small>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header text-center">
                            <h5 class="mb-0">3. Detalle de Reclamación y pedido del consumidor</h5>
                        </div>
                        <div class="card-body">
                            <!-- Tipo de reclamación -->
                            <div class="form-group">
                                <label>Reclamación: <span class="right text-danger">(*)</span></label>
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
                                <label for="canal">Canal (Selecciona dónde realizaste la compra o intentaste realizarla): <span class="right text-danger">(*)</span></label>
                                <select class="form-control" id="canal" name="canal" required>
                                    <option value="">Seleccione canal</option>
                                    <option value="web">Tienda Virtual (fuegoymasa.com)</option>
                                    <option value="whatsapp">Whatsapp</option>
                                    <option value="movil">Aplicación Móvil</option>
                                </select>
                            </div>

                            <!-- Motivo y Submotivo -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="motivo">Motivo: <span class="right text-danger">(*)</span></label>
                                    <select class="form-control" id="motivo" name="motivo" required>
                                        <option value=""></option>
                                        @foreach($motivos as $motivo)
                                            <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="submotivo">Submotivo: <span class="right text-danger">(*)</span></label>
                                    <select class="form-control" id="submotivo" name="submotivo" required>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <!-- Detalle del Reclamo o Queja -->
                            <div class="form-group">
                                <label for="detalle">Detalle: <span class="right text-danger">(*)</span></label>
                                <textarea class="form-control" id="detalle" name="detalle" rows="4" maxlength="600" placeholder="Explicar el reclamo o queja" required></textarea>
                                <small id="charCountDetalle" class="form-text text-muted">600 caracteres restantes</small>
                                <small class="form-text text-danger">Máximo permitido 600 caracteres</small>
                            </div>

                            <!-- Pedido del cliente -->
                            <div class="form-group">
                                <label for="pedido_cliente">Pedido del cliente: <span class="right text-danger">(*)</span></label>
                                <textarea class="form-control" id="pedido_cliente" name="pedido_cliente" rows="4" maxlength="600" placeholder="Detallar lo que solicita" required></textarea>
                                <small id="charCountPedidoCliente" class="form-text text-muted">600 caracteres restantes</small>
                                <small class="form-text text-danger">Máximo permitido 600 caracteres</small>
                            </div>

                            <!-- Subir comprobante de pago -->
                            <div class="form-group">
                                <label for="comprobante-dropzone">Adjuntar comprobante de pago y/o evidencia (Opcional):</label>
                                <div id="comprobante-dropzone" class="dropzone border rounded p-3 bg-light"></div>
                                <small class="form-text text-muted mt-2">
                                    Puedes subir hasta 4 archivos (JPG, PNG, PDF) de máximo 4MB cada uno.
                                </small>
                            </div>


                            <!-- Notas de reclamo y queja -->
                            <div class="mt-2">
                                <p style="font-size: 0.9rem"><strong>RECLAMO:</strong> Disconformidad relacionada con los productos o servicios.</p>
                                <p style="font-size: 0.9rem"><strong>QUEJA:</strong> Disconformidad no relacionada a los productos o servicios; o, malestar o descontento respecto a la atención al público.</p>
                                <p style="font-size: 0.9rem"><em>* La formulación del reclamo no impide acudir a otras vías de solución de controversias ni es requisito previo para interponer una denuncia ante el INDECOPI.</em></p>
                                <p style="font-size: 0.9rem"><em>* El proveedor debe dar respuesta al reclamo o queja en un plazo no mayor a quince (15) días hábiles, el cual es improrrogable.</em></p>
                            </div>

                        </div>
                    </div>

                    <!-- Código del formulario -->
                    <div class="form-group text-center mt-3">
                        <!-- Google reCAPTCHA -->
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    </div>

                    <!-- Botón de Envío -->
                    <div class="form-group text-center mt-0">
                        <button type="button" id="btn-submit" class="btn btn-success">Enviar reclamo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Dropzone JS (antes del cierre de </body>) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;

        const dropzone = new Dropzone("#comprobante-dropzone", {
            url: "#", // No enviará nada automáticamente
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 4,
            paramName: "comprobantes[]",
            maxFiles: 4,
            maxFilesize: 4, // MB
            acceptedFiles: ".jpg,.jpeg,.png,.pdf",
            addRemoveLinks: true,
            dictDefaultMessage: "Arrastra tus archivos aquí o haz clic para subir",
            dictMaxFilesExceeded: "Solo puedes subir un máximo de 4 archivos.",
            dictFileTooBig: "El archivo es muy grande (máx: 4MB).",
            dictInvalidFileType: "Tipo de archivo no permitido."
        });
    </script>
    <script src="{{ asset('js/welcome/reclamaciones.js') }}"></script>
@endsection
