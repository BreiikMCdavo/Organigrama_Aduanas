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
                @if($servidor->fotografia_url)
                    <img src="{{ $servidor->fotografia_url }}" class="foto-circulo" alt="Foto">
                @else
                    <div class="foto-placeholder">👤</div>
                @endif

                {{-- Datos principales --}}
                @if($servidor->tipo === 'item')
                    <div class="dato-principal">
                        <div class="valor">N° Ítem: {{ $servidor->numero_item ?? '—' }}</div>
                        <div style="font-size:0.9rem; color:#a0b8d8; margin-top:4px;">
                            @if($servidor->cod_funcionario)
                                Cód. Funcionario: <span style="color:#fff;">{{ $servidor->cod_funcionario }}</span>
                            @endif
                            @if($servidor->escala_salarial)
                                &nbsp; Escala Salarial: <span style="color:#fff;">{{ $servidor->escala_salarial }} Bs.</span>
                            @endif
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
                <div class="fechas-row" style="display:flex; justify-content:space-between;">
                    <div>
                        <div>Ingreso a la Aduana:</div>
                        <div><strong>{{ $servidor->fecha_ingreso_aduana ? \Carbon\Carbon::parse($servidor->fecha_ingreso_aduana)->format('d/m/Y') : '—' }}</strong></div>
                    </div>
                    @if($servidor->tipo === 'item')
                    <div>
                        <div>Fecha Inic. cargo:</div>
                        <div><strong>{{ $servidor->fecha_inicio_cargo ? \Carbon\Carbon::parse($servidor->fecha_inicio_cargo)->format('d/m/Y') : '—' }}</strong></div>
                    </div>
                    @else
                    <div>
                        <div>Fecha del contrato:</div>
                        <div><strong>{{ $servidor->fecha_inicio_contrato ? \Carbon\Carbon::parse($servidor->fecha_inicio_contrato)->format('d/m/Y') : '—' }}</strong></div>
                    </div>
                    @endif
                </div>

                {{-- Inamovilidad --}}
                <div class="inamovilidad-titulo">Inamovilidad:</div>

                @if($servidor->asignacion_familiar_desc)
                <div class="inamovilidad-item" style="justify-content:space-between;">
                    <span><span class="check-icon">☑</span> Asignación Familiar: {{ $servidor->asignacion_familiar_desc }}</span>
                    <span style="color:#4fc3f7; white-space:nowrap;">Grado: <strong>{{ $servidor->asignacion_familiar_grado }}</strong></span>
                </div>
                @endif

                @if($servidor->casos_especiales_desc)
                <div class="inamovilidad-item" style="justify-content:space-between;">
                    <span><span class="check-icon">☑</span> Casos especiales: {{ $servidor->casos_especiales_desc }}</span>
                    <span style="color:#4fc3f7; white-space:nowrap;">Grado: <strong>{{ $servidor->casos_especiales_grado }}</strong></span>
                </div>
                @endif

                @if($servidor->discapacidad_desc)
                <div class="inamovilidad-item" style="justify-content:space-between; align-items:flex-start;">
                    <span>
                        <span class="check-icon">☑</span> Discapacidad Ley N° 223: {{ $servidor->discapacidad_desc }}
                        @if($servidor->discapacidad_tipo || $servidor->discapacidad_carnet || $servidor->discapacidad_vence)
                        <div class="mt-1 ms-2 d-flex flex-column" style="font-size:0.9rem;color:#c8daf0;">
                            @if($servidor->discapacidad_tipo)<span>Tipo: {{ $servidor->discapacidad_tipo }}</span>@endif
                            @if($servidor->discapacidad_carnet)<span>Carnet: {{ $servidor->discapacidad_carnet }}</span>@endif
                            @if($servidor->discapacidad_vence)<span>Vence: {{ \Carbon\Carbon::parse($servidor->discapacidad_vence)->format('d/m/Y') }}</span>@endif
                        </div>
                        @endif
                    </span>
                    <span style="color:#4fc3f7; white-space:nowrap;">Grado: <strong>{{ $servidor->discapacidad_grado }}</strong></span>
                </div>
                @endif

                {{-- Botón volver al organigrama --}}
                <a href="{{ route('index') }}" class="btn-volver">Volver al Organigrama</a>

            </div>

            {{-- Acciones debajo de la tarjeta --}}
            <div class="d-flex gap-3 mt-4 justify-content-center flex-wrap">
                <a href="{{ route('servidores.index') }}"
                    class="btn d-inline-flex align-items-center gap-2 px-4 py-2 border-0 rounded-3 fw-semibold"
                    style="background: linear-gradient(135deg, #6c757d, #495057); color:#fff; box-shadow:0 3px 10px rgba(108,117,125,0.3); transition:all 0.25s ease;"
                    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 18px rgba(108,117,125,0.5)'"
                    onmouseout="this.style.transform='';this.style.boxShadow='0 3px 10px rgba(108,117,125,0.3)'">
                    <i class="bi bi-arrow-left" style="font-size:1.1rem;"></i> Volver
                </a>
                <a href="{{ route('servidores.edit', $servidor->id) }}"
                    class="btn d-inline-flex align-items-center gap-2 px-4 py-2 border-0 rounded-3 fw-semibold"
                    style="background: linear-gradient(135deg, #f9a825, #ff8f00); color:#fff; box-shadow:0 3px 10px rgba(249,168,37,0.3); transition:all 0.25s ease;"
                    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 18px rgba(249,168,37,0.5)'"
                    onmouseout="this.style.transform='';this.style.boxShadow='0 3px 10px rgba(249,168,37,0.3)'">
                    <i class="bi bi-pencil" style="font-size:1.1rem;"></i> Editar
                </a>
                <button type="button" data-bs-toggle="modal" data-bs-target="#modalEliminar"
                    class="btn d-inline-flex align-items-center gap-2 px-4 py-2 border-0 rounded-3 fw-semibold"
                    style="background: linear-gradient(135deg, #e53935, #c62828); color:#fff; box-shadow:0 3px 10px rgba(229,57,53,0.3); transition:all 0.25s ease;"
                    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 18px rgba(229,57,53,0.5)'"
                    onmouseout="this.style.transform='';this.style.boxShadow='0 3px 10px rgba(229,57,53,0.3)'">
                    <i class="bi bi-trash" style="font-size:1.1rem;"></i> Eliminar
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
                <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('servidores.destroy', $servidor->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 py-2">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection