@extends('layouts.app')

@push('styles')
<style>
    .servidores-page {
        padding-bottom: 150px;
        transform: none !important;
        transform-origin: initial !important;
    }

    .servidor-card {
        border-radius: 14px;
        min-height: 214px;
    }

    .servidor-card .card-body {
        min-height: 214px;
        display: flex;
        align-items: center;
    }

    .servidor-row {
        width: 100%;
        display: grid;
        grid-template-columns: 128px minmax(0, 1fr) 116px;
        align-items: center;
        gap: 24px;
    }

    .servidor-photo {
        width: 110px;
        height: 110px;
        object-fit: cover;
        border: 4px solid #1565c0;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.2);
        flex: 0 0 auto;
    }

    .servidor-avatar {
        width: 110px;
        height: 110px;
        font-size: 2.8rem;
        flex: 0 0 auto;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.2);
    }

    .servidor-info {
        min-width: 0;
    }

    .servidor-nombre {
        overflow-wrap: anywhere;
        line-height: 1.18;
    }

    .servidor-meta {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(220px, 0.8fr);
        gap: 16px;
        align-items: start;
    }

    .servidor-actions {
        width: 104px;
        display: grid;
        gap: 8px;
    }

    .servidor-actions .btn {
        width: 100%;
        min-height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .area-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px;
    }

    .area-summary-item {
        background: #fff;
        border: 1px solid #e3e8ef;
        border-radius: 8px;
        padding: 14px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .area-summary-name {
        min-height: 38px;
        color: #1a3a6b;
        font-size: 0.86rem;
        font-weight: 700;
        line-height: 1.25;
    }

    .area-summary-metrics {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-top: 10px;
        font-variant-numeric: tabular-nums;
    }

    .area-summary-metric {
        text-align: center;
        font-size: 0.72rem;
        color: #6c757d;
    }

    .area-summary-metric strong {
        display: block;
        font-size: 1rem;
        line-height: 1.1;
    }

    .servidores-search {
        position: relative;
        background: #fff;
        border: 1px solid #e3e8ef;
        border-radius: 8px;
        padding: 14px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
    }

    .servidores-search .input-group-text {
        background: #f8fafc;
        border-color: #d9e2ec;
        color: #1a3a6b;
    }

    .servidores-search-input {
        border-color: #d9e2ec;
        min-height: 42px;
    }

    .servidores-suggestions {
        position: absolute;
        left: 14px;
        right: 14px;
        top: calc(100% - 8px);
        z-index: 1050;
        display: none;
        max-height: 320px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid #d9e2ec;
        border-radius: 8px;
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.16);
    }

    .servidores-suggestions.is-visible {
        display: block;
    }

    .servidores-suggestion {
        display: block;
        padding: 10px 12px;
        color: #334155;
        text-decoration: none;
        border-bottom: 1px solid #eef2f7;
    }

    .servidores-suggestion:hover,
    .servidores-suggestion:focus {
        background: #f1f6ff;
        color: #0d6efd;
    }

    .servidores-suggestion:last-child {
        border-bottom: 0;
    }

    .servidores-suggestion-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        font-weight: 700;
        line-height: 1.2;
    }

    .servidores-suggestion-code {
        flex: 0 0 auto;
        color: #0d6efd;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .servidores-suggestion-meta {
        margin-top: 4px;
        color: #64748b;
        font-size: 0.78rem;
        line-height: 1.25;
    }

    @media (max-width: 767.98px) {
        .servidor-card,
        .servidor-card .card-body {
            min-height: auto;
        }

        .servidor-row {
            grid-template-columns: 1fr;
            justify-items: center;
            text-align: center;
            gap: 16px;
        }

        .servidor-meta {
            grid-template-columns: 1fr;
            text-align: left;
        }

        .servidor-actions {
            width: min(100%, 280px);
        }
    }
</style>
@endpush

