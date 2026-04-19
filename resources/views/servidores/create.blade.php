@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Encabezado de secciones --}}
    <div class="row text-center mb-4">
        <div class="col-4 border-end">
            <h6 class="text-muted fw-bold">AGREGAR SERVIDORES PÚBLICOS</h6>
        </div>
        <div class="col-4 border-end">
            <h6 class="text-muted fw-bold">REGISTRO</h6>
        </div>
        <div class="col-4">
            <h6 class="text-muted fw-bold">VISTA USUARIO FINAL</h6>
        </div>
    </div>

    <div class="row align-items-start justify-content-center">

        {{-- Columna izquierda: selector --}}
        <div class="col-md-2 d-flex align-items-start pt-3">
            <div class="w-100">
                <select id="selectorTipo" class="form-select shadow-sm" onchange="mostrarFormulario(this.value)">
                    <option value="">Selecciona una opción</option>
                    <option value="item">Ítem</option>
                    <option value="consultoria">Consultoría</option>
                </select>
            </div>
        </div>

        {{-- Columna central: formularios --}}
        <div class="col-md-6">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORMULARIO ITEM --}}
            <div id="form-item" style="display:none;">
                <form action="{{ route('servidores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tipo" value="item">

                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Datos del Ítem</h6>

                            <div class="row g-2 mb-2">
                                <div class="col-3">
                                    <label class="form-label small mb-1">N° Ítem</label>
                                    <input type="text" name="numero_item" class="form-control form-control-sm" value="{{ old('numero_item') }}">
                                </div>
                                <div class="col-5">
                                    <label class="form-label small mb-1">CITE Memorandum</label>
                                    <input type="text" name="cite_memorandum" class="form-control form-control-sm" value="{{ old('cite_memorandum') }}">
                                </div>
                                <div class="col-4">
                                    <label class="form-label small mb-1">Cargo</label>
                                    <select name="designacion" class="form-select form-select-sm">
                                        <option value="">Designación</option>
                                        <option value="Designación" {{ old('designacion')=='Designación'?'selected':'' }}>Designación</option>
                                        <option value="Interinato" {{ old('designacion')=='Interinato'?'selected':'' }}>Interinato</option>
                                        <option value="Comisión" {{ old('designacion')=='Comisión'?'selected':'' }}>Comisión</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-4">
                                    <input type="text" name="nombre" class="form-control form-control-sm" placeholder="Nombres" value="{{ old('nombre') }}">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="apellido_paterno" class="form-control form-control-sm" placeholder="Apellido Paterno" value="{{ old('apellido_paterno') }}">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="apellido_materno" class="form-control form-control-sm" placeholder="Apellido Materno" value="{{ old('apellido_materno') }}">
                                </div>
                            </div>

                            <div class="mb-2">
                                <input type="text" name="cargo" class="form-control form-control-sm" placeholder="Ingresar nombre del cargo..." value="{{ old('cargo') }}">
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small mb-1">Fecha de ingreso a la Aduana</label>
                                    <input type="date" name="fecha_ingreso_aduana" class="form-control form-control-sm" value="{{ old('fecha_ingreso_aduana') }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Fecha de inicio cargo</label>
                                    <input type="date" name="fecha_inicio_cargo" class="form-control form-control-sm" value="{{ old('fecha_inicio_cargo') }}">
                                </div>
                            </div>

                            <p class="fw-bold small mb-2">Inamovilidad:</p>

                            @foreach([
                                ['label'=>'1. Asignación Familiar:', 'desc'=>'asignacion_familiar_desc', 'grado'=>'asignacion_familiar_grado'],
                                ['label'=>'2. Casos especiales:', 'desc'=>'casos_especiales_desc', 'grado'=>'casos_especiales_grado'],
                                ['label'=>'3. Discapacidad Ley N° 223:', 'desc'=>'discapacidad_desc', 'grado'=>'discapacidad_grado'],
                            ] as $campo)
                            <div class="row g-2 align-items-center mb-2">
                                <div class="col-1 text-center">
                                    <input type="checkbox" class="form-check-input" name="{{ $campo['desc'] }}_check">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-0">{{ $campo['label'] }}</label>
                                    <input type="text" name="{{ $campo['desc'] }}" class="form-control form-control-sm" placeholder="Ingresar descripción..." value="{{ old($campo['desc']) }}">
                                </div>
                                <div class="col-2">
                                    <label class="form-label small mb-0">Grado:</label>
                                </div>
                                <div class="col-3">
                                    <select name="{{ $campo['grado'] }}" class="form-select form-select-sm">
                                        <option value="G" {{ old($campo['grado'])=='G'?'selected':'' }}>G</option>
                                        <option value="MG" {{ old($campo['grado'])=='MG'?'selected':'' }}>MG</option>
                                        <option value="M" {{ old($campo['grado'])=='M'?'selected':'' }}>M</option>
                                        <option value="L" {{ old($campo['grado'])=='L'?'selected':'' }}>L</option>
                                    </select>
                                </div>
                            </div>
                            @endforeach

                            <div class="mt-3">
                                <label class="form-label small mb-1">📷 Fotografía</label>
                                <div class="d-flex align-items-center gap-2">
                                    <label class="btn btn-sm btn-outline-secondary mb-0">
                                        Subir Imagen <input type="file" name="fotografia" accept="image/*" class="d-none" onchange="previewImg(event,'preview-item')">
                                    </label>
                                    <img id="preview-item" src="" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;display:none;">
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-primary btn-sm px-4">Guardar</button>
                                <a href="{{ route('servidores.index') }}" class="btn btn-secondary btn-sm px-4">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- FORMULARIO CONSULTORÍA --}}
            <div id="form-consultoria" style="display:none;">
                <form action="{{ route('servidores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tipo" value="consultoria">

                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Datos de Consultoría</h6>

                            <div class="row g-2 mb-2">
                                <div class="col-7">
                                    <label class="form-label small mb-1">Contrato</label>
                                    <input type="text" name="contrato_numero" class="form-control form-control-sm" value="{{ old('contrato_numero') }}">
                                </div>
                                <div class="col-5">
                                    <label class="form-label small mb-1">Cargo</label>
                                    <select name="designacion" class="form-select form-select-sm">
                                        <option value="">Designación</option>
                                        <option value="Designación" {{ old('designacion')=='Designación'?'selected':'' }}>Designación</option>
                                        <option value="Interinato" {{ old('designacion')=='Interinato'?'selected':'' }}>Interinato</option>
                                        <option value="Comisión" {{ old('designacion')=='Comisión'?'selected':'' }}>Comisión</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-4">
                                    <input type="text" name="nombre" class="form-control form-control-sm" placeholder="Nombres" value="{{ old('nombre') }}">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="apellido_paterno" class="form-control form-control-sm" placeholder="Apellido Paterno" value="{{ old('apellido_paterno') }}">
                                </div>
                                <div class="col-4">
                                    <input type="text" name="apellido_materno" class="form-control form-control-sm" placeholder="Apellido Materno" value="{{ old('apellido_materno') }}">
                                </div>
                            </div>

                            <div class="mb-2">
                                <input type="text" name="cargo_consultoria" class="form-control form-control-sm" placeholder="Ingresar descripción del cargo..." value="{{ old('cargo_consultoria') }}">
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small mb-1">Fecha de ingreso a la Aduana</label>
                                    <input type="date" name="fecha_ingreso_aduana" class="form-control form-control-sm" value="{{ old('fecha_ingreso_aduana') }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Fecha de inicio contrato</label>
                                    <input type="date" name="fecha_inicio_contrato" class="form-control form-control-sm" value="{{ old('fecha_inicio_contrato') }}">
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small mb-1">Fecha de fin contrato</label>
                                    <input type="date" name="fecha_fin_contrato" class="form-control form-control-sm" value="{{ old('fecha_fin_contrato') }}">
                                </div>
                            </div>

                            <p class="fw-bold small mb-2">Inamovilidad:</p>

                            @foreach([
                                ['label'=>'1. Asignación Familiar:', 'desc'=>'asignacion_familiar_desc', 'grado'=>'asignacion_familiar_grado'],
                                ['label'=>'2. Casos especiales:', 'desc'=>'casos_especiales_desc', 'grado'=>'casos_especiales_grado'],
                                ['label'=>'3. Discapacidad Ley N° 223:', 'desc'=>'discapacidad_desc', 'grado'=>'discapacidad_grado'],
                            ] as $campo)
                            <div class="row g-2 align-items-center mb-2">
                                <div class="col-1 text-center">
                                    <input type="checkbox" class="form-check-input" name="{{ $campo['desc'] }}_check">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-0">{{ $campo['label'] }}</label>
                                    <input type="text" name="{{ $campo['desc'] }}" class="form-control form-control-sm" placeholder="Ingresar descripción..." value="{{ old($campo['desc']) }}">
                                </div>
                                <div class="col-2">
                                    <label class="form-label small mb-0">Grado:</label>
                                </div>
                                <div class="col-3">
                                    <select name="{{ $campo['grado'] }}" class="form-select form-select-sm">
                                        <option value="G" {{ old($campo['grado'])=='G'?'selected':'' }}>G</option>
                                        <option value="MG" {{ old($campo['grado'])=='MG'?'selected':'' }}>MG</option>
                                        <option value="M" {{ old($campo['grado'])=='M'?'selected':'' }}>M</option>
                                        <option value="L" {{ old($campo['grado'])=='L'?'selected':'' }}>L</option>
                                    </select>
                                </div>
                            </div>
                            @endforeach

                            <div class="mt-3">
                                <label class="form-label small mb-1">📷 Fotografía</label>
                                <div class="d-flex align-items-center gap-2">
                                    <label class="btn btn-sm btn-outline-secondary mb-0">
                                        Subir Imagen <input type="file" name="fotografia" accept="image/*" class="d-none" onchange="previewImg(event,'preview-cons')">
                                    </label>
                                    <img id="preview-cons" src="" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;display:none;">
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-primary btn-sm px-4">Guardar</button>
                                <a href="{{ route('servidores.index') }}" class="btn btn-secondary btn-sm px-4">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>{{-- fin col central --}}

    </div>{{-- fin row --}}
</div>

<script>
function mostrarFormulario(valor) {
    document.getElementById('form-item').style.display = 'none';
    document.getElementById('form-consultoria').style.display = 'none';
    if (valor === 'item') document.getElementById('form-item').style.display = 'block';
    if (valor === 'consultoria') document.getElementById('form-consultoria').style.display = 'block';
}

function previewImg(event, previewId) {
    const img = document.getElementById(previewId);
    const file = event.target.files[0];
    if (file) {
        img.src = URL.createObjectURL(file);
        img.style.display = 'inline-block';
    }
}

// Si hay errores de validación, mostrar el formulario correcto
@if(old('tipo') === 'item')
    mostrarFormulario('item');
    document.getElementById('selectorTipo').value = 'item';
@elseif(old('tipo') === 'consultoria')
    mostrarFormulario('consultoria');
    document.getElementById('selectorTipo').value = 'consultoria';
@endif
</script>
@endsection
