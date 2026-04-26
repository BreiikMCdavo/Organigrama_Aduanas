@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row align-items-start justify-content-center">

        {{-- Selector izquierda --}}
        <div class="col-md-2 text-center">
            <h6 class="text-muted fw-bold mb-3">AGREGAR SERVIDORES PÚBLICOS</h6>
            <select id="selectorTipo" class="form-select shadow-sm" onchange="mostrarFormulario(this.value)">
                <option value="">Selecciona una opción</option>
                <option value="item">Ítem</option>
                <option value="consultoria">Consultoría</option>
            </select>
        </div>

        {{-- Formularios --}}
        <div class="col-md-7">
            <h6 class="text-muted fw-bold mb-3 text-center">REGISTRO</h6>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            {{-- ===== ÍTEM ===== --}}
            <div id="form-item" style="display:none;">
                <form action="{{ route('servidores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tipo" value="item">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Datos del Ítem</h6>

                            <div class="row g-2 mb-2">
                                <div class="col-4">
                                    <label class="form-label small mb-1">N° Ítem</label>
                                    <input type="text" name="numero_item" class="form-control form-control-sm" value="{{ old('numero_item') }}">
                                </div>
                                <div class="col-8">
                                    <label class="form-label small mb-1">CITE Memorandum</label>
                                    <input type="text" name="cite_memorandum" class="form-control form-control-sm" value="{{ old('cite_memorandum') }}">
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

                            {{-- Designación con checkboxes y fechas --}}
                            <p class="fw-bold small mb-2 mt-3">Designación:</p>
                            <div class="border rounded p-3 mb-3 bg-light">

                                {{-- Designación --}}
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="designacion_tipos[]" value="Designación" id="chk_des_item"
                                                {{ is_array(old('designacion_tipos')) && in_array('Designación', old('designacion_tipos')) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="chk_des_item">Designación</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <input type="date" name="designacion_inicio" class="form-control form-control-sm" placeholder="Inicio" value="{{ old('designacion_inicio') }}">
                                    </div>
                                    <div class="col-4">
                                        <input type="date" name="designacion_fin" class="form-control form-control-sm" placeholder="Fin" value="{{ old('designacion_fin') }}">
                                    </div>
                                </div>

                                {{-- Interinato --}}
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="designacion_tipos[]" value="Interinato" id="chk_int_item"
                                                {{ is_array(old('designacion_tipos')) && in_array('Interinato', old('designacion_tipos')) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="chk_int_item">Interinato</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <input type="date" name="interinato_inicio" class="form-control form-control-sm" value="{{ old('interinato_inicio') }}">
                                    </div>
                                    <div class="col-4">
                                        <input type="date" name="interinato_fin" class="form-control form-control-sm" value="{{ old('interinato_fin') }}">
                                    </div>
                                </div>

                                {{-- Comisión --}}
                                <div class="row g-2 align-items-center">
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="designacion_tipos[]" value="Comisión" id="chk_com_item"
                                                {{ is_array(old('designacion_tipos')) && in_array('Comisión', old('designacion_tipos')) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="chk_com_item">Comisión</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <input type="date" name="comision_inicio" class="form-control form-control-sm" value="{{ old('comision_inicio') }}">
                                    </div>
                                    <div class="col-4">
                                        <input type="date" name="comision_fin" class="form-control form-control-sm" value="{{ old('comision_fin') }}">
                                    </div>
                                </div>

                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small mb-1">Unidad</label>
                                    <select name="unidad" id="unidad" class="form-select form-select-sm" onchange="cargarSubUnidades()">
                                        <option value="">Seleccionar Unidad</option>
                                        <option value="GERENCIA REGIONAL LA PAZ - GRLPZ">GERENCIA REGIONAL LA PAZ - GRLPZ</option>
                                        <option value="Unidad Administrativa">Unidad Administrativa</option>
                                        <option value="Unidad Fiscalización">Unidad Fiscalización</option>
                                        <option value="Unidad Jurídica">Unidad Jurídica</option>
                                        <option value="Administración Aduana Interior La Paz">Administración Aduana Interior La Paz</option>
                                        <option value="Aduana Frontera Guayaramerín">Aduana Frontera Guayaramerín</option>
                                        <option value="Aduana Aeropuerto El Alto">Aduana Aeropuerto El Alto</option>
                                        <option value="Administración Aduana Zona Franca Industrial Patacamaya">Administración Aduana Zona Franca Industrial Patacamaya</option>
                                        <option value="Administración Aduana Frontera Desaguadero">Administración Aduana Frontera Desaguadero</option>
                                        <option value="Zona Franca Comercial / Frontera Cobija">Zona Franca Comercial / Frontera Cobija</option>
                                        <option value="Agencia Aduana Exterior Matarani">Agencia Aduana Exterior Matarani</option>
                                        <option value="Administración Aduana Frontera Charaña">Administración Aduana Frontera Charaña</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Sub-Unidad</label>
                                    <select name="sub_unidad" id="sub_unidad" class="form-select form-select-sm">
                                        <option value="">Seleccionar Sub-Unidad</option>
                                    </select>
                                </div>
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
                                ['label'=>'1. Asignación Familiar:','desc'=>'asignacion_familiar_desc','grado'=>'asignacion_familiar_grado'],
                                ['label'=>'2. Casos especiales:','desc'=>'casos_especiales_desc','grado'=>'casos_especiales_grado'],
                                ['label'=>'3. Discapacidad Ley N° 223:','desc'=>'discapacidad_desc','grado'=>'discapacidad_grado'],
                            ] as $campo)
                            <div class="row g-2 align-items-center mb-2">
                                <div class="col-1 text-center">
                                    <input type="checkbox" class="form-check-input">
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

            {{-- ===== CONSULTORÍA ===== --}}
            <div id="form-consultoria" style="display:none;">
                <form action="{{ route('servidores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tipo" value="consultoria">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Datos de Consultoría</h6>

                            <div class="row g-2 mb-2">
                                <div class="col-12">
                                    <label class="form-label small mb-1">Contrato</label>
                                    <input type="text" name="contrato_numero" class="form-control form-control-sm" value="{{ old('contrato_numero') }}">
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

                            {{-- Designación con checkboxes y fechas --}}
                            <p class="fw-bold small mb-2 mt-3">Designación:</p>
                            <div class="border rounded p-3 mb-3 bg-light">

                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="designacion_tipos[]" value="Designación" id="chk_des_cons"
                                                {{ is_array(old('designacion_tipos')) && in_array('Designación', old('designacion_tipos')) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="chk_des_cons">Designación</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <input type="date" name="designacion_inicio" class="form-control form-control-sm" value="{{ old('designacion_inicio') }}">
                                    </div>
                                    <div class="col-4">
                                        <input type="date" name="designacion_fin" class="form-control form-control-sm" value="{{ old('designacion_fin') }}">
                                    </div>
                                </div>

                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="designacion_tipos[]" value="Interinato" id="chk_int_cons"
                                                {{ is_array(old('designacion_tipos')) && in_array('Interinato', old('designacion_tipos')) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="chk_int_cons">Interinato</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <input type="date" name="interinato_inicio" class="form-control form-control-sm" value="{{ old('interinato_inicio') }}">
                                    </div>
                                    <div class="col-4">
                                        <input type="date" name="interinato_fin" class="form-control form-control-sm" value="{{ old('interinato_fin') }}">
                                    </div>
                                </div>

                                <div class="row g-2 align-items-center">
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="designacion_tipos[]" value="Comisión" id="chk_com_cons"
                                                {{ is_array(old('designacion_tipos')) && in_array('Comisión', old('designacion_tipos')) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="chk_com_cons">Comisión</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <input type="date" name="comision_inicio" class="form-control form-control-sm" value="{{ old('comision_inicio') }}">
                                    </div>
                                    <div class="col-4">
                                        <input type="date" name="comision_fin" class="form-control form-control-sm" value="{{ old('comision_fin') }}">
                                    </div>
                                </div>

                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small mb-1">Unidad</label>
                                    <select name="unidad" id="unidad_cons" class="form-select form-select-sm" onchange="cargarSubUnidadesCons()">
                                        <option value="">Seleccionar Unidad</option>
                                        <option value="GERENCIA REGIONAL LA PAZ - GRLPZ">GERENCIA REGIONAL LA PAZ - GRLPZ</option>
                                        <option value="Unidad Administrativa">Unidad Administrativa</option>
                                        <option value="Unidad Fiscalización">Unidad Fiscalización</option>
                                        <option value="Unidad Jurídica">Unidad Jurídica</option>
                                        <option value="Administración Aduana Interior La Paz">Administración Aduana Interior La Paz</option>
                                        <option value="Aduana Frontera Guayaramerín">Aduana Frontera Guayaramerín</option>
                                        <option value="Aduana Aeropuerto El Alto">Aduana Aeropuerto El Alto</option>
                                        <option value="Administración Aduana Zona Franca Industrial Patacamaya">Administración Aduana Zona Franca Industrial Patacamaya</option>
                                        <option value="Administración Aduana Frontera Desaguadero">Administración Aduana Frontera Desaguadero</option>
                                        <option value="Zona Franca Comercial / Frontera Cobija">Zona Franca Comercial / Frontera Cobija</option>
                                        <option value="Agencia Aduana Exterior Matarani">Agencia Aduana Exterior Matarani</option>
                                        <option value="Administración Aduana Frontera Charaña">Administración Aduana Frontera Charaña</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small mb-1">Sub-Unidad</label>
                                    <select name="sub_unidad" id="sub_unidad_cons" class="form-select form-select-sm">
                                        <option value="">Seleccionar Sub-Unidad</option>
                                    </select>
                                </div>
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
                                ['label'=>'1. Asignación Familiar:','desc'=>'asignacion_familiar_desc','grado'=>'asignacion_familiar_grado'],
                                ['label'=>'2. Casos especiales:','desc'=>'casos_especiales_desc','grado'=>'casos_especiales_grado'],
                                ['label'=>'3. Discapacidad Ley N° 223:','desc'=>'discapacidad_desc','grado'=>'discapacidad_grado'],
                            ] as $campo)
                            <div class="row g-2 align-items-center mb-2">
                                <div class="col-1 text-center">
                                    <input type="checkbox" class="form-check-input">
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

        </div>{{-- fin col-md-7 --}}
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
    if (file) { img.src = URL.createObjectURL(file); img.style.display = 'inline-block'; }
}

@if(old('tipo') === 'item')
    mostrarFormulario('item');
    document.getElementById('selectorTipo').value = 'item';
@elseif(old('tipo') === 'consultoria')
    mostrarFormulario('consultoria');
    document.getElementById('selectorTipo').value = 'consultoria';
@endif

const subs = {
    "GERENCIA REGIONAL LA PAZ - GRLPZ":["GERENTE","ASESORÍA","SECRETARIA","SISTEMAS","USO","ARCHIVO"],
    "Unidad Administrativa":["Responsable Administrativo Financiero","Auxiliar Unidad Administrativa","Activos Fijos","Contabilidad","Talento Humano","Contrataciones","Servicios Generales"],
    "Unidad Fiscalización":["Jefes Unidad Fiscalización","Supervisores Fiscalización","Auxiliar Fiscalización","Fiscalizaciones posteriores / Controles diferidos"],
    "Unidad Jurídica":["Responsable Administrativo Jurídica","Auxiliar Unidad Jurídica","Cobranza coactiva","Técnica jurídica","Procesos administrativos"],
    "Administración Aduana Interior La Paz":["Secretaria Aduana Interior La Paz","Administrador Aduana Interior La Paz","SPCC (Comisos)","Disposición de mercancías","Despachos","Gestión"],
    "Aduana Frontera Guayaramerín":["Secretaria Guayaramerin","Administrador Guayamerin","Gestion Aduanera / Operativa Guayamerin"],
    "Aduana Aeropuerto El Alto":["Secretaria Aeropuerto El Alto","Administrador Aeropuerto El Alto","Supervisor Aeropuerto El Alto","Despachos Aeropuerto El Alto","Tecnico gestion Aeropuerto El Alto","SPCC Aeropuerto El Alto"],
    "Administración Aduana Zona Franca Industrial Patacamaya":["Secretaria Patacamaya","Administrador Patacamaya","Gestion Aduanera / Operativa Patacamaya"],
    "Administración Aduana Frontera Desaguadero":["Secretaria Frontera Desaguadero","Administrador Frontera Desaguadero","Gestion Aduanera / Operativa Desaguadero"],
    "Zona Franca Comercial / Frontera Cobija":["Secretaria Frontera Cobija","Administrador Frontera Cobija","Gestion Aduanera / Operativa Cobija","Zofra Cobija","Aeropuerto Cobija"],
    "Agencia Aduana Exterior Matarani":["Secretaria Exterior Matarani","Administrador Exterior Matarani","Gestion Aduanera / Operativa Matarani"],
    "Administración Aduana Frontera Charaña":["Secretaria Frontera Charaña","Administrador Frontera Charaña","Despachos / Minimas cuantrillas"]
};
function llenarSubs(unidad, sel, actual) {
    sel.innerHTML = '<option value="">Seleccionar Sub-Unidad</option>';
    (subs[unidad]||[]).forEach(s => { let o=document.createElement('option'); o.value=s; o.text=s; if(s===actual) o.selected=true; sel.appendChild(o); });
}
function llenarSubUnidades(u,s){ llenarSubs(u,s,''); }
function cargarSubUnidades(){ llenarSubs(document.getElementById("unidad").value, document.getElementById("sub_unidad"),''); }
function cargarSubUnidadesCons(){ llenarSubs(document.getElementById("unidad_cons").value, document.getElementById("sub_unidad_cons"),''); }</script>
@endsection
