@extends('layouts.app')

@section('content')
<div class="container">
    <h1>VISTA USUARIO FINAL</h1>
    <a href="{{ route('servidores.create') }}" class="btn btn-primary mb-3">+ AGREGAR SERVIDOR PÚBLICO</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($servidores as $servidor)
        <div class="col-md-4 mb-3">
            <div class="card">
                @if($servidor->fotografia)
                    <img src="{{ asset('storage/' . $servidor->fotografia) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                        <span class="text-white">Sin foto</span>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $servidor->nombre }} {{ $servidor->apellido_paterno }}</h5>
                    <p>
                        <span class="badge {{ $servidor->tipo == 'item' ? 'bg-primary' : 'bg-success' }}">
                            {{ $servidor->tipo == 'item' ? 'ITEM' : 'CONSULTORÍA' }}
                        </span>
                    </p>
                    @if($servidor->tipo == 'item')
                        <p><strong>Nº Item:</strong> {{ $servidor->numero_item }}</p>
                        <p><strong>Cargo:</strong> {{ $servidor->cargo }}</p>
                    @else
                        <p><strong>Contrato:</strong> {{ $servidor->contrato_numero }}</p>
                        <p><strong>Cargo:</strong> {{ $servidor->cargo_consultoria }}</p>
                    @endif
                    <a href="{{ route('servidores.show', $servidor->id) }}" class="btn btn-sm btn-info">Ver más</a>
                    <a href="{{ route('servidores.edit', $servidor->id) }}" class="btn btn-sm btn-warning">Editar</a>
                </div>
            </div>
        </div>
        @empty
            <div class="alert alert-info">No hay servidores registrados. ¡Agrega el primero!</div>
        @endforelse
    </div>
</div>
@endsection