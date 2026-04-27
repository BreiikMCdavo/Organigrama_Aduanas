@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-people-fill me-2"></i>Servidores Públicos Registrados
                </h5>
                <small class="text-muted">Gestión completa del personal</small>
            </div>
            <div class="d-flex gap-2">
                <div class="btn-group" role="group">
                    <a href="{{ route('reporte.items') }}"
                        class="btn btn-gradient-success btn-sm d-flex align-items-center px-3"
                        style="background: linear-gradient(135deg, #28a745, #20c997); border: none; color: white; box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3); transition: all 0.3s;">

                        <i class="bi bi-file-earmark-excel me-2 fs-3"></i>

                        <span>
                            <strong>Reporte Items</strong>
                            <br>
                            <small style="font-size: 0.7rem; opacity: 0.9;">Abrir en Excel</small>
                        </span>
                    </a>
                    <a href="{{ route('reporte.consultoria') }}"
                        class="btn btn-gradient-info btn-sm d-flex align-items-center px-3"
                        style="background: linear-gradient(135deg, #17a2b8, #6610f2); border: none; color: white; box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3); transition: all 0.3s;">

                        <i class="bi bi-file-earmark-excel me-2 fs-3"></i>

                        <span>
                            <strong>Reporte Consultoría</strong>
                            <br>
                            <small style="font-size: 0.7rem; opacity: 0.9;">Abrir en Excel</small>
                        </span>
                    </a>
                </div>
                <a href="{{ route('servidores.create') }}" class="btn btn-primary btn-sm d-flex align-items-center px-3"
                    style="box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3); transition: all 0.3s;">

                    <i class="bi bi-person-plus-fill me-2 fs-3"></i>

                    <span>
                        <strong>+ Nuevo Servidor</strong>
                        <br>
                        <small style="font-size: 0.7rem; opacity: 0.9;">Agregar registro</small>
                    </span>
                </a>
            </div>
        </div>

        <!-- Tarjeta de estadísticas -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-0 bg-light rounded-3 shadow-sm">
                    <div class="card-body p-3">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="badge bg-success bg-opacity-10 text-success p-2 me-2">
                                        <i class="bi bi-briefcase-fill"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-success">
                                            {{ \App\Models\ServidorPublico::where('tipo', 'item')->where(function ($q) {
        $q->whereNull('acefalia')->orWhere('acefalia', false); })->count() }}
                                        </h6>
                                        <small class="text-muted">Items Activos</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="badge bg-info bg-opacity-10 text-info p-2 me-2">
                                        <i class="bi bi-file-text-fill"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-info">
                                            {{ \App\Models\ServidorPublico::where('tipo', 'consultoria')->where(function ($q) {
        $q->whereNull('acefalia')->orWhere('acefalia', false); })->count() }}
                                        </h6>
                                        <small class="text-muted">Consultoría</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="badge bg-warning bg-opacity-10 text-warning p-2 me-2">
                                        <i class="bi bi-shield-fill"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-warning">
                                            {{ \App\Models\ServidorPublico::where(function ($q) {
        $q->whereNotNull('asignacion_familiar_desc')->where('asignacion_familiar_desc', '!=', '')->orWhereNotNull('casos_especiales_desc')->where('casos_especiales_desc', '!=', '')->orWhereNotNull('discapacidad_desc')->where('discapacidad_desc', '!=', ''); })->where(function ($q) {
            $q->whereNull('acefalia')->orWhere('acefalia', false); })->count() }}
                                        </h6>
                                        <small class="text-muted">Inamovibles</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="badge bg-danger bg-opacity-10 text-danger p-2 me-2">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-danger">
                                            {{ \App\Models\ServidorPublico::where('acefalia', true)->count() }}
                                        </h6>
                                        <small class="text-muted">Acefalías</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @forelse($servidores as $servidor)
            <div class="card mb-4 shadow-sm border-0 servidor-card" style="border-radius: 12px;">
                <div class="card-body p-4">

                    <div class="row align-items-center">
                        {{-- Foto --}}
                        <div class="col-md-auto text-center mb-3 mb-md-0">
                            @if($servidor->fotografia)
                                <img src="{{ asset('storage/' . $servidor->fotografia) }}" class="rounded-circle"
                                    style="width:80px;height:80px;object-fit:cover;border:3px solid #1565c0;box-shadow:0 2px 8px rgba(0,0,0,0.15);">
                            @else
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                                    style="width:80px;height:80px;font-size:2rem;flex-shrink:0;box-shadow:0 2px 8px rgba(0,0,0,0.15);">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Info Principal --}}
                        <div class="col-md">
                            <div class="mb-2">
                                <h5 class="mb-1 fw-bold servidor-nombre" style="font-size: 1.25rem;">
                                    {{ $servidor->nombre }} {{ $servidor->apellido_paterno }} {{ $servidor->apellido_materno }}
                                </h5>

                                {{-- Badge de estado --}}
                                @if($servidor->acefalia)
                                    <span class="badge bg-danger me-2 px-3 py-1 servidor-badge">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>ACEFALÍA
                                    </span>
                                @elseif($servidor->tipo === 'item')
                                    <span class="badge bg-primary me-2 px-3 py-1 servidor-badge">
                                        <i class="bi bi-check-circle-fill me-1"></i>ÍTEM
                                    </span>
                                @else
                                    <span class="badge bg-success me-2 px-3 py-1 servidor-badge">
                                        <i class="bi bi-briefcase-fill me-1"></i>CONSULTORÍA
                                    </span>
                                @endif
                            </div>

                            {{-- Información del cargo --}}
                            <div class="mb-2">
                                @if($servidor->tipo === 'item')
                                    <div class="text-muted">
                                        <i class="bi bi-hash me-1"></i><strong>N° Item:</strong> {{ $servidor->numero_item }}
                                    </div>
                                    <div class="text-muted">
                                        <i class="bi bi-briefcase me-1"></i><strong>Cargo:</strong> {{ $servidor->cargo }}
                                    </div>
                                    <div class="text-muted">
                                        <i class="bi bi-award me-1"></i><strong>Designación:</strong> {{ $servidor->designacion }}
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="bi bi-file-text me-1"></i><strong>Contrato:</strong>
                                        {{ $servidor->contrato_numero }}
                                    </div>
                                    <div class="text-muted">
                                        <i class="bi bi-briefcase me-1"></i><strong>Cargo:</strong>
                                        {{ $servidor->cargo_consultoria }}
                                    </div>
                                    <div class="text-muted">
                                        <i class="bi bi-award me-1"></i><strong>Designación:</strong> {{ $servidor->designacion }}
                                    </div>
                                @endif
                            </div>

                            {{-- Unidad y fecha --}}
                            <div class="row text-muted small">
                                <div class="col-md-6">
                                    <i class="bi bi-building me-1"></i>
                                    <strong>Unidad:</strong> {{ $servidor->unidad }}
                                    @if($servidor->sub_unidad)
                                        <br><i class="bi bi-door-open me-1"></i>
                                        <strong>Sub-unidad:</strong> {{ $servidor->sub_unidad }}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    <strong>Ingreso Aduana:</strong>
                                    {{ $servidor->fecha_ingreso_aduana ? \Carbon\Carbon::parse($servidor->fecha_ingreso_aduana)->format('d/m/Y') : '—' }}
                                </div>
                            </div>

                            {{-- Inamovilidad --}}
                            @if($servidor->asignacion_familiar_desc || $servidor->casos_especiales_desc || $servidor->discapacidad_desc)
                                <div class="mt-2 p-2 bg-light rounded border-start border-4 border-warning">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="bi bi-shield-fill text-warning me-2"></i>
                                        <strong class="text-warning">Inamovilidad:</strong>
                                    </div>
                                    <div class="small text-muted">
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
                        <div class="col-md-auto text-center">
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('servidores.show', $servidor->id) }}" class="btn btn-sm btn-primary px-3">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </a>
                                <a href="{{ route('servidores.edit', $servidor->id) }}" class="btn btn-sm btn-warning px-3">
                                    <i class="bi bi-pencil me-1"></i>Editar
                                </a>
                                <form action="{{ route('servidores.destroy', $servidor->id) }}" method="POST"
                                    onsubmit="return confirm('¿Está seguro de eliminar este servidor?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger px-3">
                                        <i class="bi bi-trash me-1"></i>Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="alert alert-info">No hay servidores registrados aún.</div>
        @endforelse

        {{-- PAGINACIÓN --}}
        <div class="mt-3 d-flex justify-content-center">
            {{ $servidores->links('pagination::bootstrap-5') }}
        </div>

    </div>
@endsection