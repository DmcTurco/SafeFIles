@extends('employee/layouts/base')

@section('title', 'Gestión de Documentos')

@section('content-area')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <!-- Cabecera de la tarjeta -->
                    <div class="card-header p-0 position-relative mt-n4 mx-0 z-index-2">
                        <div
                            class="bg-gradient-primary shadow-primary border-radius-lg px-3 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 text-white">Gestión de Documentos</h6>
                                <p class="mb-0 text-white text-sm opacity-8">Administra los documentos tributarios de la
                                    empresa</p>
                            </div>
                            <div>
                                <a href="{{ route('employee.documents.create') }}" class="btn btn-sm btn-white">
                                    <i class="fas fa-plus me-2"></i> Nuevo Documento
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Cuerpo de la tarjeta -->
                    <div class="card-body px-0 pt-0 pb-2">
                        <!-- Filtros de búsqueda -->
                        <div class="p-3">
                            <form action="{{ route('employee.documents.index') }}" method="GET" id="filtrosForm">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="buscar" class="text-sm text-secondary mb-2">Buscar:</label>
                                            <div class="input-group input-group-outline">
                                                <input type="text" class="form-control" id="buscar" name="buscar"
                                                    placeholder="RUC, Razón Social o N° Documento"
                                                    value="{{ request('buscar') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="tipo_documento" class="text-sm text-secondary mb-2">Tipo
                                                Documento:</label>
                                            <div class="input-group input-group-static">
                                                <select class="form-control" id="tipo_documento" name="tipo_documento">
                                                    <option value="">Todos</option>
                                                    <option value="FACTURA"
                                                        {{ request('tipo_documento') == 'FACTURA' ? 'selected' : '' }}>
                                                        Factura</option>
                                                    <option value="BOLETA"
                                                        {{ request('tipo_documento') == 'BOLETA' ? 'selected' : '' }}>Boleta
                                                    </option>
                                                    <option value="NOTA_CREDITO"
                                                        {{ request('tipo_documento') == 'NOTA_CREDITO' ? 'selected' : '' }}>
                                                        Nota de Crédito</option>
                                                    <option value="NOTA_DEBITO"
                                                        {{ request('tipo_documento') == 'NOTA_DEBITO' ? 'selected' : '' }}>
                                                        Nota de Débito</option>
                                                    <option value="GUIA_REMISION"
                                                        {{ request('tipo_documento') == 'GUIA_REMISION' ? 'selected' : '' }}>
                                                        Guía de Remisión</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="estado" class="text-sm text-secondary mb-2">Estado:</label>
                                            <div class="input-group input-group-static">
                                                <select class="form-control" id="estado" name="estado">
                                                    <option value="">Todos</option>
                                                    <option value="PENDIENTE"
                                                        {{ request('estado') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente
                                                    </option>
                                                    <option value="APROBADO"
                                                        {{ request('estado') == 'APROBADO' ? 'selected' : '' }}>Aprobado
                                                    </option>
                                                    <option value="RECHAZADO"
                                                        {{ request('estado') == 'RECHAZADO' ? 'selected' : '' }}>Rechazado
                                                    </option>
                                                    <option value="ANULADO"
                                                        {{ request('estado') == 'ANULADO' ? 'selected' : '' }}>Anulado
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="fecha" class="text-sm text-secondary mb-2">Fecha:</label>
                                            <div class="input-group input-group-outline">
                                                <input type="date" class="form-control" id="fecha" name="fecha"
                                                    value="{{ request('fecha') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <div class="form-group w-100">
                                            <button type="submit" class="btn bg-gradient-primary w-100 mb-0">
                                                <i class="fas fa-search me-2"></i> Filtrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Tabla de Documentos -->
                        <div class="table-responsive p-3">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <span class="text-sm text-secondary">Total de documentos:
                                        <strong>{{ $documents->total() ?? 0 }}</strong></span>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        id="exportarMenu" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-download me-2"></i> Exportar
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="exportarMenu">
                                        <a class="dropdown-item"
                                            href="">
                                            <i class="fas fa-file-excel me-2"></i> Excel
                                        </a>
                                        <a class="dropdown-item"
                                            href="">
                                            <i class="fas fa-file-pdf me-2"></i> PDF
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            RUC</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Razón Social</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Tipo</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Documento</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Estado</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Fecha</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Archivo</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($documents as $document)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">#{{ $document->id }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0">{{ $document->ruc }}</p>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <div
                                                            class="icon-shape icon-sm bg-gradient-primary shadow text-center border-radius-sm me-2">
                                                            <i class="fas fa-building text-white opacity-10"></i>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">
                                                            {{ Str::limit($document->razon_social, 30) }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                @php
                                                    $tipoIcon =
                                                        [
                                                            'FACTURA' => 'fa-file-invoice',
                                                            'BOLETA' => 'fa-receipt',
                                                            'NOTA_CREDITO' => 'fa-file-invoice-dollar',
                                                            'NOTA_DEBITO' => 'fa-file-invoice-dollar',
                                                            'GUIA_REMISION' => 'fa-truck',
                                                        ][$document->tipo_documento] ?? 'fa-file';

                                                    $tipoColor =
                                                        [
                                                            'FACTURA' => 'info',
                                                            'BOLETA' => 'primary',
                                                            'NOTA_CREDITO' => 'warning',
                                                            'NOTA_DEBITO' => 'danger',
                                                            'GUIA_REMISION' => 'secondary',
                                                        ][$document->tipo_documento] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-gradient-{{ $tipoColor }}">
                                                    <i class="fas {{ $tipoIcon }} me-1"></i>
                                                    {{ str_replace('_', ' ', $document->tipo_documento) }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm font-weight-bold">
                                                    {{ $document->serie }}-{{ $document->correlativo }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                @php
                                                    $estadoColor =
                                                        [
                                                            'PENDIENTE' => 'warning',
                                                            'APROBADO' => 'success',
                                                            'RECHAZADO' => 'danger',
                                                            'ANULADO' => 'secondary',
                                                        ][$document->estado] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-gradient-{{ $estadoColor }}">
                                                    {{ $document->estado }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $document->created_at->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                @if ($document->archivo)
                                                    <a href="{{ Storage::url($document->archivo) }}" target="_blank"
                                                        class="btn btn-link text-info px-2 mb-0"
                                                        title="Descargar archivo">
                                                        <i class="fas fa-download text-info"></i>
                                                    </a>
                                                @else
                                                    <span class="text-xs text-secondary">Sin archivo</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <div class="ms-auto text-end">
                                                    <a href="{{ route('employee.documents.show', $document->id) }}"
                                                        class="btn btn-link text-info px-2 mb-0" title="Ver detalles">
                                                        <i class="fas fa-eye text-info"></i>
                                                    </a>
                                                    <a href="{{ route('employee.documents.edit', $document->id) }}"
                                                        class="btn btn-link text-dark px-2 mb-0" title="Editar">
                                                        <i class="fas fa-pencil-alt text-dark"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-link text-danger text-gradient px-2 mb-0 btn-eliminar-documento"
                                                        data-id="{{ $document->id }}"
                                                        data-documento="{{ $document->serie }}-{{ $document->correlativo }}"
                                                        title="Eliminar">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <p class="text-sm mb-0">No hay documentos registrados</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Paginación -->
                            <div class="d-flex justify-content-end mt-3">
                                {{ $documents->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar documento -->
    <div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Confirmar eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar el documento <strong id="numero-documento-eliminar"></strong>?</p>
                    <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Esta acción no se puede
                        deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <form id="form-eliminar" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Configurar modal de eliminación
            $('.btn-eliminar-documento').on('click', function() {
                const id = $(this).data('id');
                const documento = $(this).data('documento');

                $('#numero-documento-eliminar').text(documento);
                const url = "{{ route('employee.documents.destroy', ':id') }}";
                $('#form-eliminar').attr('action', url.replace(':id', id));
                $('#confirmarEliminarModal').modal('show');
            });

            // Limpiar filtros
            $('#limpiarFiltros').on('click', function() {
                $('#filtrosForm')[0].reset();
                window.location.href = "{{ route('employee.documents.index') }}";
            });
        });
    </script>
@endsection
