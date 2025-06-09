@extends('employee/layouts/base')

@section('title', isset($documents) ? 'Editar Documento' : 'Nuevo Documento')

@section('content-area')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">
                                <i class="fas fa-file-alt me-2"></i>
                                {{ isset($documents) ? 'Editar Documento' : 'Nuevo Documento' }}
                            </h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Mensajes de éxito -->
                        @if (session('success'))
                            <div class="alert alert-success text-white alert-dismissible fade show" role="alert">
                                <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                                <span class="alert-text">{{ session('success') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Mensajes de error general -->
                        @if (session('error'))
                            <div class="alert alert-danger text-white alert-dismissible fade show" role="alert">
                                <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <span class="alert-text">{{ session('error') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Errores de validación -->
                        @if ($errors->any())
                            <div class="alert alert-danger text-white alert-dismissible fade show" role="alert">
                                <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
                                <span class="alert-text"><strong>¡Error!</strong> Por favor corrige los siguientes errores:</span>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form id="documentForm"
                            action="{{ isset($documents) ? route('employee.documents.update', $documents->id) : route('employee.documents.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (isset($documents))
                                @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ruc" class="form-control-label text-sm">RUC</label>
                                        <div class="input-group input-group-outline @error('ruc') is-invalid @enderror {{ old('ruc', $documents->ruc ?? '') ? 'is-filled' : '' }}">
                                            <input type="text" class="form-control @error('ruc') is-invalid @enderror"
                                                id="ruc" name="ruc" maxlength="11"
                                                value="{{ old('ruc', $documents->ruc ?? '') }}" 
                                                placeholder="Ingrese 11 dígitos (opcional)">
                                        </div>
                                        @error('ruc')
                                            <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                                        @enderror
                                        <small class="text-muted">Debe contener exactamente 11 números</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="razon_social" class="form-control-label text-sm">Razón Social</label>
                                        <div class="input-group input-group-outline @error('razon_social') is-invalid @enderror {{ old('razon_social', $documents->razon_social ?? '') ? 'is-filled' : '' }}">
                                            <input type="text"
                                                class="form-control @error('razon_social') is-invalid @enderror"
                                                id="razon_social" name="razon_social" maxlength="255"
                                                value="{{ old('razon_social', $documents->razon_social ?? '') }}"
                                                placeholder="Nombre de la empresa (opcional)">
                                        </div>
                                        @error('razon_social')
                                            <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tipo_documento" class="form-control-label text-sm">Tipo de Documento <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-outline @error('tipo_documento') is-invalid @enderror {{ old('tipo_documento', $documents->tipo_documento ?? '') ? 'is-filled' : '' }}">
                                            <select class="form-control @error('tipo_documento') is-invalid @enderror"
                                                id="tipo_documento" name="tipo_documento" required>
                                                <option value="">-- Seleccione un tipo --</option>
                                                @foreach($documentTypes as $id => $nombre)
                                                    <option value="{{ $id }}"
                                                        {{ old('tipo_documento', $documents->tipo_documento ?? '') == $id ? 'selected' : '' }}>
                                                        {{ $nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('tipo_documento')
                                            <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="serie" class="form-control-label text-sm">Serie</label>
                                        <div class="input-group input-group-outline @error('serie') is-invalid @enderror {{ old('serie', $documents->serie ?? '') ? 'is-filled' : '' }}">
                                            <input type="text" class="form-control @error('serie') is-invalid @enderror"
                                                id="serie" name="serie" maxlength="10"
                                                value="{{ old('serie', $documents->serie ?? '') }}" 
                                                placeholder="Ej: F001">
                                        </div>
                                        @error('serie')
                                            <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="correlativo" class="form-control-label text-sm">Correlativo</label>
                                        <div class="input-group input-group-outline @error('correlativo') is-invalid @enderror {{ old('correlativo', $documents->correlativo ?? '') ? 'is-filled' : '' }}">
                                            <input type="text"
                                                class="form-control @error('correlativo') is-invalid @enderror"
                                                id="correlativo" name="correlativo" maxlength="20"
                                                value="{{ old('correlativo', $documents->correlativo ?? '') }}"
                                                placeholder="Ej: 00000001">
                                        </div>
                                        @error('correlativo')
                                            <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="archivo" class="form-control-label text-sm">
                                            Archivo 
                                            @if(!isset($documents))
                                                <span class="text-danger">*</span>
                                            @else
                                                <span class="text-muted">(Opcional)</span>
                                            @endif
                                        </label>
                                        <div class="input-group input-group-outline @error('archivo') is-invalid @enderror">
                                            <input type="file"
                                                class="form-control @error('archivo') is-invalid @enderror" 
                                                id="archivo"
                                                name="archivo" 
                                                accept=".pdf,.xml,.zip">
                                        </div>
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle"></i> Formatos permitidos: PDF, XML, ZIP (Máximo 10MB)
                                        </small>
                                        @if (isset($documents) && $documents->archivo)
                                            <small class="form-text text-success d-block">
                                                <i class="fas fa-check-circle me-1"></i>Archivo actual: {{ basename($documents->archivo) }}
                                            </small>
                                        @endif
                                        @error('archivo')
                                            <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                                        @enderror
                                        <div id="archivo-preview" class="mt-2"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estado" class="form-control-label text-sm">Estado <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-outline @error('estado') is-invalid @enderror {{ old('estado', $documents->estado ?? '0') !== null ? 'is-filled' : '' }}">
                                            <select class="form-control @error('estado') is-invalid @enderror"
                                                id="estado" name="estado" required>
                                                <option value="0" {{ old('estado', $documents->estado ?? '0') == '0' ? 'selected' : '' }}>
                                                    <i class="fas fa-clock"></i> Pendiente
                                                </option>
                                                <option value="1" {{ old('estado', $documents->estado ?? '') == '1' ? 'selected' : '' }}>
                                                    <i class="fas fa-check"></i> Activo
                                                </option>
                                            </select>
                                        </div>
                                        @error('estado')
                                            <small class="text-danger d-block mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Campo oculto para employee_id -->
                            <input type="hidden" name="employee_id" value="{{ auth()->guard('employee')->user()->id }}">

                            @if (isset($documents))
                                <!-- Información adicional solo en modo edición -->
                                <div class="card mt-4 bg-gradient-light shadow-sm">
                                    <div class="card-body">
                                        <h6 class="text-dark mb-3">Información adicional</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="text-sm mb-1">Documento:</p>
                                                <h5 class="font-weight-bold">
                                                    {{ $documents->serie ?? 'Sin serie' }}-{{ $documents->correlativo ?? 'Sin correlativo' }}
                                                </h5>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="text-sm mb-1">Tipo:</p>
                                                <h5 class="font-weight-bold">
                                                    {{ \App\Constants\DocumentTypes::getName($documents->tipo_documento) }}
                                                </h5>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="text-sm mb-1">Estado actual:</p>
                                                <h5 class="font-weight-bold">
                                                    @if($documents->estado == 1)
                                                        <span class="badge bg-success">Activo</span>
                                                    @else
                                                        <span class="badge bg-warning">Pendiente</span>
                                                    @endif
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <p class="text-sm mb-1">Fecha creación:</p>
                                                <h5 class="font-weight-bold">{{ $documents->created_at->format('d/m/Y H:i') }}</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="text-sm mb-1">Última actualización:</p>
                                                <h5 class="font-weight-bold">{{ $documents->updated_at->format('d/m/Y H:i') }}</h5>
                                            </div>
                                        </div>

                                        @if ($documents->archivo)
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <p class="text-sm mb-1">Archivo adjunto:</p>
                                                    <div class="btn-group" role="group">
                                                        @if (pathinfo($documents->archivo, PATHINFO_EXTENSION) == 'pdf')
                                                            <a href="{{ route('employee.documents.preview', $documents->id) }}"
                                                                target="_blank" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye me-1"></i> Ver PDF
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('employee.documents.download', $documents->id) }}"
                                                            class="btn btn-sm btn-success">
                                                            <i class="fas fa-download me-1"></i> Descargar archivo
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="row mt-4">
                                <div class="col-md-6 text-start">
                                    <a href="{{ route('employee.documents.index') }}" class="btn btn-light">
                                        <i class="fas fa-arrow-left me-2"></i> Volver
                                    </a>
                                </div>
                                <div class="col-md-6 text-end">
                                    <button type="submit" class="btn bg-gradient-primary" id="btnSubmit">
                                        <i class="fas fa-save me-2"></i>
                                        {{ isset($documents) ? 'Actualizar' : 'Guardar' }} Documento
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Definir los placeholders para cada tipo de documento
            var seriePlaceholders = {
                '{{ \App\Constants\DocumentTypes::FACTURA }}': 'F001',
                '{{ \App\Constants\DocumentTypes::BOLETA }}': 'B001',
                '{{ \App\Constants\DocumentTypes::NOTA_CREDITO }}': 'FC01',
                '{{ \App\Constants\DocumentTypes::NOTA_DEBITO }}': 'FD01',
                '{{ \App\Constants\DocumentTypes::GUIA_REMISION }}': 'T001'
            };

            // Mostrar spinner al enviar formulario
            $('#documentForm').on('submit', function() {
                var btn = $('#btnSubmit');
                btn.prop('disabled', true);
                btn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Procesando...');
            });

            // Formatear RUC mientras se escribe
            $('#ruc').on('input', function() {
                var value = $(this).val().replace(/\D/g, '');
                if (value.length > 11) {
                    value = value.substr(0, 11);
                }
                $(this).val(value);
                
                // Validación visual
                if (value.length == 11 || value.length == 0) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                } else if (value.length > 0) {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                }
            });

            // Formatear serie (mayúsculas)
            $('#serie').on('input', function() {
                $(this).val($(this).val().toUpperCase());
            });

            // Formatear correlativo (solo números, sin padding automático)
            $('#correlativo').on('input', function() {
                var value = $(this).val().replace(/\D/g, '');
                if (value.length > 20) {
                    value = value.substr(0, 20);
                }
                $(this).val(value);
            });

            // Validación del archivo con preview
            $('#archivo').on('change', function() {
                var file = this.files[0];
                var preview = $('#archivo-preview');
                preview.empty();
                
                if (file) {
                    var fileSize = file.size / 1024 / 1024; // en MB
                    var fileName = file.name;
                    var extension = fileName.split('.').pop().toLowerCase();
                    
                    // Validar tamaño
                    if (fileSize > 10) {
                        preview.html('<div class="alert alert-danger py-2"><i class="fas fa-exclamation-triangle"></i> El archivo no debe superar los 10MB</div>');
                        $(this).val('');
                        $(this).addClass('is-invalid');
                        return;
                    }
                    
                    // Validar extensión
                    if (!['pdf', 'xml', 'zip'].includes(extension)) {
                        preview.html('<div class="alert alert-danger py-2"><i class="fas fa-exclamation-triangle"></i> Solo se permiten archivos PDF, XML o ZIP</div>');
                        $(this).val('');
                        $(this).addClass('is-invalid');
                        return;
                    }
                    
                    // Mostrar preview exitoso
                    $(this).removeClass('is-invalid').addClass('is-valid');
                    var icon = extension == 'pdf' ? 'fa-file-pdf text-danger' : 
                              extension == 'xml' ? 'fa-file-code text-success' : 
                              'fa-file-archive text-warning';
                    
                    preview.html(`
                        <div class="alert alert-success py-2">
                            <i class="fas ${icon} me-2"></i>
                            <strong>${fileName}</strong> 
                            <span class="ms-2">(${fileSize.toFixed(2)} MB)</span>
                        </div>
                    `);
                }
            });

            // Cambiar prefijo de serie según tipo de documento
            $('#tipo_documento').on('change', function() {
                var tipo = $(this).val();
                var serie = $('#serie');
                
                if (tipo) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                    
                    // Cambiar placeholder según el tipo seleccionado
                    if (seriePlaceholders[tipo]) {
                        serie.attr('placeholder', seriePlaceholders[tipo]);
                    } else {
                        serie.attr('placeholder', 'Serie');
                    }
                } else {
                    $(this).removeClass('is-valid');
                    serie.attr('placeholder', 'Serie');
                }
            });

            // Validación de estado
            $('#estado').on('change', function() {
                if ($(this).val() !== '') {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid');
                }
            });

            // Trigger change en tipo_documento si hay valor seleccionado (para edición)
            @if(isset($documents) && $documents->tipo_documento)
                $('#tipo_documento').trigger('change');
            @endif
        });
    </script>
@endsection