@section('content')
    <div class="container py-4 servidores-page">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-primary">
                    <i class="bi bi-people-fill me-2"></i>Servidores Públicos Registrados
                </h4>
                <small class="text-muted" style="font-size:0.9rem;">
                    @if($areaActual)
                        Filtro activo: {{ $areaActual }}
                    @else
                        Gestión completa del personal
                    @endif
                </small>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('reporte.items') }}"
                    class="btn btn-sm d-flex align-items-center px-3 py-2 border-0 rounded-3 fw-semibold report-btn"
                    style="background: linear-gradient(135deg, #28a745, #20c997); color: #fff; box-shadow: 0 3px 10px rgba(40, 167, 69, 0.25); transition: all 0.25s ease;"
                    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(40, 167, 69, 0.4)'"
                    onmouseout="this.style.transform='';this.style.boxShadow='0 3px 10px rgba(40, 167, 69, 0.25)'">
                    <i class="bi bi-file-earmark-excel me-2" style="font-size: 1.5rem;"></i>
                    <span>
                        <strong style="font-size: 0.8rem;">Reporte Items</strong>
                        <br>
                        <small style="font-size: 0.65rem; opacity: 0.85;">Abrir en Excel</small>
                    </span>
                </a>
                <a href="{{ route('reporte.consultoria') }}"
                    class="btn btn-sm d-flex align-items-center px-3 py-2 border-0 rounded-3 fw-semibold report-btn"
                    style="background: linear-gradient(135deg, #17a2b8, #6610f2); color: #fff; box-shadow: 0 3px 10px rgba(23, 162, 184, 0.25); transition: all 0.25s ease;"
                    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(23, 162, 184, 0.4)'"
                    onmouseout="this.style.transform='';this.style.boxShadow='0 3px 10px rgba(23, 162, 184, 0.25)'">
                    <i class="bi bi-file-earmark-excel me-2" style="font-size: 1.5rem;"></i>
                    <span>
                        <strong style="font-size: 0.8rem;">Reporte Consultoría</strong>
                        <br>
                        <small style="font-size: 0.65rem; opacity: 0.85;">Abrir en Excel</small>
                    </span>
                </a>
                <a href="{{ route('servidores.create') }}"
                    class="btn btn-sm d-flex align-items-center px-3 py-2 border-0 rounded-3 fw-semibold"
                    style="background: linear-gradient(135deg, #0d6efd, #6610f2); color: #fff; box-shadow: 0 3px 10px rgba(13, 110, 253, 0.25); transition: all 0.25s ease;"
                    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(13, 110, 253, 0.4)'"
                    onmouseout="this.style.transform='';this.style.boxShadow='0 3px 10px rgba(13, 110, 253, 0.25)'">
                    <i class="bi bi-person-plus-fill me-2" style="font-size: 1.5rem;"></i>
                    <span>
                        <strong style="font-size: 0.8rem;">+ Nuevo Servidor</strong>
                        <br>
                        <small style="font-size: 0.65rem; opacity: 0.85;">Agregar registro</small>
                    </span>
                </a>
            </div>
        </div>

        <form class="servidores-search mb-4" method="GET" action="{{ route('servidores.index') }}" autocomplete="off">
            @if($areaActual)
                <input type="hidden" name="area" value="{{ $areaActual }}">
            @endif

            <div class="row g-2 align-items-center">
                <div class="col-lg">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            id="servidoresSearchInput"
                            type="search"
                            name="buscar"
                            value="{{ $buscar }}"
                            class="form-control servidores-search-input"
                            placeholder="Buscar por nombre, apellido, N° item o contrato"
                            aria-label="Buscar servidores"
                            data-suggestions-url="{{ route('servidores.sugerencias') }}"
                            data-area="{{ $areaActual }}"
                        >
                    </div>
                </div>
                <div class="col-lg-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    @if($buscar)
                        <a href="{{ route('servidores.index', $areaActual ? ['area' => $areaActual] : []) }}" class="btn btn-outline-secondary px-3">
                            Limpiar
                        </a>
                    @endif
                </div>
            </div>

            <div id="servidoresSuggestions" class="servidores-suggestions"></div>

            @if($buscar)
                <div class="mt-2 text-muted" style="font-size: 0.86rem;">
                    Resultados para <strong>{{ $buscar }}</strong>: {{ $servidores->total() }} registro(s)
                </div>
            @endif
        </form>

        <!-- Tarjeta de estadísticas -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-0 bg-light rounded-3 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="badge bg-success bg-opacity-10 text-success p-3 me-3" style="font-size:1.3rem;">
                                        <i class="bi bi-briefcase-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-success">
                                            {{ $estadisticas['items'] }}
                                        </h5>
                                        <small class="text-muted">Items Activos</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="badge bg-info bg-opacity-10 text-info p-3 me-3" style="font-size:1.3rem;">
                                        <i class="bi bi-file-text-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-info">
                                            {{ $estadisticas['consultoria'] }}
                                        </h5>
                                        <small class="text-muted">Consultoría</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="badge bg-warning bg-opacity-10 text-warning p-3 me-3" style="font-size:1.3rem;">
                                        <i class="bi bi-shield-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-warning">
                                            {{ $estadisticas['inamoviles'] }}
                                        </h5>
                                        <small class="text-muted">Inamovibles</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="badge bg-danger bg-opacity-10 text-danger p-3 me-3" style="font-size:1.3rem;">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold text-danger">
                                            {{ $estadisticas['acefalias'] }}
                                        </h5>
                                        <small class="text-muted">Acefalías</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($areaActual && $resumenUnidades->count() > 1)
            <div class="mb-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-diagram-3-fill me-2"></i>Resumen por sub-unidad
                    </h6>
                    <small class="text-muted">{{ $resumenUnidades->count() }} sub-unidades</small>
                </div>
                <div class="area-summary-grid">
                    @foreach($resumenUnidades as $resumen)
                        <div class="area-summary-item">
                            <div class="area-summary-name">{{ $resumen['nombre'] }}</div>
                            <div class="area-summary-metrics">
                                <div class="area-summary-metric">
                                    <strong class="text-success">{{ $resumen['items'] }}</strong>
                                    Items
                                </div>
                                <div class="area-summary-metric">
                                    <strong class="text-info">{{ $resumen['consultoria'] }}</strong>
                                    Cons.
                                </div>
                                <div class="area-summary-metric">
                                    <strong class="text-warning">{{ $resumen['inamoviles'] }}</strong>
                                    Inam.
                                </div>
                                <div class="area-summary-metric">
                                    <strong class="text-danger">{{ $resumen['acefalias'] }}</strong>
                                    Acef.
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @forelse($servidores as $servidor)
            <div class="card mb-4 shadow-sm border-0 servidor-card" style="border-radius: 14px;">
                <div class="card-body p-4" style="padding: 1.8rem !important;">

                    <div class="servidor-row">
                        {{-- Foto --}}
                        <div class="text-center">
                            @if($servidor->fotografia_url)
                                <img src="{{ $servidor->fotografia_url }}" class="rounded-circle servidor-photo">
                            @else
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white servidor-avatar">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Info Principal --}}
                        <div class="servidor-info">
                            <div class="mb-3">
                                <h4 class="mb-1 fw-bold servidor-nombre" style="font-size: 1.4rem;">
                                    {{ $servidor->nombre }} {{ $servidor->apellido_paterno }} {{ $servidor->apellido_materno }}
                                </h4>

                                {{-- Badge de estado --}}
                                @if($servidor->acefalia)
                                    <span class="badge bg-danger me-2 px-3 py-1 servidor-badge" style="font-size:0.85rem;">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>ACEFALÍA
                                    </span>
                                @elseif($servidor->tipo === 'item')
                                    <span class="badge bg-primary me-2 px-3 py-1 servidor-badge" style="font-size:0.85rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i>ÍTEM
                                    </span>
                                @else
                                    <span class="badge bg-success me-2 px-3 py-1 servidor-badge" style="font-size:0.85rem;">
                                        <i class="bi bi-briefcase-fill me-1"></i>CONSULTORÍA
                                    </span>
                                @endif
                            </div>

                            {{-- Información del cargo --}}
                            <div class="mb-3" style="font-size:1rem;">
                                @if($servidor->tipo === 'item')
                                    <div class="text-muted mb-1">
                                        <i class="bi bi-hash me-1"></i><strong>N° Item:</strong> {{ $servidor->numero_item }}
                                    </div>
                                    <div class="text-muted mb-1">
                                        <i class="bi bi-briefcase me-1"></i><strong>Cargo:</strong> {{ $servidor->cargo }}
                                    </div>
                                    <div class="text-muted">
                                        <i class="bi bi-award me-1"></i><strong>Designación:</strong> {{ $servidor->designacion }}
                                    </div>
                                @else
                                    <div class="text-muted mb-1">
                                        <i class="bi bi-file-text me-1"></i><strong>Contrato:</strong>
                                        {{ $servidor->contrato_numero }}
                                    </div>
                                    <div class="text-muted mb-1">
                                        <i class="bi bi-briefcase me-1"></i><strong>Cargo:</strong>
                                        {{ $servidor->cargo_consultoria }}
                                    </div>
                                    <div class="text-muted">
                                        <i class="bi bi-award me-1"></i><strong>Designación:</strong> {{ $servidor->designacion }}
                                    </div>
                                @endif
                            </div>

                            {{-- Unidad y fecha --}}
                            <div class="servidor-meta text-muted" style="font-size:0.95rem;">
                                <div class="mb-1">
                                    <i class="bi bi-building me-1"></i>
                                    <strong>Unidad:</strong> {{ $servidor->unidad }}
                                    @if($servidor->sub_unidad)
                                        <br><i class="bi bi-door-open me-1"></i>
                                        <strong>Sub-unidad:</strong> {{ $servidor->sub_unidad }}
                                    @endif
                                </div>
                                <div class="mb-1">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    <strong>Ingreso Aduana:</strong>
                                    {{ $servidor->fecha_ingreso_aduana ? \Carbon\Carbon::parse($servidor->fecha_ingreso_aduana)->format('d/m/Y') : '—' }}
                                </div>
                            </div>

                            {{-- Inamovilidad --}}
                            @if($servidor->asignacion_familiar_desc || $servidor->casos_especiales_desc || $servidor->discapacidad_desc)
                                <div class="mt-3 p-3 bg-light rounded border-start border-4 border-warning">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-shield-fill text-warning me-2" style="font-size:1.1rem;"></i>
                                        <strong class="text-warning" style="font-size:1rem;">Inamovilidad:</strong>
                                    </div>
                                    <div class="text-muted" style="font-size:0.95rem;">
                                        @if($servidor->asignacion_familiar_desc)
                                            <div class="mb-1">
                                                <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                <strong>Asignación Familiar:</strong> {{ $servidor->asignacion_familiar_desc }}
                                                @if($servidor->asignacion_familiar_grado) <span
                                                class="badge bg-secondary ms-1">{{ $servidor->asignacion_familiar_grado }}</span>@endif
                                            </div>
                                        @endif
                                        @if($servidor->casos_especiales_desc)
                                            <div class="mb-1">
                                                <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                <strong>Casos Especiales:</strong> {{ $servidor->casos_especiales_desc }}
                                                @if($servidor->casos_especiales_grado) <span
                                                class="badge bg-secondary ms-1">{{ $servidor->casos_especiales_grado }}</span>@endif
                                            </div>
                                        @endif
                                        @if($servidor->discapacidad_desc)
                                            <div class="mb-1">
                                                <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                <strong>Discapacidad Ley N° 223:</strong> {{ $servidor->discapacidad_desc }}
                                                @if($servidor->discapacidad_grado) <span
                                                class="badge bg-secondary ms-1">{{ $servidor->discapacidad_grado }}</span>@endif
                                                @if($servidor->discapacidad_tipo) <small
                                                class="text-muted ms-2">({{ $servidor->discapacidad_tipo }})</small>@endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Acciones --}}
                        <div class="text-center">
                            <div class="servidor-actions">
                                <a href="{{ route('servidores.show', $servidor->id) }}" class="btn btn-primary px-3 py-2">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </a>
                                <a href="{{ route('servidores.edit', $servidor->id) }}" class="btn btn-warning px-3 py-2">
                                    <i class="bi bi-pencil me-1"></i>Editar
                                </a>
                                <button type="button" class="btn btn-danger px-3 py-2"
                                    data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                    data-servidor-id="{{ $servidor->id }}"
                                    data-servidor-nombre="{{ $servidor->nombre }} {{ $servidor->apellido_paterno }}">
                                    <i class="bi bi-trash me-1"></i>Eliminar
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="alert alert-info">No hay servidores registrados aún.</div>
        @endforelse

        {{-- PAGINACIÓN --}}
        <div class="servidores-pagination">
            {{ $servidores->links('pagination.bootstrap-5') }}
        </div>

    </div>

