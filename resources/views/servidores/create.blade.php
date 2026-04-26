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
        <div class="col-md-6">
            <h6 class="text-muted fw-bold mb-3 text-center">REGISTRO</h6>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <strong>❌ Error:</strong> {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning">
                    <strong>⚠️ Atención:</strong> {{ session('warning') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">
                    <strong>ℹ️ Información:</strong> {{ session('info') }}
                </div>
            @endif

            {{-- SECCIÓN DE DUPLICADOS - VERSIÓN SIMPLE --}}
            {{-- ===== ÍTEM ===== --}}
            <div id="form-item" style="display:none;">
                <form action="{{ route('servidores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tipo" value="item">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Datos del Ítem</h6>

                            {{-- SECCIÓN DE DUPLICADOS - VERSIÓN SIMPLE --}}
                            @if(session('duplicados') && session('duplicados')->count() > 0)
                                <div class="alert alert-warning mb-3">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        ¡Esta persona ya tiene un cargo registrado!
                                    </h6>
                                    
                                    <div class="mb-3">
                                        <strong>Cargo actual:</strong> 
                                        {{ session('duplicados')->first()->cargo_descripcion }}
                                        <br>
                                        <small class="text-muted">
                                            {{ session('duplicados')->first()->sub_unidad ?? session('duplicados')->first()->unidad }}
                                        </small>
                                    </div>

                                    <div class="alert alert-light">
                                        <h6 class="mb-2">¿Qué quieres hacer?</h6>
                                        
                                        <div class="mb-2">
                                            <label class="form-check">
                                                <input class="form-check-input" type="radio" 
                                                       name="accion_duplicado" 
                                                       value="reemplazar" 
                                                       required>
                                                <span class="form-check-label">
                                                    <strong>🔄 Cambiar de cargo</strong>
                                                    <br>
                                                    <small>Dejar el cargo actual vacante y asignar el nuevo</small>
                                                </span>
                                            </label>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-check">
                                                <input class="form-check-input" type="radio" 
                                                       name="accion_duplicado" 
                                                       value="adicionar">
                                                <span class="form-check-label">
                                                    <strong>➕ Agregar segundo cargo</strong>
                                                    <br>
                                                    <small class="text-warning">La persona tendrá dos cargos (se contará como vacante)</small>
                                                </span>
                                            </label>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-check">
                                                <input class="form-check-input" type="radio" 
                                                       name="accion_duplicado" 
                                                       value="nuevo">
                                                <span class="form-check-label">
                                                    <strong>👤 Es otra persona</strong>
                                                    <br>
                                                    <small>Son nombres iguales pero personas diferentes</small>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row g-2 mb-2">
                                <div class="col-3">
                                    <label class="form-label small mb-1">N° Ítem</label>
                                    <input type="text" name="numero_item" class="form-control form-control-sm" value="{{ old('numero_item') }}">
                                </div>
                                <div class="col-5">
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

                            {{-- Designación --}}
                            <div class="border rounded p-2 mb-2 bg-light">
                                <label class="form-label small fw-bold mb-1">Designación:</label>
                                <div class="row g-2 align-items-start">
                                    <div class="col-5 d-flex flex-column gap-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="designacion_tipos[]" value="Designación" id="chk_des_item"
                                                {{ is_array(old('designacion_tipos')) && in_array('Designación', old('designacion_tipos')) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="chk_des_item">Designación</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="designacion_tipos[]" value="Interinato" id="chk_int_item"
                                                {{ is_array(old('designacion_tipos')) && in_array('Interinato', old('designacion_tipos')) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="chk_int_item">Interinato</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="designacion_tipos[]" value="Comisión" id="chk_com_item"
                                                {{ is_array(old('designacion_tipos')) && in_array('Comisión', old('designacion_tipos')) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="chk_com_item">Comisión</label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <input type="date" name="designacion_inicio" class="form-control form-control-sm" title="Fecha inicio" value="{{ old('designacion_inicio') }}">
                                    </div>
                                    <div class="col-3">
                                        <input type="date" name="designacion_fin" class="form-control form-control-sm" title="Fecha fin" value="{{ old('designacion_fin') }}">
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
                            ] as $campo)
                            <div class="row g-2 align-items-center mb-2">
                                <div class="col-1 text-center"><input type="checkbox" class="form-check-input"></div>
                                <div class="col-6">
                                    <label class="form-label small mb-0">{{ $campo['label'] }}</label>
                                    <input type="text" name="{{ $campo['desc'] }}" class="form-control form-control-sm" placeholder="Ingresar descripción..." value="{{ old($campo['desc']) }}">
                                </div>
                                <div class="col-2"><label class="form-label small mb-0">Grado:</label></div>
                                <div class="col-3">
                                    <select name="{{ $campo['grado'] }}" class="form-select form-select-sm">
                                        @foreach(['G','MG','M','L'] as $g)
                                            <option value="{{ $g }}" {{ old($campo['grado'])==$g?'selected':'' }}>{{ $g }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endforeach

                            {{-- 3. Discapacidad con detalle --}}
                            <div class="mb-2">
                                <div class="row g-2 align-items-center">
                                    <div class="col-1 text-center">
                                        <input type="checkbox" class="form-check-input" id="chk_disc_item" onchange="document.getElementById('disc_detalle_item').style.display=this.checked?'block':'none'">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-0">3. Discapacidad Ley N° 223:</label>
                                        <input type="text" name="discapacidad_desc" class="form-control form-control-sm" placeholder="Ingresar descripción..." value="{{ old('discapacidad_desc') }}">
                                    </div>
                                    <div class="col-2"><label class="form-label small mb-0">Grado:</label></div>
                                    <div class="col-3">
                                        <select name="discapacidad_grado" class="form-select form-select-sm">
                                            @foreach(['G','MG','M','L'] as $g)
                                                <option value="{{ $g }}" {{ old('discapacidad_grado')==$g?'selected':'' }}>{{ $g }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="disc_detalle_item" class="mt-2 ms-4 ps-2 border-start border-2 border-primary" style="display:{{ old('discapacidad_tipo') || old('discapacidad_carnet') ? 'block' : 'none' }};">
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <label class="form-label small mb-1">Tipo de discapacidad</label>
                                            <input type="text" name="discapacidad_tipo" class="form-control form-control-sm" placeholder="Ej: Visual, Motriz..." value="{{ old('discapacidad_tipo') }}">
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label small mb-1">Carnet de discapacidad</label>
                                            <input type="text" name="discapacidad_carnet" class="form-control form-control-sm" placeholder="N° carnet" value="{{ old('discapacidad_carnet') }}">
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label small mb-1">Vence</label>
                                            <input type="date" name="discapacidad_vence" class="form-control form-control-sm" value="{{ old('discapacidad_vence') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

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

                            {{-- 1. Contrato --}}
                            <div class="mb-2">
                                <label class="form-label small mb-1">Contrato</label>
                                <input type="text" name="contrato_numero" class="form-control form-control-sm" value="{{ old('contrato_numero') }}">
                            </div>

                            {{-- 2. Nombres --}}
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

                            {{-- 3. Cargo --}}
                            <div class="mb-2">
                                <input type="text" name="cargo_consultoria" class="form-control form-control-sm" placeholder="Ingresar descripción del cargo..." value="{{ old('cargo_consultoria') }}">
                            </div>

                            {{-- 4. Designación --}}
                            <div class="border rounded p-2 mb-2 bg-light">
                                <label class="form-label small fw-bold mb-1">Designación:</label>
                                <div class="row g-2 align-items-start">
                                    <div class="col-5 d-flex flex-column gap-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="designacion_tipos[]" value="Designación" id="chk_des_cons"
                                                {{ is_array(old('designacion_tipos')) && in_array('Designación', old('designacion_tipos')) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="chk_des_cons">Designación</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="designacion_tipos[]" value="Interinato" id="chk_int_cons"
                                                {{ is_array(old('designacion_tipos')) && in_array('Interinato', old('designacion_tipos')) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="chk_int_cons">Interinato</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="designacion_tipos[]" value="Comisión" id="chk_com_cons"
                                                {{ is_array(old('designacion_tipos')) && in_array('Comisión', old('designacion_tipos')) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="chk_com_cons">Comisión</label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <input type="date" name="designacion_inicio" class="form-control form-control-sm" title="Fecha inicio" value="{{ old('designacion_inicio') }}">
                                    </div>
                                    <div class="col-3">
                                        <input type="date" name="designacion_fin" class="form-control form-control-sm" title="Fecha fin" value="{{ old('designacion_fin') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- 5. Unidad / Sub-Unidad --}}
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
                            ] as $campo)
                            <div class="row g-2 align-items-center mb-2">
                                <div class="col-1 text-center"><input type="checkbox" class="form-check-input"></div>
                                <div class="col-6">
                                    <label class="form-label small mb-0">{{ $campo['label'] }}</label>
                                    <input type="text" name="{{ $campo['desc'] }}" class="form-control form-control-sm" placeholder="Ingresar descripción..." value="{{ old($campo['desc']) }}">
                                </div>
                                <div class="col-2"><label class="form-label small mb-0">Grado:</label></div>
                                <div class="col-3">
                                    <select name="{{ $campo['grado'] }}" class="form-select form-select-sm">
                                        @foreach(['G','MG','M','L'] as $g)
                                            <option value="{{ $g }}" {{ old($campo['grado'])==$g?'selected':'' }}>{{ $g }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endforeach

                            {{-- 3. Discapacidad con detalle --}}
                            <div class="mb-2">
                                <div class="row g-2 align-items-center">
                                    <div class="col-1 text-center">
                                        <input type="checkbox" class="form-check-input" id="chk_disc_cons" onchange="document.getElementById('disc_detalle_cons').style.display=this.checked?'block':'none'">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small mb-0">3. Discapacidad Ley N° 223:</label>
                                        <input type="text" name="discapacidad_desc" class="form-control form-control-sm" placeholder="Ingresar descripción..." value="{{ old('discapacidad_desc') }}">
                                    </div>
                                    <div class="col-2"><label class="form-label small mb-0">Grado:</label></div>
                                    <div class="col-3">
                                        <select name="discapacidad_grado" class="form-select form-select-sm">
                                            @foreach(['G','MG','M','L'] as $g)
                                                <option value="{{ $g }}" {{ old('discapacidad_grado')==$g?'selected':'' }}>{{ $g }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="disc_detalle_cons" class="mt-2 ms-4 ps-2 border-start border-2 border-primary" style="display:{{ old('discapacidad_tipo') || old('discapacidad_carnet') ? 'block' : 'none' }};">
                                    <div class="row g-2">
                                        <div class="col-4">
                                            <label class="form-label small mb-1">Tipo de discapacidad</label>
                                            <input type="text" name="discapacidad_tipo" class="form-control form-control-sm" placeholder="Ej: Visual, Motriz..." value="{{ old('discapacidad_tipo') }}">
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label small mb-1">Carnet de discapacidad</label>
                                            <input type="text" name="discapacidad_carnet" class="form-control form-control-sm" placeholder="N° carnet" value="{{ old('discapacidad_carnet') }}">
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label small mb-1">Vence</label>
                                            <input type="date" name="discapacidad_vence" class="form-control form-control-sm" value="{{ old('discapacidad_vence') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

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

        </div>{{-- fin col-md-6 --}}
    </div>{{-- fin row --}}
</div>{{-- fin container --}}

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

@if(old('tipo') === 'item')
    mostrarFormulario('item');
    document.getElementById('selectorTipo').value = 'item';
@elseif(old('tipo') === 'consultoria')
    mostrarFormulario('consultoria');
    document.getElementById('selectorTipo').value = 'consultoria';
@endif

// Si hay duplicados, mostrar el form de ítem automáticamente
@if(session('duplicados'))
    mostrarFormulario('item');
    document.getElementById('selectorTipo').value = 'item';
@endif

function llenarSubUnidades(unidad, selectEl) {
    selectEl.innerHTML = '<option value="">Seleccionar Sub-Unidad</option>';
    const datos = {
        "GERENCIA REGIONAL LA PAZ - GRLPZ": ["GERENTE","ASESORÍA","SECRETARIA","SISTEMAS","USO","ARCHIVO"],
        "Unidad Administrativa": ["Contabilidad","Activos Fijos","Talento Humano","Contrataciones","Servicios Generales","Responsable Administrativo Financiero","Auxiliar Unidad Administrativa"],
        "Unidad Fiscalización": ["Fiscalizaciones posteriores / Controles diferidos","Jefes Unidad Fiscalización","Supervisores Fiscalización","Auxiliar Fiscalización"],
        "Unidad Jurídica": ["Cobranza coactiva","Técnica jurídica","Procesos administrativos","Servicios Generales","Responsable Administrativo Jurídica","Auxiliar Unidad Jurídica"],
        "Administración Aduana Interior La Paz": ["SPCC (Comisos)","Disposición de mercancías","Despachos","Gestión","Secretaria Aduana Interior La Paz","Administrador Aduana Interior La Paz"],
        "Aduana Frontera Guayaramerín": ["Secretaria Guayaramerin","Administrador Guayamerin","Gestion Aduanera / Operativa Guayamerin"],
        "Aduana Aeropuerto El Alto": ["Secretaria Aeropuerto El Alto","Administrador Aeropuerto El Alto","Supervisor Aeropuerto El Alto","Despachos Aeropuerto El Alto","Tecnico gestion Aeropuerto El Alto","SPCC Aeropuerto El Alto"],
        // "Administración Aduana Zona Franca": ["Operaciones","Control","Administración"],
        "Administración Aduana Zona Franca Industrial Patacamaya": ["Secretaria Patacamaya","Administrador Patacamaya","Gestion Aduanera / Operativa Patacamaya"],
        "Administración Aduana Frontera Desaguadero": ["Secretaria Frontera Desaguadero","Administrador Frontera Desaguadero","Gestion Aduanera / Operativa Desaguadero"],
        "Zona Franca Comercial / Frontera Cobija": ["Secretaria Frontera Cobija","Administrador Frontera Cobija","Gestion Aduanera / Operativa Cobija","Zofra Cobija","Aeropuerto Cobija"],
        "Agencia Aduana Exterior Matarani": ["Secretaria Exterior Matarani","Administrador Exterior Matarani","Gestion Aduanera / Operativa Matarani"],
        "Administración Aduana Frontera Charaña": ["Secretaria Frontera Charaña","Administrador Frontera Charaña","Despachos / Minimas cuantrillas"],
    };
    if (datos[unidad]) {
        datos[unidad].forEach(function(sub) {
            let option = document.createElement("option");
            option.value = sub;
            option.text = sub;
            selectEl.appendChild(option);
        });
    }
}

function cargarSubUnidades() {
    llenarSubUnidades(document.getElementById("unidad").value, document.getElementById("sub_unidad"));
}

function cargarSubUnidadesCons() {
    llenarSubUnidades(document.getElementById("unidad_cons").value, document.getElementById("sub_unidad_cons"));
}
</script>
@endsection
