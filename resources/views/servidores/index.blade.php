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
            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body d-flex align-items-center gap-3">

                    {{-- Foto --}}
                    @if($servidor->fotografia)
                        <img src="{{ asset('storage/' . $servidor->fotografia) }}" class="rounded-circle"
                            style="width:60px;height:60px;object-fit:cover;border:2px solid #1565c0;">
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                            style="width:60px;height:60px;font-size:1.5rem;flex-shrink:0;">👤</div>
                    @endif

                    {{-- Info --}}
                    <div class="flex-grow-1">
                        <div class="fw-bold">{{ $servidor->nombre }} {{ $servidor->apellido_paterno }}
                            {{ $servidor->apellido_materno }}</div>
                        <div class="text-muted small">
                            @if($servidor->acefalia)
                                <span class="badge bg-danger me-1">ACEFALÍA</span>
                                @if($servidor->tipo === 'item')
                                    N° {{ $servidor->numero_item }} &bull; {{ $servidor->cargo }}
                                @else
                                    Contrato: {{ $servidor->contrato_numero }} &bull; {{ $servidor->cargo_consultoria }}
                                @endif
                            @elseif($servidor->tipo === 'item')
                                <span class="badge bg-primary me-1">ÍTEM</span>
                                N° {{ $servidor->numero_item }} &bull; {{ $servidor->cargo }} &bull; {{ $servidor->designacion }}
                            @else
                                <span class="badge bg-success me-1">CONSULTORÍA</span>
                                Contrato: {{ $servidor->contrato_numero }} &bull; {{ $servidor->cargo_consultoria }} &bull;
                                {{ $servidor->designacion }}
                            @endif
                        </div>
                        <div class="text-muted small">
                            Ingreso Aduana:
                            {{ $servidor->fecha_ingreso_aduana ? \Carbon\Carbon::parse($servidor->fecha_ingreso_aduana)->format('d/m/Y') : '—' }}
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="d-flex gap-2">
                        <a href="{{ route('servidores.show', $servidor->id) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                        <a href="{{ route('servidores.edit', $servidor->id) }}"
                            class="btn btn-sm btn-outline-warning">Editar</a>
                        <form action="{{ route('servidores.destroy', $servidor->id) }}" method="POST"
                            onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
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