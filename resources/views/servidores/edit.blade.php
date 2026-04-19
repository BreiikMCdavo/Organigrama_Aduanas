@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-md-8">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('servidores.update', $servidor->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="tipo" value="{{ $servidor->tipo }}">

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">

                        @if($servidor->tipo === 'item')
                            <h6 class="fw-bold mb-3">Editar Datos del Ítem</h6>

                            <div class="row g-2 mb-2">
                                <div class="col-3">
                                    <label class="form-label small mb-1">N° Ítem</label>
                                    <input type="text" name="numero_item" class="form-control form-control-sm" value="{{ old('numero_item', $servidor->numero_item) }}">
                                </div>
                                <div class="col-5">
                                    <label class="form-label small mb-1">CITE Memorandum</label>
                                    <input type="text" name="cite_memorandum" class="form-control form-control-sm" value="{{ old('cite_memorandum', $servidor->cite_memorandum) }}">
                                </div>
                                <div class="col-4">
                                    <label class="form-label small mb-1">Cargo (Designación)</label>
                                    <select name="designacion" class="form-select form-select-sm">
                                        <option value="">Seleccionar</option>
                                        @foreach(['Designación','Interinato','Comisión'] as $op)
                                            <option value="{{ $op }}" {{ old('designacion', $servidor->designacion)==$op?'selected':'' }}>{{ $op }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-4">
                                    <input type="text" name="nombre" class="form-control form-control-sm" placeholder="Nombres" value="{{ old('nombre', $servidor->nombre) }}">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="apellido_paterno" class="form-control form-control-sm" placeholder="Apellido Paterno" value="{{ old('apellido_paterno', $servidor->apellido_paterno) }}">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="apellido_materno" class="form-control form-control-sm" placeholder="Apellido Materno" value="{{ old('apellido_materno', $servidor->apellido_materno) }}">
                                </div>
                            </div>

                            <div class="mb-2">
                                <input type="text" name="cargo" class="form-control form-control-sm" placeholder="Nombre del cargo..." value="{{ old('cargo', $servidor->cargo) }}">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small mb-1">Fecha de ingreso a la Aduana</label>
                                    <input type="date" name="fecha_ingreso_aduana" class="form-control form-control-sm" value="{{ old('fecha_ingreso_aduana', $servidor->fecha_ingreso_aduana) }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Fecha de inicio cargo</label>
                                    <input type="date" name="fecha_inicio_cargo" class="form-control form-control-sm" value="{{ old('fecha_inicio_cargo', $servidor->fecha_inicio_cargo) }}">
                                </div>
                            </div>

                        @else
                            <h6 class="fw-bold mb-3">Editar Datos de Consultoría</h6>

                            <div class="row g-2 mb-2">
                                <div class="col-7">
                                    <label class="form-label small mb-1">Contrato</label>
                                    <input type="text" name="contrato_numero" class="form-control form-control-sm" value="{{ old('contrato_numero', $servidor->contrato_numero) }}">
                                </div>
                                <div class="col-5">
                                    <label class="form-label small mb-1">Cargo (Designación)</label>
                                    <select name="designacion" class="form-select form-select-sm">
                                        <option value="">Seleccionar</option>
                                        @foreach(['Designación','Interinato','Comisión'] as $op)
                                            <option value="{{ $op }}" {{ old('designacion', $servidor->designacion)==$op?'selected':'' }}>{{ $op }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-4">
                                    <input type="text" name="nombre" class="form-control form-control-sm" placeholder="Nombres" value="{{ old('nombre', $servidor->nombre) }}">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="apellido_paterno" class="form-control form-control-sm" placeholder="Apellido Paterno" value="{{ old('apellido_paterno', $servidor->apellido_paterno) }}">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="apellido_materno" class="form-control form-control-sm" placeholder="Apellido Materno" value="{{ old('apellido_materno', $servidor->apellido_materno) }}">
                                </div>
                            </div>

                            <div class="mb-2">
                                <input type="text" name="cargo_consultoria" class="form-control form-control-sm" placeholder="Descripción del cargo..." value="{{ old('cargo_consultoria', $servidor->cargo_consultoria) }}">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-4">
                                    <label class="form-label small mb-1">Fecha ingreso Aduana</label>
                                    <input type="date" name="fecha_ingreso_aduana" class="form-control form-control-sm" value="{{ old('fecha_ingreso_aduana', $servidor->fecha_ingreso_aduana) }}">
                                </div>
                                <div class="col-4">
                                    <label class="form-label small mb-1">Fecha inicio contrato</label>
                                    <input type="date" name="fecha_inicio_contrato" class="form-control form-control-sm" value="{{ old('fecha_inicio_contrato', $servidor->fecha_inicio_contrato) }}">
                                </div>
                                <div class="col-4">
                                    <label class="form-label small mb-1">Fecha fin contrato</label>
                                    <input type="date" name="fecha_fin_contrato" class="form-control form-control-sm" value="{{ old('fecha_fin_contrato', $servidor->fecha_fin_contrato) }}">
                                </div>
                            </div>
                        @endif

                        {{-- Inamovilidad (común) --}}
                        <p class="fw-bold small mb-2">Inamovilidad:</p>

                        @foreach([
                            ['label'=>'1. Asignación Familiar:', 'desc'=>'asignacion_familiar_desc', 'grado'=>'asignacion_familiar_grado'],
                            ['label'=>'2. Casos especiales:', 'desc'=>'casos_especiales_desc', 'grado'=>'casos_especiales_grado'],
                            ['label'=>'3. Discapacidad Ley N° 223:', 'desc'=>'discapacidad_desc', 'grado'=>'discapacidad_grado'],
                        ] as $campo)
                        <div class="row g-2 align-items-center mb-2">
                            <div class="col-7">
                                <label class="form-label small mb-0">{{ $campo['label'] }}</label>
                                <input type="text" name="{{ $campo['desc'] }}" class="form-control form-control-sm"
                                       placeholder="Ingresar descripción..."
                                       value="{{ old($campo['desc'], $servidor->{$campo['desc']}) }}">
                            </div>
                            <div class="col-2">
                                <label class="form-label small mb-0">Grado:</label>
                            </div>
                            <div class="col-3">
                                <select name="{{ $campo['grado'] }}" class="form-select form-select-sm">
                                    @foreach(['G','MG','M','L'] as $g)
                                        <option value="{{ $g }}" {{ old($campo['grado'], $servidor->{$campo['grado']})==$g?'selected':'' }}>{{ $g }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endforeach

                        {{-- Foto --}}
                        <div class="mt-3">
                            <label class="form-label small mb-1">📷 Fotografía</label>
                            <div class="d-flex align-items-center gap-3">
                                @if($servidor->fotografia)
                                    <img src="{{ asset('storage/' . $servidor->fotografia) }}"
                                         id="preview-edit" class="rounded-circle"
                                         style="width:50px;height:50px;object-fit:cover;border:2px solid #1565c0;">
                                @else
                                    <img id="preview-edit" src="" class="rounded-circle"
                                         style="width:50px;height:50px;object-fit:cover;display:none;">
                                @endif
                                <label class="btn btn-sm btn-outline-secondary mb-0">
                                    Cambiar imagen
                                    <input type="file" name="fotografia" accept="image/*" class="d-none"
                                           onchange="previewEdit(event)">
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-sm px-4">Actualizar</button>
                            <a href="{{ route('servidores.show', $servidor->id) }}" class="btn btn-secondary btn-sm px-4">Cancelar</a>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewEdit(event) {
    const img = document.getElementById('preview-edit');
    const file = event.target.files[0];
    if (file) {
        img.src = URL.createObjectURL(file);
        img.style.display = 'inline-block';
    }
}
</script>
@endsection
