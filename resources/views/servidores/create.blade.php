@extends('layouts.app')

@php
$subUnidades = [
    "GERENCIA REGIONAL LA PAZ - GRLPZ" => ["GERENTE","ASESORÍA","PLATAFORMA","SISTEMAS","ARCHIVO","SECRETARIA"],
    "Unidad Administrativa" => ["Resposanble Administrativa","Auxiliar Unidad Administrativa","Bienes y Servicios / Activos Fijos","Bienes y Servicios / Servicios Generales","Bienes y Servicios / Contrataciones","Talento Humano / Regimiento Laboral","Talento Humano / Planillal","Finanzas / Contabilidad y Presupuesto","Finanzas / Tesorería y Archivo"],
    "Unidad Fiscalización" => ["Jefe de Fiscalización","Supervisores Fiscalización","Auxiliar Fiscalización","Fiscalizaciones Control Posterior"],
    "Unidad Jurídica" => ["Jefe de Jurídica","Supervisor Jurídica","Procurador Jurídica","Auxiliar Unidad Jurídica","Cobranza Coactiva Jurídica","Técnica Jurídica","Procesos Judiciales y Administrativos Jurídica"],
    "Administración Aduana Interior La Paz" => ["Administrador Aduana Interior La Paz","Supervisor Aduana Interior La Paz","Auxiliar Aduana Interior La Paz","Archivo Aduana Interior La Paz","Administrador Aduana Interior La Paz","SPCC Aduana Interior La Paz","Disposición de mercancías Aduana Interior La Paz","Despachos Aduana Interior La Paz","Gestión Aduana Interior La Paz"],
    "Administración Aduana Frontera Guayaramerín" => ["Administrador Guayaramerín","Despachos Guayaramerín","Gestión Aduanera Guayaramerín"],
    "Administración Aduana Aeropuerto" => ["Administrador Aeropuerto","Supervisor Aeropuerto","Secretario Aeropuerto","Archivo Aeropuerto","Despachos Aeropuerto","Disposición Aeropuerto","Gestión Aduanera Aeropuerto"],
    "Administración Aduana Zona Franca Patacamaya" => ["Administrador Patacamaya","Despachos Patacamaya","Gestión aduanera Patacamaya"],
    "Administración Aduana Frontera Desaguadero" => ["Administrador Frontera Desaguadero","Secretario Frontera Desaguadero","Archivo Frontera Desaguadero","Despachos Frontera Desaguadero","Gestión aduanera Frontera Desaguadero","Disposición Frontera Desaguadero","Plataforma Frontera Desaguadero"],
    "Administración Aduana Frontera Cobija" => ["Administrador Frontera Cobija","Despachos Frontera Cobija","Disposición Frontera Cobija","Gestión aduanera Frontera Cobija"],
    "Administración Agencia Aduana Exterior Matarani" => ["Administrador Exterior Mataraniones","Despachos Exterior Mataraniones","Disposición Exterior Matarani","Gestión aduanera Exterior Matarani"],
    "Administración Aduana Frontera Charaña" => ["Administrador aduanera Frontera Charaña","Gestión Frontera Charaña","Tránsitos Frontera Charaña"],
];
@endphp

