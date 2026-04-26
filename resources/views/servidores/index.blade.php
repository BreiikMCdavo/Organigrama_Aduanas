@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Servidores Públicos Registrados</h5>
            <a href="{{ route('servidores.create') }}" class="btn btn-primary btn-sm">+ Agregar Servidor Público</a>
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
                                        <i class="bi bi-file-text me-1"></i><strong>Contrato:</strong> {{ $servidor->contrato_numero }}
                                    </div>
                                    <div class="text-muted">
                                        <i class="bi bi-briefcase me-1"></i><strong>Cargo:</strong> {{ $servidor->cargo_consultoria }}
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
                        </div>

                        {{-- Acciones --}}
                        <div class="col-md-auto text-center">
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('servidores.show', $servidor->id) }}" 
                                   class="btn btn-sm btn-primary px-3">
                                    <i class="bi bi-eye me-1"></i>Ver
                                </a>
                                <a href="{{ route('servidores.edit', $servidor->id) }}"
                                   class="btn btn-sm btn-warning px-3">
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