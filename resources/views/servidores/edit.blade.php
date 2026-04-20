@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
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
                            <h5 class="fw-bold mb-3">Editar Datos del Ítem</h5>

                            <div class="row g-3 mb-3">
                                <div class="col-3">
                                    <label class="form-label">N° Ítem</label>
                                    <input type="text" name="numero_item" class="form-control" value="{{ old('numero_item', $servidor->numero_item) }}">
                                </div>
                                <div class="col-5">
                                    <label class="form-label">CITE Memorandum</label>
                                    <input type="text" name="cite_memorandum" class="form-control" value="{{ old('cite_memorandum', $servidor->cite_memorandum) }}">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Designación</label>
                                    <select name="designacion" class="form-select">
                                        <option value="">Seleccionar</option>
                                        @foreach(['Designación','Interinato','Comisión'] as $op)
                                            <option value="{{ $op }}" {{ old('designacion', $servidor->designacion)==$op?'selected':'' }}>{{ $op }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-4">
                                    <input type="text" name="nombre" class="form-control" placeholder="Nombres" value="{{ old('nombre', $servidor->nombre) }}">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="apellido_paterno" class="form-control" placeholder="Apellido Paterno" value="{{ old('apellido_paterno', $servidor->apellido_paterno) }}">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="apellido_materno" class="form-control" placeholder="Apellido Materno" value="{{ old('apellido_materno', $servidor->apellido_materno) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="text" name="cargo" class="form-control" placeholder="Nombre del cargo..." value="{{ old('cargo', $servidor->cargo) }}">
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label">Unidad</label>
                                    <select name="unidad" id="unidad_edit" class="form-select" onchange="cargarSubsEdit()">
                                        <option value="">Seleccionar Unidad</option>
                                        @foreach(['GERENCIA REGIONAL LA PAZ - GRLPZ','Unidad Administrativa','Unidad Fiscalización','Unidad Jurídica','Administración Aduana Interior La Paz','Aduana Frontera Guayaramerín','Aduana Aeropuerto El Alto','Administración Aduana Zona Franca'] as $u)
                                            <option value="{{ $u }}" {{ old('unidad', $servidor->unidad)==$u?'selected':'' }}>{{ $u }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Sub-Unidad</label>
                                    <select name="sub_unidad" id="sub_unidad_edit" class="form-select">
                                        <option value="">Seleccionar Sub-Unidad</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label">Fecha de ingreso a la Aduana</label>
                                    <input type="date" name="fecha_ingreso_aduana" class="form-control"
                                        value="{{ old('fecha_ingreso_aduana', $servidor->fecha_ingreso_aduana ? \Carbon\Carbon::parse($servidor->fecha_ingreso_aduana)->format('Y-m-d') : '') }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Fecha de inicio cargo</label>
                                    <input type="date" name="fecha_inicio_cargo" class="form-control"
                                        value="{{ old('fecha_inicio_cargo', $servidor->fecha_inicio_cargo ? \Carbon\Carbon::parse($servidor->fecha_inicio_cargo)->format('Y-m-d') : '') }}">
                                </div>
                            </div>

                        @else
                            <h5 class="fw-bold mb-3">Editar Datos de Consultoría</h5>

                            <div class="row g-3 mb-3">
                                <div class="col-7">
                                    <label class="form-label">Contrato</label>
                                    <input type="text" name="contrato_numero" class="form-control" value="{{ old('contrato_numero', $servidor->contrato_numero) }}">
                                </div>
                                <div class="col-5">
                                    <label class="form-label">Designación</label>
                                    <select name="designacion" class="form-select">
                                        <option value="">Seleccionar</option>
                                        @foreach(['Designación','Interinato','Comisión'] as $op)
                                            <option value="{{ $op }}" {{ old('designacion', $servidor->designacion)==$op?'selected':'' }}>{{ $op }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-4">
                                    <input type="text" name="nombre" class="form-control" placeholder="Nombres" value="{{ old('nombre', $servidor->nombre) }}">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="apellido_paterno" class="form-control" placeholder="Apellido Paterno" value="{{ old('apellido_paterno', $servidor->apellido_paterno) }}">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="apellido_materno" class="form-control" placeholder="Apellido Materno" value="{{ old('apellido_materno', $servidor->apellido_materno) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="text" name="cargo_consultoria" class="form-control" placeholder="Descripción del cargo..." value="{{ old('cargo_consultoria', $servidor->cargo_consultoria) }}">
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label">Unidad</label>
                                    <select name="unidad" id="unidad_edit" class="form-select" onchange="cargarSubsEdit()">
                                        <option value="">Seleccionar Unidad</option>
                                        @foreach(['GERENCIA REGIONAL LA PAZ - GRLPZ','Unidad Administrativa','Unidad Fiscalización','Unidad Jurídica','Administración Aduana Interior La Paz','Aduana Frontera Guayaramerín','Aduana Aeropuerto El Alto','Administración Aduana Zona Franca'] as $u)
                                            <option value="{{ $u }}" {{ old('unidad', $servidor->unidad)==$u?'selected':'' }}>{{ $u }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Sub-Unidad</label>
                                    <select name="sub_unidad" id="sub_unidad_edit" class="form-select">
                                        <option value="">Seleccionar Sub-Unidad</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-4">
                                    <label class="form-label">Fecha ingreso Aduana</label>
                                    <input type="date" name="fecha_ingreso_aduana" class="form-control"
                                        value="{{ old('fecha_ingreso_aduana', $servidor->fecha_ingreso_aduana ? \Carbon\Carbon::parse($servidor->fecha_ingreso_aduana)->format('Y-m-d') : '') }}">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Fecha inicio contrato</label>
                                    <input type="date" name="fecha_inicio_contrato" class="form-control"
                                        value="{{ old('fecha_inicio_contrato', $servidor->fecha_inicio_contrato ? \Carbon\Carbon::parse($servidor->fecha_inicio_contrato)->format('Y-m-d') : '') }}">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Fecha fin contrato</label>
                                    <input type="date" name="fecha_fin_contrato" class="form-control"
                                        value="{{ old('fecha_fin_contrato', $servidor->fecha_fin_contrato ? \Carbon\Carbon::parse($servidor->fecha_fin_contrato)->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        @endif

                        {{-- Inamovilidad --}}
                        <p class="fw-bold mb-3">Inamovilidad:</p>
                        @foreach([
                            ['label'=>'1. Asignación Familiar:','desc'=>'asignacion_familiar_desc','grado'=>'asignacion_familiar_grado'],
                            ['label'=>'2. Casos especiales:','desc'=>'casos_especiales_desc','grado'=>'casos_especiales_grado'],
                            ['label'=>'3. Discapacidad Ley N° 223:','desc'=>'discapacidad_desc','grado'=>'discapacidad_grado'],
                        ] as $campo)
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-7">
                                <label class="form-label mb-1">{{ $campo['label'] }}</label>
                                <input type="text" name="{{ $campo['desc'] }}" class="form-control"
                                       placeholder="Ingresar descripción..."
                                       value="{{ old($campo['desc'], $servidor->{$campo['desc']}) }}">
                            </div>
                            <div class="col-2">
                                <label class="form-label mb-1">Grado:</label>
                            </div>
                            <div class="col-3">
                                <select name="{{ $campo['grado'] }}" class="form-select">
                                    @foreach(['G','MG','M','L'] as $g)
                                        <option value="{{ $g }}" {{ old($campo['grado'], $servidor->{$campo['grado']})==$g?'selected':'' }}>{{ $g }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endforeach

                        {{-- Foto --}}
                        <div class="mt-3 mb-4">
                            <label class="form-label">📷 Fotografía</label>
                            <div class="d-flex align-items-center gap-3">
                                @if($servidor->fotografia)
                                    <img src="{{ asset('storage/' . $servidor->fotografia) }}"
                                         id="preview-edit" class="rounded-circle"
                                         style="width:55px;height:55px;object-fit:cover;border:2px solid #1565c0;">
                                @else
                                    <img id="preview-edit" src="" class="rounded-circle"
                                         style="width:55px;height:55px;object-fit:cover;display:none;">
                                @endif
                                <label class="btn btn-outline-secondary mb-0">
                                    Cambiar imagen
                                    <input type="file" name="fotografia" accept="image/*" class="d-none" onchange="previewEdit(event)">
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">Actualizar</button>
                            <a href="{{ route('servidores.show', $servidor->id) }}" class="btn btn-secondary px-4">Cancelar</a>
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

const datosUnidades = {
    "GERENCIA REGIONAL LA PAZ - GRLPZ": ["ASESORÍA","SECRETARIA","SISTEMAS","USO","ARCHIVO"],
    "Unidad Administrativa": ["Contabilidad","Activos Fijos","Talento Humano","Contrataciones","Servicios Generales"],
    "Unidad Fiscalización": ["Fiscalizaciones posteriores","Controles diferidos"],
    "Unidad Jurídica": ["Cobranza coactiva","Técnica jurídica","Procesos administrativos"],
    "Administración Aduana Interior La Paz": ["SPCC (Comisos)","Disposición de mercancías","Despachos","Gestión"],
    "Aduana Frontera Guayaramerín": ["Operaciones","Control","Administración"],
    "Aduana Aeropuerto El Alto": ["Carga Aérea","Equipajes","Administración"],
    "Administración Aduana Zona Franca": ["Operaciones","Control","Administración"]
};

function cargarSubsEdit() {
    const unidad = document.getElementById('unidad_edit').value;
    const sel = document.getElementById('sub_unidad_edit');
    sel.innerHTML = '<option value="">Seleccionar Sub-Unidad</option>';
    (datosUnidades[unidad] || []).forEach(function(sub) {
        const opt = document.createElement('option');
        opt.value = sub; opt.text = sub;
        sel.appendChild(opt);
    });
}

// Cargar subunidades al abrir la página
document.addEventListener('DOMContentLoaded', function() {
    const unidadActual = '{{ old('unidad', $servidor->unidad) }}';
    const subActual    = '{{ old('sub_unidad', $servidor->sub_unidad) }}';
    if (unidadActual) {
        const sel = document.getElementById('sub_unidad_edit');
        sel.innerHTML = '<option value="">Seleccionar Sub-Unidad</option>';
        (datosUnidades[unidadActual] || []).forEach(function(sub) {
            const opt = document.createElement('option');
            opt.value = sub; opt.text = sub;
            if (sub === subActual) opt.selected = true;
            sel.appendChild(opt);
        });
    }
});
</script>
@endsection
