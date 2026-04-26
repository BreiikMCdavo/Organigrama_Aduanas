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
                    {{ $servidor->unidad ?? ($servidor->tipo === 'item' ? 'UNIDAD ADMINISTRATIVA' : 'UNIDAD JURÍDICA') }}
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
                        <div class="label">
                            N° Ítem: <span class="valor">{{ $servidor->numero_item ?? '—' }}</span>
                            &nbsp;&nbsp; Cód. Funcionario: <span class="valor">{{ $servidor->cod_funcionario ?? '' }}</span>
                            &nbsp;&nbsp; Escala Salarial: <span class="valor">{{ $servidor->escala_salarial ?? '' }}</span>
                        </div>
                    </div>
                    <div class="dato-principal">
                        <div class="label">Cargo: <span class="valor">{{ $servidor->designacion ?? '' }}</span></div>
                    </div>
                    <div class="dato-principal">
                        <div class="cargo-desc">✔ {{ $servidor->cargo ?? '' }}</div>
                    </div>
                @else
                    <div class="dato-principal">
                        <div class="label">Contrato: <span class="valor">{{ $servidor->contrato_numero ?? '—' }}</span></div>
                    </div>
                    <div class="dato-principal">
                        <div class="label">Cargo: <span class="valor">{{ $servidor->designacion ?? '' }}</span></div>
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

                {{-- Designación con fechas --}}
                @if($servidor->designacion)
                <div class="inamovilidad-titulo mt-2">Designación:</div>
                @foreach(explode(', ', $servidor->designacion) as $tipo)
                <div class="inamovilidad-item">
                    <span class="check-icon">☑</span>
                    <span>
                        {{ $tipo }}
                        @if($tipo === 'Designación' && $servidor->designacion_inicio)
                            — {{ \Carbon\Carbon::parse($servidor->designacion_inicio)->format('d/m/Y') }}
                            @if($servidor->designacion_fin) al {{ \Carbon\Carbon::parse($servidor->designacion_fin)->format('d/m/Y') }} @endif
                        @elseif($tipo === 'Interinato' && $servidor->interinato_inicio)
                            — {{ \Carbon\Carbon::parse($servidor->interinato_inicio)->format('d/m/Y') }}
                            @if($servidor->interinato_fin) al {{ \Carbon\Carbon::parse($servidor->interinato_fin)->format('d/m/Y') }} @endif
                        @elseif($tipo === 'Comisión' && $servidor->comision_inicio)
                            — {{ \Carbon\Carbon::parse($servidor->comision_inicio)->format('d/m/Y') }}
                            @if($servidor->comision_fin) al {{ \Carbon\Carbon::parse($servidor->comision_fin)->format('d/m/Y') }} @endif
                        @endif
                    </span>
                </div>
                @endforeach
                @endif

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
                <a href="{{ route('index') }}" class="btn-volver">Volver al Organigrama</a>

            </div>

            {{-- Acciones debajo de la tarjeta --}}
            <div class="d-flex gap-2 mt-3 justify-content-center">
                <a href="{{ route('servidores.edit', $servidor->id) }}" class="btn btn-warning btn-sm">Editar</a>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar">
                    Eliminar
                </button>
            </div>
        </div>
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
                <strong>{{ $servidor->nombre }} {{ $servidor->apellido_paterno }}</strong>?
                <br><small class="text-muted">Esta acción no se puede deshacer.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('servidores.destroy', $servidor->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