@push('styles')
<style>
    .form-card { border: none; border-radius: 16px; box-shadow: 0 8px 30px rgba(0,0,0,0.06); overflow: hidden; }
    .form-card-header { background: linear-gradient(135deg, #1a237e, #0d47a1); padding: 1.25rem 1.5rem; }
    .form-card-body { padding: 2rem 1.75rem; }
    .section-divider { border: 0; height: 1px; background: linear-gradient(90deg, transparent, rgba(26,35,126,0.15), transparent); margin: 1.75rem 0; }
    .form-section { background: #f8f9fc; border-radius: 12px; padding: 1.25rem 1.5rem; border: 1px solid rgba(26,35,126,0.06); margin-bottom: 1.5rem; }
    .section-title { font-size: 0.85rem; font-weight: 700; color: #1a237e; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .section-title i { font-size: 1rem; }
    .form-control, .form-select { border: 1.5px solid #e0e3eb; border-radius: 10px; padding: 0.55rem 0.85rem; font-size: 0.9rem; transition: all 0.2s; background: #fff; }
    .form-control:focus, .form-select:focus { border-color: #1a237e; box-shadow: 0 0 0 3px rgba(26,35,126,0.1); outline: none; }
    .form-control::placeholder { color: #b0b7c8; font-size: 0.85rem; }
    .form-label-custom { font-size: 0.82rem; font-weight: 600; color: #2c3e50; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.3rem; }
    .badge-required { display: inline-block; background: #e74c3c; color: #fff; font-size: 0.6rem; font-weight: 700; padding: 0.05rem 0.4rem; border-radius: 4px; vertical-align: top; }
    .btn-gradient-primary { background: linear-gradient(135deg, #1a237e, #0d47a1); color: #fff; border: none; padding: 0.6rem 1.75rem; border-radius: 10px; font-size: 0.9rem; font-weight: 600; box-shadow: 0 4px 15px rgba(13,71,161,0.25); transition: all 0.25s; }
    .btn-gradient-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(13,71,161,0.35); color: #fff; }
    .btn-gradient-secondary { background: #e9ecef; color: #495057; border: none; padding: 0.6rem 1.75rem; border-radius: 10px; font-size: 0.9rem; font-weight: 600; transition: all 0.25s; }
    .btn-gradient-secondary:hover { background: #dee2e6; transform: translateY(-2px); color: #212529; }
    .upload-btn { display: inline-flex; align-items: center; gap: 0.5rem; background: #f0f2f7; border: 2px dashed #c5cad9; border-radius: 12px; padding: 0.7rem 1.25rem; font-size: 0.85rem; font-weight: 600; color: #495057; cursor: pointer; transition: all 0.2s; }
    .upload-btn:hover { border-color: #1a237e; background: rgba(26,35,126,0.04); color: #1a237e; }
    .custom-checkbox { width: 1.15rem; height: 1.15rem; border-radius: 4px; border: 2px solid #c5cad9; cursor: pointer; transition: all 0.15s; }
    .custom-checkbox:checked { background-color: #1a237e; border-color: #1a237e; }
    .elegant-alert { border: none; border-radius: 12px; padding: 1rem 1.25rem; font-size: 0.9rem; }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row g-4 align-items-start justify-content-center">

        {{-- Selector izquierda --}}
        <div class="col-md-2 text-center">
            <div class="card form-card">
                <div class="form-card-header">
                    <h6 class="mb-0 text-white fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                        <i class="bi bi-person-plus-fill me-1"></i> AGREGAR SERVIDOR
                    </h6>
                </div>
                <div class="p-3">
                    <select id="selectorTipo" class="form-select" onchange="mostrarFormulario(this.value)">
                        <option value="">Selecciona una opción</option>
                        <option value="item">Ítem</option>
                        <option value="consultoria">Consultoría</option>
                    </select>
                    <div class="mt-2 text-start">
                        <small class="text-muted" style="font-size: 0.7rem;">
                            <i class="bi bi-info-circle me-1"></i>Seleccione el tipo de servidor a registrar
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formularios --}}
        <div class="col-md-7">
            <div class="card form-card">
                <div class="form-card-header text-center">
                    <h6 class="mb-0 text-white fw-bold" style="font-size: 1rem; letter-spacing: 1px;">
                        <i class="bi bi-pencil-square me-2"></i>REGISTRO DE SERVIDOR PÚBLICO
                    </h6>
                </div>
                <div class="form-card-body">

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

            {{-- DUPLICADOS: notificación y acciones --}}
            @if(session('duplicados') && session('duplicados')->count() > 0)
                <div class="alert alert-warning elegant-alert">
                    <strong><i class="bi bi-exclamation-triangle"></i> Se encontraron registros duplicados</strong>
                    <p class="mb-2 mt-1" style="font-size:0.85rem;">Los siguientes registros ya existen con el mismo N° de Ítem o nombre. Elige qué acción deseas realizar:</p>
                    <table class="table table-sm table-bordered mb-2" style="font-size:0.8rem;background:#fff;">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cargo</th>
                                <th>Unidad</th>
                                <th>N° Ítem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('duplicados') as $dup)
                                <tr>
                                    <td>{{ $dup->nombre_completo ?? ($dup->nombre.' '.$dup->apellido_paterno) }}</td>
                                    <td>{{ $dup->cargo_descripcion ?? $dup->cargo ?? $dup->cargo_consultoria ?? '(sin cargo)' }}</td>
                                    <td>{{ $dup->unidad ?? '' }}</td>
                                    <td>{{ $dup->numero_item ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="setAccionDuplicado('nuevo')">
                            <i class="bi bi-plus-circle"></i> Nuevo — registrar de todas formas
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="setAccionDuplicado('adicionar')">
                            <i class="bi bi-copy"></i> Adicionar — agregar otro registro
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="setAccionDuplicado('comision')">
                            <i class="bi bi-arrow-left-right"></i> Comisión - mantener 1 ítem activo
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="setAccionDuplicado('reemplazar')">
                            <i class="bi bi-arrow-repeat"></i> Reemplazar — marcar existente como acefalía
                        </button>
                    </div>
                    <div id="reemplazar-select-wrapper" class="mt-2" style="display:none;">
                        <label class="form-label-custom">Selecciona el registro a reemplazar (quedará vacante):</label>
                        <select name="cargo_a_reemplazar" id="cargo_a_reemplazar" class="form-select form-select-sm">
                            <option value="">— Seleccionar —</option>
                            @foreach(session('duplicados') as $dup)
                                <option value="{{ $dup->id }}">
                                    {{ $dup->nombre_completo ?? ($dup->nombre.' '.$dup->apellido_paterno) }} — {{ $dup->cargo_descripcion ?? $dup->cargo ?? $dup->cargo_consultoria ?? '(sin cargo)' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            {{-- ===== ÍTEM ===== --}}
            <div id="form-item" style="display:none;">
                <form action="{{ route('servidores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tipo" value="item">
                    <input type="hidden" name="accion_duplicado" id="accion_duplicado_item" value="">
                    <input type="hidden" name="cargo_a_reemplazar" id="cargo_a_reemplazar_item" value="">

                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-briefcase"></i> Datos de Ítem</div>
                        <div class="row g-3">
                            <div class="col-3">
                                <label class="form-label-custom"><i class="bi bi-hash"></i> N° Ítem</label>
                                <input type="text" name="numero_item" class="form-control" value="{{ old('numero_item') }}">
                            </div>
                            <div class="col-3">
                                <label class="form-label-custom"><i class="bi bi-file-text"></i> CITE Mem.</label>
                                <input type="text" name="cite_memorandum" class="form-control" value="{{ old('cite_memorandum') }}">
                            </div>
                            <div class="col-3">
                                <label class="form-label-custom"><i class="bi bi-person-badge"></i> Cód. Funcionario</label>
                                <input type="text" name="cod_funcionario" class="form-control" value="{{ old('cod_funcionario') }}">
                            </div>
                            <div class="col-3">
                                <label class="form-label-custom"><i class="bi bi-currency-dollar"></i> Escala Salarial</label>
                                <input type="text" name="escala_salarial" class="form-control" value="{{ old('escala_salarial') }}">
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-person-fill"></i> Nombres del Servidor</div>
                        <div class="row g-3">
                            <div class="col-4">
                                <input type="text" name="nombre" class="form-control" placeholder="Nombres" value="{{ old('nombre') }}">
                            </div>
                            <div class="col-4">
                                <input type="text" name="apellido_paterno" class="form-control" placeholder="Apellido Paterno" value="{{ old('apellido_paterno') }}">
                            </div>
                            <div class="col-4">
                                <input type="text" name="apellido_materno" class="form-control" placeholder="Apellido Materno" value="{{ old('apellido_materno') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <input type="text" name="cargo" class="form-control" placeholder="Ingresar nombre del cargo..." value="{{ old('cargo') }}">
                    </div>

                    <hr class="section-divider">

                    {{-- Designación --}}
                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-pen"></i> Designación</div>
                        <div class="row g-3 align-items-start">
                            <div class="col-5 d-flex flex-column gap-2">
                                <div class="form-check">
                                    <input class="form-check-input custom-checkbox" type="checkbox" name="designacion_tipos[]" value="Designación" id="chk_des_item"
                                        {{ is_array(old('designacion_tipos')) && in_array('Designación', old('designacion_tipos')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="chk_des_item" style="font-size: 0.9rem;">Designación</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input custom-checkbox" type="checkbox" name="designacion_tipos[]" value="Interinato" id="chk_int_item"
                                        {{ is_array(old('designacion_tipos')) && in_array('Interinato', old('designacion_tipos')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="chk_int_item" style="font-size: 0.9rem;">Interinato</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input custom-checkbox" type="checkbox" name="designacion_tipos[]" value="Comisión" id="chk_com_item"
                                        {{ is_array(old('designacion_tipos')) && in_array('Comisión', old('designacion_tipos')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="chk_com_item" style="font-size: 0.9rem;">Comisión</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <label class="form-label-custom" style="font-size: 0.75rem;">Fecha inicio</label>
                                <input type="date" name="designacion_inicio" class="form-control" value="{{ old('designacion_inicio') }}">
                            </div>
                            <div class="col-3">
                                <label class="form-label-custom" style="font-size: 0.75rem;">Fecha fin</label>
                                <input type="date" name="designacion_fin" class="form-control" value="{{ old('designacion_fin') }}">
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-building"></i> Ubicación</div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label-custom">Unidad</label>
                                <select name="unidad" id="unidad" class="form-select" onchange="cargarSubUnidades()">
                                    <option value="">Seleccionar Unidad</option>
                                    <option value="GERENCIA REGIONAL LA PAZ - GRLPZ">GERENCIA REGIONAL LA PAZ - GRLPZ</option>
                                    <option value="Unidad Administrativa">Unidad Administrativa</option>
                                    <option value="Unidad Fiscalización">Unidad Fiscalización</option>
                                    <option value="Unidad Jurídica">Unidad Jurídica</option>
                                    <option value="Administración Aduana Interior La Paz">Administración Aduana Interior La Paz</option>
                                    <option value="Administración Aduana Frontera Guayaramerín">Administración Aduana Frontera Guayaramerín</option>
                                    <option value="Administración Aduana Aeropuerto">Administración Aduana Aeropuerto</option>
                                    <option value="Administración Aduana Zona Franca Patacamaya">Administración Aduana Zona Franca Patacamaya</option>
                                    <option value="Administración Aduana Frontera Desaguadero">Administración Aduana Frontera Desaguadero</option>
                                    <option value="Administración Aduana Frontera Cobija">Administración Aduana Frontera Cobija</option>
                                    <option value="Administración Agencia Aduana Exterior Matarani">Administración Agencia Aduana Exterior Matarani</option>
                                    <option value="Administración Aduana Frontera Charaña">Administración Aduana Frontera Charaña</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label-custom">Sub-Unidad</label>
                                <select name="sub_unidad" id="sub_unidad" class="form-select">
                                    <option value="">Seleccionar Sub-Unidad</option>
                                    @if(isset($subUnidades[old('unidad')]))
                                        @foreach($subUnidades[old('unidad')] as $sub)
                                            <option value="{{ $sub }}" {{ old('sub_unidad') == $sub ? 'selected' : '' }}>{{ $sub }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label-custom"><i class="bi bi-calendar"></i> Ingreso a la Aduana</label>
                                <input type="date" name="fecha_ingreso_aduana" class="form-control" value="{{ old('fecha_ingreso_aduana') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label-custom"><i class="bi bi-calendar-check"></i> Inicio cargo</label>
                                <input type="date" name="fecha_inicio_cargo" class="form-control" value="{{ old('fecha_inicio_cargo') }}">
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-shield-check"></i> Inamovilidad</div>
                        @foreach([
                            ['label'=>'1. Asignación Familiar:','desc'=>'asignacion_familiar_desc','grado'=>'asignacion_familiar_grado','check'=>'asignacion_familiar_check'],
                            ['label'=>'2. Casos especiales:','desc'=>'casos_especiales_desc','grado'=>'casos_especiales_grado','check'=>'casos_especiales_check'],
                        ] as $campo)
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-1 text-center"><input type="checkbox" name="{{ $campo['check'] }}" class="form-check-input custom-checkbox" {{ old($campo['check']) ? 'checked' : '' }}></div>
                            <div class="col-6">
                                <label class="form-label-custom mb-0">{{ $campo['label'] }}</label>
                                <input type="text" name="{{ $campo['desc'] }}" class="form-control" placeholder="Ingresar descripción..." value="{{ old($campo['desc']) }}">
                            </div>
                            <div class="col-2"><label class="form-label-custom mb-0">Grado:</label></div>
                            <div class="col-3">
                                <select name="{{ $campo['grado'] }}" class="form-select">
                                    @foreach(['G','MG','M','L'] as $g) <option value="{{ $g }}" {{ old($campo['grado'])==$g?'selected':'' }}>{{ $g }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                        @endforeach

                        {{-- 3. Discapacidad --}}
                        <div class="mb-2">
                            <div class="row g-3 align-items-center">
                                <div class="col-1 text-center">
                                    <input type="checkbox" name="discapacidad_check" class="form-check-input custom-checkbox" id="chk_disc_item" {{ old('discapacidad_check') ? 'checked' : '' }} onchange="document.getElementById('disc_detalle_item').style.display=this.checked?'block':'none'">
                                </div>
                                <div class="col-6">
                                    <label class="form-label-custom mb-0">3. Discapacidad Ley N° 223:</label>
                                    <input type="text" name="discapacidad_desc" class="form-control" placeholder="Ingresar descripción..." value="{{ old('discapacidad_desc') }}">
                                </div>
                                <div class="col-2"><label class="form-label-custom mb-0">Grado:</label></div>
                                <div class="col-3">
                                    <select name="discapacidad_grado" class="form-select">
                                        @foreach(['G','MG','M','L'] as $g) <option value="{{ $g }}" {{ old('discapacidad_grado')==$g?'selected':'' }}>{{ $g }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="disc_detalle_item" class="mt-3 ms-4 ps-3 border-start border-2" style="border-color: #1a237e !important; display:{{ old('discapacidad_tipo') || old('discapacidad_carnet') ? 'block' : 'none' }};">
                                <div class="row g-3">
                                    <div class="col-4">
                                        <label class="form-label-custom">Tipo de discapacidad</label>
                                        <input type="text" name="discapacidad_tipo" class="form-control" placeholder="Ej: Visual, Motriz..." value="{{ old('discapacidad_tipo') }}">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label-custom">Carnet de discapacidad</label>
                                        <input type="text" name="discapacidad_carnet" class="form-control" placeholder="N° carnet" value="{{ old('discapacidad_carnet') }}">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label-custom">Vence</label>
                                        <input type="date" name="discapacidad_vence" class="form-control" value="{{ old('discapacidad_vence') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-camera"></i> Fotografía</div>
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" class="upload-btn" onclick="document.getElementById('foto_item').click()">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 1.2rem;"></i>
                                Subir Imagen
                            </button>
                            <input type="file" name="fotografia" id="foto_item" accept="image/*" class="d-none" onchange="previewImg(event,'preview-item')">
                            <img id="preview-item" src="" class="rounded-circle border border-2" style="border-color: #1a237e !important; width:55px;height:55px;object-fit:cover;display:none;">
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end mt-4">
                        <button type="submit" class="btn btn-gradient-primary">
                            <i class="bi bi-check-lg me-1"></i> Guardar
                        </button>
                        <a href="{{ route('servidores.index') }}" class="btn btn-gradient-secondary">
                            <i class="bi bi-x-lg me-1"></i> Cancelar
                        </a>
                    </div>

                </form>
            </div>

            {{-- ===== CONSULTORÍA ===== --}}
            <div id="form-consultoria" style="display:none;">
                <form action="{{ route('servidores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tipo" value="consultoria">
                    <input type="hidden" name="accion_duplicado" id="accion_duplicado_cons" value="">
                    <input type="hidden" name="cargo_a_reemplazar" id="cargo_a_reemplazar_cons" value="">
                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-briefcase"></i> Datos de Consultoría</div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label-custom"><i class="bi bi-file-text"></i> Contrato</label>
                                <input type="text" name="contrato_numero" class="form-control" value="{{ old('contrato_numero') }}">
                            </div>
                            <div class="col-3">
                                <label class="form-label-custom"><i class="bi bi-person-badge"></i> Cód. Funcionario</label>
                                <input type="text" name="cod_funcionario" class="form-control" value="{{ old('cod_funcionario') }}">
                            </div>
                            <div class="col-3">
                                <label class="form-label-custom"><i class="bi bi-currency-dollar"></i> Escala Salarial</label>
                                <input type="text" name="escala_salarial" class="form-control" value="{{ old('escala_salarial') }}">
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-person-fill"></i> Nombres del Servidor</div>
                        <div class="row g-3">
                            <div class="col-4">
                                <input type="text" name="nombre" class="form-control" placeholder="Nombres" value="{{ old('nombre') }}">
                            </div>
                            <div class="col-4">
                                <input type="text" name="apellido_paterno" class="form-control" placeholder="Apellido Paterno" value="{{ old('apellido_paterno') }}">
                            </div>
                            <div class="col-4">
                                <input type="text" name="apellido_materno" class="form-control" placeholder="Apellido Materno" value="{{ old('apellido_materno') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <input type="text" name="cargo_consultoria" class="form-control" placeholder="Ingresar nombre del cargo..." value="{{ old('cargo_consultoria') }}">
                    </div>

                    <hr class="section-divider">

                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-pen"></i> Designación</div>
                        <div class="row g-3 align-items-start">
                            <div class="col-5 d-flex flex-column gap-2">
                                <div class="form-check">
                                    <input class="form-check-input custom-checkbox" type="checkbox" name="designacion_tipos[]" value="Designación" id="chk_des_cons"
                                        {{ is_array(old('designacion_tipos')) && in_array('Designación', old('designacion_tipos')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="chk_des_cons" style="font-size: 0.9rem;">Designación</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input custom-checkbox" type="checkbox" name="designacion_tipos[]" value="Interinato" id="chk_int_cons"
                                        {{ is_array(old('designacion_tipos')) && in_array('Interinato', old('designacion_tipos')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="chk_int_cons" style="font-size: 0.9rem;">Interinato</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input custom-checkbox" type="checkbox" name="designacion_tipos[]" value="Comisión" id="chk_com_cons"
                                        {{ is_array(old('designacion_tipos')) && in_array('Comisión', old('designacion_tipos')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="chk_com_cons" style="font-size: 0.9rem;">Comisión</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <label class="form-label-custom" style="font-size: 0.75rem;">Fecha inicio</label>
                                <input type="date" name="designacion_inicio" class="form-control" value="{{ old('designacion_inicio') }}">
                            </div>
                            <div class="col-3">
                                <label class="form-label-custom" style="font-size: 0.75rem;">Fecha fin</label>
                                <input type="date" name="designacion_fin" class="form-control" value="{{ old('designacion_fin') }}">
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-building"></i> Ubicación</div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label-custom">Unidad</label>
                                <select name="unidad" id="unidad_cons" class="form-select" onchange="cargarSubUnidadesCons()">
                                    <option value="">Seleccionar Unidad</option>
                                    <option value="GERENCIA REGIONAL LA PAZ - GRLPZ">GERENCIA REGIONAL LA PAZ - GRLPZ</option>
                                    <option value="Unidad Administrativa">Unidad Administrativa</option>
                                    <option value="Unidad Fiscalización">Unidad Fiscalización</option>
                                    <option value="Unidad Jurídica">Unidad Jurídica</option>
                                    <option value="Administración Aduana Interior La Paz">Administración Aduana Interior La Paz</option>
                                    <option value="Administración Aduana Frontera Guayaramerín">Administración Aduana Frontera Guayaramerín</option>
                                    <option value="Administración Aduana Aeropuerto">Administración Aduana Aeropuerto</option>
                                    <option value="Administración Aduana Zona Franca Patacamaya">Administración Aduana Zona Franca Patacamaya</option>
                                    <option value="Administración Aduana Frontera Desaguadero">Administración Aduana Frontera Desaguadero</option>
                                    <option value="Administración Aduana Frontera Cobija">Administración Aduana Frontera Cobija</option>
                                    <option value="Administración Agencia Aduana Exterior Matarani">Administración Agencia Aduana Exterior Matarani</option>
                                    <option value="Administración Aduana Frontera Charaña">Administración Aduana Frontera Charaña</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label-custom">Sub-Unidad</label>
                                <select name="sub_unidad" id="sub_unidad_cons" class="form-select">
                                    <option value="">Seleccionar Sub-Unidad</option>
                                    @if(isset($subUnidades[old('unidad')]))
                                        @foreach($subUnidades[old('unidad')] as $sub)
                                            <option value="{{ $sub }}" {{ old('sub_unidad') == $sub ? 'selected' : '' }}>{{ $sub }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-4">
                                <label class="form-label-custom"><i class="bi bi-calendar"></i> Ingreso Aduana</label>
                                <input type="date" name="fecha_ingreso_aduana" class="form-control" value="{{ old('fecha_ingreso_aduana') }}">
                            </div>
                            <div class="col-4">
                                <label class="form-label-custom"><i class="bi bi-calendar-check"></i> Inicio contrato</label>
                                <input type="date" name="fecha_inicio_contrato" class="form-control" value="{{ old('fecha_inicio_contrato') }}">
                            </div>
                            <div class="col-4">
                                <label class="form-label-custom"><i class="bi bi-calendar-x"></i> Fin contrato</label>
                                <input type="date" name="fecha_fin_contrato" class="form-control" value="{{ old('fecha_fin_contrato') }}">
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-shield-check"></i> Inamovilidad</div>
                        @foreach([
                            ['label'=>'1. Asignación Familiar:','desc'=>'asignacion_familiar_desc','grado'=>'asignacion_familiar_grado','check'=>'asignacion_familiar_check'],
                            ['label'=>'2. Casos especiales:','desc'=>'casos_especiales_desc','grado'=>'casos_especiales_grado','check'=>'casos_especiales_check'],
                        ] as $campo)
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-1 text-center"><input type="checkbox" name="{{ $campo['check'] }}" class="form-check-input custom-checkbox" {{ old($campo['check']) ? 'checked' : '' }}></div>
                            <div class="col-6">
                                <label class="form-label-custom mb-0">{{ $campo['label'] }}</label>
                                <input type="text" name="{{ $campo['desc'] }}" class="form-control" placeholder="Ingresar descripción..." value="{{ old($campo['desc']) }}">
                            </div>
                            <div class="col-2"><label class="form-label-custom mb-0">Grado:</label></div>
                            <div class="col-3">
                                <select name="{{ $campo['grado'] }}" class="form-select">
                                    @foreach(['G','MG','M','L'] as $g) <option value="{{ $g }}" {{ old($campo['grado'])==$g?'selected':'' }}>{{ $g }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                        @endforeach

                        <div class="mb-2">
                            <div class="row g-3 align-items-center">
                                <div class="col-1 text-center">
                                    <input type="checkbox" name="discapacidad_check" class="form-check-input custom-checkbox" id="chk_disc_cons" {{ old('discapacidad_check') ? 'checked' : '' }} onchange="document.getElementById('disc_detalle_cons').style.display=this.checked?'block':'none'">
                                </div>
                                <div class="col-6">
                                    <label class="form-label-custom mb-0">3. Discapacidad Ley N° 223:</label>
                                    <input type="text" name="discapacidad_desc" class="form-control" placeholder="Ingresar descripción..." value="{{ old('discapacidad_desc') }}">
                                </div>
                                <div class="col-2"><label class="form-label-custom mb-0">Grado:</label></div>
                                <div class="col-3">
                                    <select name="discapacidad_grado" class="form-select">
                                        @foreach(['G','MG','M','L'] as $g) <option value="{{ $g }}" {{ old('discapacidad_grado')==$g?'selected':'' }}>{{ $g }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="disc_detalle_cons" class="mt-3 ms-4 ps-3 border-start border-2" style="border-color: #1a237e !important; display:{{ old('discapacidad_tipo') || old('discapacidad_carnet') ? 'block' : 'none' }};">
                                <div class="row g-3">
                                    <div class="col-4">
                                        <label class="form-label-custom">Tipo de discapacidad</label>
                                        <input type="text" name="discapacidad_tipo" class="form-control" placeholder="Ej: Visual, Motriz..." value="{{ old('discapacidad_tipo') }}">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label-custom">Carnet de discapacidad</label>
                                        <input type="text" name="discapacidad_carnet" class="form-control" placeholder="N° carnet" value="{{ old('discapacidad_carnet') }}">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label-custom">Vence</label>
                                        <input type="date" name="discapacidad_vence" class="form-control" value="{{ old('discapacidad_vence') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <div class="form-section">
                        <div class="section-title"><i class="bi bi-camera"></i> Fotografía</div>
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" class="upload-btn" onclick="document.getElementById('foto_cons').click()">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 1.2rem;"></i>
                                Subir Imagen
                            </button>
                            <input type="file" name="fotografia" id="foto_cons" accept="image/*" class="d-none" onchange="previewImg(event,'preview-cons')">
                            <img id="preview-cons" src="" class="rounded-circle border border-2" style="border-color: #1a237e !important; width:55px;height:55px;object-fit:cover;display:none;">
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end mt-4">
                        <button type="submit" class="btn btn-gradient-primary">
                            <i class="bi bi-check-lg me-1"></i> Guardar
                        </button>
                        <a href="{{ route('servidores.index') }}" class="btn btn-gradient-secondary">
                            <i class="bi bi-x-lg me-1"></i> Cancelar
                        </a>
                    </div>

                </form>
            </div>

                        </div>{{-- fin card-body --}}
                    </div>{{-- fin card --}}
                </div>{{-- fin form-card-body --}}
            </div>{{-- fin form-card --}}
        </div>{{-- fin col-md-7 --}}
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

// Si hay duplicados, mostrar el formulario correspondiente
@if(session('duplicados'))
    mostrarFormulario('{{ old('tipo', 'item') }}');
    document.getElementById('selectorTipo').value = '{{ old('tipo', 'item') }}';
@endif

const subUnidadesData = @json($subUnidades);

function llenarSubUnidades(unidad, selectEl) {
    selectEl.innerHTML = '<option value="">Seleccionar Sub-Unidad</option>';
    if (subUnidadesData[unidad]) {
        subUnidadesData[unidad].forEach(function(sub) {
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

function setAccionDuplicado(accion) {
    const tipo = document.getElementById('selectorTipo').value;
    const sufijo = tipo === 'consultoria' ? 'cons' : 'item';
    document.getElementById('accion_duplicado_' + sufijo).value = accion;
    document.getElementById('reemplazar-select-wrapper').style.display = accion === 'reemplazar' ? 'block' : 'none';
    const chkComision = document.getElementById(sufijo === 'cons' ? 'chk_com_cons' : 'chk_com_item');
    if (accion === 'comision' && chkComision) {
        chkComision.checked = true;
    }
    if (accion !== 'reemplazar') {
        document.getElementById('cargo_a_reemplazar_' + sufijo).value = '';
    }
}

// Cuando cambia el select de reemplazo, copiar valor al hidden dentro del form activo
document.addEventListener('change', function(e) {
    if (e.target.id === 'cargo_a_reemplazar') {
        const tipo = document.getElementById('selectorTipo').value;
        const sufijo = tipo === 'consultoria' ? 'cons' : 'item';
        document.getElementById('cargo_a_reemplazar_' + sufijo).value = e.target.value;
    }
});
</script>
@endsection
