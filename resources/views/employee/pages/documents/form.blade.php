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
                            <i class="fas fa-file-alt me-2"></i> {{ isset($documents) ? 'Editar Documento' : 'Nuevo Documento' }}
                        </h6>
                    </div>
                </div>
                <div class="card-body">
                    <form id="documentForm" action="{{ isset($documents) ? route('employee.documents.update', $documents->id) : route('employee.documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($documents))
                            @method('PUT')
                        @endif
                        
                        <!-- Mensajes de error -->
                        @if ($errors->any())
                        <div class="alert alert-danger text-white">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ruc" class="form-control-label text-sm">RUC <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-outline">
                                        <input type="text" class="form-control @error('ruc') is-invalid @enderror" 
                                            id="ruc" name="ruc" required maxlength="11" 
                                            value="{{ old('ruc', $documents->ruc ?? '') }}"
                                            pattern="[0-9]{11}" title="El RUC debe tener 11 dígitos">
                                    </div>
                                    @error('ruc')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="razon_social" class="form-control-label text-sm">Razón Social <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-outline">
                                        <input type="text" class="form-control @error('razon_social') is-invalid @enderror" 
                                            id="razon_social" name="razon_social" required maxlength="255" 
                                            value="{{ old('razon_social', $documents->razon_social ?? '') }}">
                                    </div>
                                    @error('razon_social')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo_documento" class="form-control-label text-sm">Tipo de Documento <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-outline">
                                        <select class="form-control @error('tipo_documento') is-invalid @enderror" 
                                            id="tipo_documento" name="tipo_documento" required>
                                            <option value="">Seleccione un tipo</option>
                                            <option value="FACTURA" {{ old('tipo_documento', $documents->tipo_documento ?? '') == 'FACTURA' ? 'selected' : '' }}>Factura</option>
                                            <option value="BOLETA" {{ old('tipo_documento', $documents->tipo_documento ?? '') == 'BOLETA' ? 'selected' : '' }}>Boleta</option>
                                            <option value="NOTA_CREDITO" {{ old('tipo_documento', $documents->tipo_documento ?? '') == 'NOTA_CREDITO' ? 'selected' : '' }}>Nota de Crédito</option>
                                            <option value="NOTA_DEBITO" {{ old('tipo_documento', $documents->tipo_documento ?? '') == 'NOTA_DEBITO' ? 'selected' : '' }}>Nota de Débito</option>
                                            <option value="GUIA_REMISION" {{ old('tipo_documento', $documents->tipo_documento ?? '') == 'GUIA_REMISION' ? 'selected' : '' }}>Guía de Remisión</option>
                                        </select>
                                    </div>
                                    @error('tipo_documento')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="serie" class="form-control-label text-sm">Serie <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-outline">
                                        <input type="text" class="form-control @error('serie') is-invalid @enderror" 
                                            id="serie" name="serie" required maxlength="4" 
                                            value="{{ old('serie', $documents->serie ?? '') }}"
                                            placeholder="F001">
                                    </div>
                                    @error('serie')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="correlativo" class="form-control-label text-sm">Correlativo <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-outline">
                                        <input type="text" class="form-control @error('correlativo') is-invalid @enderror" 
                                            id="correlativo" name="correlativo" required maxlength="8" 
                                            value="{{ old('correlativo', $documents->correlativo ?? '') }}"
                                            placeholder="00000001">
                                    </div>
                                    @error('correlativo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="archivo" class="form-control-label text-sm">Archivo {{ isset($documents) ? '' : '<span class="text-danger">*</span>' }}</label>
                                    <div class="input-group input-group-outline">
                                        <input type="file" class="form-control @error('archivo') is-invalid @enderror" 
                                            id="archivo" name="archivo" {{ isset($documents) ? '' : 'required' }}
                                            accept=".pdf,.xml,.zip">
                                    </div>
                                    <small class="form-text text-muted">Formatos permitidos: PDF, XML, ZIP</small>
                                    @if(isset($documents) && $documents->archivo)
                                        <small class="form-text text-success">
                                            <i class="fas fa-check-circle me-1"></i>Archivo actual: {{ basename($documents->archivo) }}
                                        </small>
                                    @endif
                                    @error('archivo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado" class="form-control-label text-sm">Estado <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-outline">
                                        <select class="form-control @error('estado') is-invalid @enderror" 
                                            id="estado" name="estado" required>
                                            <option value="PENDIENTE" {{ old('estado', $documents->estado ?? 'PENDIENTE') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="APROBADO" {{ old('estado', $documents->estado ?? '') == 'APROBADO' ? 'selected' : '' }}>Aprobado</option>
                                            <option value="RECHAZADO" {{ old('estado', $documents->estado ?? '') == 'RECHAZADO' ? 'selected' : '' }}>Rechazado</option>
                                            <option value="ANULADO" {{ old('estado', $documents->estado ?? '') == 'ANULADO' ? 'selected' : '' }}>Anulado</option>
                                        </select>
                                    </div>
                                    @error('estado')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Campo oculto para employee_id -->
                        <input type="hidden" name="employee_id" value="{{ auth()->guard('employee')->user()->id }}">
                        
                        @if(isset($documents))
                        <!-- Información adicional solo en modo edición -->
                        <div class="card mt-4 bg-gradient-light shadow-sm">
                            <div class="card-body">
                                <h6 class="text-dark mb-3">Información adicional</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="text-sm mb-1">Documento:</p>
                                        <h5 class="font-weight-bold">{{ $documents->serie }}-{{ $documents->correlativo }}</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="text-sm mb-1">Fecha creación:</p>
                                        <h5 class="font-weight-bold">{{ $documents->created_at->format('d/m/Y') }}</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="text-sm mb-1">Última actualización:</p>
                                        <h5 class="font-weight-bold">{{ $documents->updated_at->format('d/m/Y H:i') }}</h5>
                                    </div>
                                </div>
                                @if($documents->archivo)
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <p class="text-sm mb-1">Archivo adjunto:</p>
                                        <a href="{{ Storage::url($documents->archivo) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-download me-1"></i> Descargar archivo
                                        </a>
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
                                <button type="submit" class="btn bg-gradient-primary">
                                    <i class="fas fa-save me-2"></i> {{ isset($documents) ? 'Actualizar' : 'Guardar' }} Documento
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
        // Formatear RUC mientras se escribe
        $('#ruc').on('input', function() {
            var value = $(this).val().replace(/\D/g, '');
            if (value.length > 11) {
                value = value.substr(0, 11);
            }
            $(this).val(value);
        });

        // Formatear serie (mayúsculas)
        $('#serie').on('input', function() {
            $(this).val($(this).val().toUpperCase());
        });

        // Formatear correlativo (solo números)
        $('#correlativo').on('input', function() {
            var value = $(this).val().replace(/\D/g, '');
            if (value.length > 8) {
                value = value.substr(0, 8);
            }
            // Rellenar con ceros a la izquierda
            if (value.length > 0) {
                value = value.padStart(8, '0');
            }
            $(this).val(value);
        });

        // Validación del archivo
        $('#archivo').on('change', function() {
            var file = this.files[0];
            if (file) {
                var fileSize = file.size / 1024 / 1024; // en MB
                var fileType = file.type;
                var fileName = file.name;
                var validTypes = ['application/pdf', 'text/xml', 'application/xml', 'application/zip', 'application/x-zip-compressed'];
                
                if (fileSize > 10) {
                    alert('El archivo no debe superar los 10MB');
                    $(this).val('');
                    return;
                }
                
                var extension = fileName.split('.').pop().toLowerCase();
                if (!['pdf', 'xml', 'zip'].includes(extension)) {
                    alert('Solo se permiten archivos PDF, XML o ZIP');
                    $(this).val('');
                    return;
                }
            }
        });

        // Cambiar prefijo de serie según tipo de documento
        $('#tipo_documento').on('change', function() {
            var tipo = $(this).val();
            var serie = $('#serie');
            
            switch(tipo) {
                case 'FACTURA':
                    serie.attr('placeholder', 'F001');
                    break;
                case 'BOLETA':
                    serie.attr('placeholder', 'B001');
                    break;
                case 'NOTA_CREDITO':
                    serie.attr('placeholder', 'FC01');
                    break;
                case 'NOTA_DEBITO':
                    serie.attr('placeholder', 'FD01');
                    break;
                case 'GUIA_REMISION':
                    serie.attr('placeholder', 'T001');
                    break;
                default:
                    serie.attr('placeholder', 'Serie');
            }
        });
    });
</script>
@endsection