{{-- Modal confirmación eliminar --}}
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">⚠️ Confirmar eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro que deseas eliminar a
                <strong id="eliminarNombre"></strong>?
                <br><small class="text-muted">Esta acción no se puede deshacer.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">Cancelar</button>
                <form id="formEliminar" action="" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 py-2">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('modalEliminar');
    modal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-servidor-id');
        var nombre = button.getAttribute('data-servidor-nombre');
        document.getElementById('eliminarNombre').textContent = nombre;
        document.getElementById('formEliminar').action = '{{ route("servidores.destroy", "") }}/' + id;
    });

    var searchInput = document.getElementById('servidoresSearchInput');
    var suggestionsBox = document.getElementById('servidoresSuggestions');
    var searchTimer;

    function escapeHtml(value) {
        return String(value || '').replace(/[&<>"']/g, function(char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function hideSuggestions() {
        if (!suggestionsBox) {
            return;
        }

        suggestionsBox.classList.remove('is-visible');
        suggestionsBox.innerHTML = '';
    }

    function renderSuggestions(items) {
        if (!suggestionsBox) {
            return;
        }

        if (!items.length) {
            suggestionsBox.innerHTML = '<div class="servidores-suggestion text-muted">Sin coincidencias cercanas</div>';
            suggestionsBox.classList.add('is-visible');
            return;
        }

        suggestionsBox.innerHTML = items.map(function(item) {
            return `
                <a class="servidores-suggestion" href="${escapeHtml(item.url)}">
                    <div class="servidores-suggestion-title">
                        <span>${escapeHtml(item.nombre)}</span>
                        <span class="servidores-suggestion-code">${escapeHtml(item.codigo)}</span>
                    </div>
                    <div class="servidores-suggestion-meta">
                        ${escapeHtml(item.cargo)} · ${escapeHtml(item.unidad)}
                    </div>
                </a>
            `;
        }).join('');
        suggestionsBox.classList.add('is-visible');
    }

    if (searchInput && suggestionsBox) {
        searchInput.addEventListener('input', function() {
            var value = searchInput.value.trim();
            clearTimeout(searchTimer);

            if (value.length < 2) {
                hideSuggestions();
                return;
            }

            searchTimer = setTimeout(function() {
                var url = new URL(searchInput.dataset.suggestionsUrl, window.location.origin);
                url.searchParams.set('buscar', value);

                if (searchInput.dataset.area) {
                    url.searchParams.set('area', searchInput.dataset.area);
                }

                fetch(url.toString(), { headers: { 'Accept': 'application/json' } })
                    .then(function(response) { return response.json(); })
                    .then(renderSuggestions)
                    .catch(hideSuggestions);
            }, 180);
        });

        searchInput.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hideSuggestions();
            }
        });

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.servidores-search')) {
                hideSuggestions();
            }
        });
    }
});
</script>
@endsection
