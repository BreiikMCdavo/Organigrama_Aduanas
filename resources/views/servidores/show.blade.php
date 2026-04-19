@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/servidor_show.css') }}">
@endpush

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="tarjeta-servidor">

                {{-- Título unidad --}}
                <div class="unidad-titulo">
                    @if($servidor->tipo === 'item')
                        UNIDAD ADMINISTRATIVA
                    @else
                        UNIDAD JURÍDICA
                    @endif
                </div>

                {{-- Foto --}}
                @if($servidor->fotografia)
                    <img src="{{ asset('storage/' . $servidor->fotografia) }}" class="foto-circulo" alt="Foto">
                @else
                    <div class="foto-placeholder">👤</div>
                @endif

                {{-- Datos principales --}}
                @if($servidor->tipo === 'item')
                    <div class="dato-principal">
                        <div class="label">N° Ítem: <span class="valor">{{ $servidor->numero_item ?? '—' }}</span></div>
                    </div>
                    <div class="dato-principal">
                        <div class="label">Cargo: <span class="valor">{{ $servidor->designacion ?? '—' }}</span></div>
                    </div>
                    <div class="dato-principal">
                        <div class="cargo-desc">✔ {{ $servidor->cargo ?? '' }}</div>
                    </div>
                @else
                    <div class="dato-principal">
                        <div class="label">Contrato: <span class="valor">{{ $servidor->contrato_numero ?? '—' }}</span></div>
                    </div>
                    <div class="dato-principal">
                        <div class="label">Cargo: <span class="valor">{{ $servidor->designacion ?? '—' }}</span></div>
                    </div>
                    <div class="dato-principal">
                        <div class="cargo-desc">{{ $servidor->cargo_consultoria ?? '' }}</div>
                    </div>
                @endif

                <hr class="divider">

                {{-- Nombre --}}
                <div class="nombre-completo">
                    Nombre: {{ $servidor->nombre }} {{ $servidor->apellido_paterno }} {{ $servidor->apellido_materno }}
                </div>

                {{-- Fechas --}}
                <div class="fechas-row">
                    <span>Ingreso a la Aduana: {{ $servidor->fecha_ingreso_aduana ? \Carbon\Carbon::parse($servidor->fecha_ingreso_aduana)->format('d/m/Y') : '—' }}</span>
                    @if($servidor->tipo === 'item')
                        <span>Fecha Inic. cargo: {{ $servidor->fecha_inicio_cargo ? \Carbon\Carbon::parse($servidor->fecha_inicio_cargo)->format('d/m/Y') : '—' }}</span>
                    @else
                        <span>Fecha del contrato: {{ $servidor->fecha_inicio_contrato ? \Carbon\Carbon::parse($servidor->fecha_inicio_contrato)->format('d/m/Y') : '—' }}</span>
                    @endif
                </div>

                {{-- Inamovilidad --}}
                <div class="inamovilidad-titulo">Inamovilidad:</div>

                @if($servidor->asignacion_familiar_desc)
                <div class="inamovilidad-item">
                    <span class="check-icon">☑</span>
                    <span>Asignación Familiar: {{ $servidor->asignacion_familiar_desc }} &nbsp; Grado: <strong>{{ $servidor->asignacion_familiar_grado }}</strong></span>
                </div>
                @endif

                @if($servidor->casos_especiales_desc)
                <div class="inamovilidad-item">
                    <span class="check-icon">☑</span>
                    <span>Casos especiales: {{ $servidor->casos_especiales_desc }} &nbsp; Grado: <strong>{{ $servidor->casos_especiales_grado }}</strong></span>
                </div>
                @endif

                @if($servidor->discapacidad_desc)
                <div class="inamovilidad-item">
                    <span class="check-icon">☑</span>
                    <span>Discapacidad Ley N° 223: {{ $servidor->discapacidad_desc }} &nbsp; Grado: <strong>{{ $servidor->discapacidad_grado }}</strong></span>
                </div>
                @endif

                {{-- Botón volver --}}
                <a href="{{ route('servidores.index') }}" class="btn-volver">Volver al Organigrama</a>

            </div>

            {{-- Acciones debajo de la tarjeta --}}
            <div class="d-flex gap-2 mt-3 justify-content-center">
                <a href="{{ route('servidores.edit', $servidor->id) }}" class="btn btn-warning btn-sm">Editar</a>
                <form action="{{ route('servidores.destroy', $servidor->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este servidor?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
