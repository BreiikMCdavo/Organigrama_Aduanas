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
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if ($errors->any())
                <div class="alert alert-danger elegant-alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card form-card">
                <div class="form-card-header text-center">
                    <h6 class="mb-0 text-white fw-bold" style="font-size: 1rem; letter-spacing: 1px;">
                        <i class="bi bi-pencil-square me-2"></i>EDITAR SERVIDOR PÚBLICO
                    </h6>
                </div>
                <div class="form-card-body">

                    <form action="{{ route('servidores.update', $servidor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tipo" value="{{ $servidor->tipo }}">

                        @if($servidor->tipo === 'item')

                            {{-- N° Ítem, CITE, Código, Escala --}}
                            <div class="row g-3 mb-3">
                                <div class="col-3">
                                    <label class="form-label-custom"><i class="bi bi-hash"></i> N° Ítem</label>
                                    <input type="text" name="numero_item" class="form-control" value="{{ old('numero_item', $servidor->numero_item) }}">
                                </div>
                                <div class="col-3">
                                    <label class="form-label-custom"><i class="bi bi-file-text"></i> CITE Mem.</label>
                                    <input type="text" name="cite_memorandum" class="form-control" value="{{ old('cite_memorandum', $servidor->cite_memorandum) }}">
                                </div>
                                <div class="col-3">
                                    <label class="form-label-custom"><i class="bi bi-person-badge"></i> Cód. Funcionario</label>
                                    <input type="text" name="cod_funcionario" class="form-control" value="{{ old('cod_funcionario', $servidor->cod_funcionario) }}">
                                </div>
                                <div class="col-3">
                                    <label class="form-label-custom"><i class="bi bi-currency-dollar"></i> Escala Salarial</label>
                                    <input type="text" name="escala_salarial" class="form-control" value="{{ old('escala_salarial', $servidor->escala_salarial) }}">
                                </div>
                            </div>

                            <hr class="section-divider">

                            <div class="form-section">
                                <div class="section-title"><i class="bi bi-person-fill"></i> Nombres del Servidor</div>
                                <div class="row g-3">
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
                            </div>

                            <div class="mb-3">
                                <input type="text" name="cargo" class="form-control" placeholder="Ingresar nombre del cargo..." value="{{ old('cargo', $servidor->cargo) }}">
                            </div>

                            <hr class="section-divider">

                            <div class="form-section">
                                <div class="section-title"><i class="bi bi-building"></i> Ubicación</div>
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <label class="form-label-custom">Unidad</label>
                                        <select name="unidad" id="unidad" class="form-select" onchange="cargarSubUnidades()">
                                            <option value="">Seleccionar Unidad</option>
                                            @foreach([
                                                'GERENCIA REGIONAL LA PAZ - GRLPZ',
                                                'Unidad Administrativa',
                                                'Unidad Fiscalización',
                                                'Unidad Jurídica',
                                                'Administración Aduana Interior La Paz',
                                                'Administración Aduana Frontera Guayaramerín',
                                                'Administración Aduana Aeropuerto',
                                                'Administración Aduana Zona Franca Patacamaya',
                                                'Administración Aduana Frontera Desaguadero',
                                                'Administración Aduana Frontera Cobija',
                                                'Administración Agencia Aduana Exterior Matarani',
                                                'Administración Aduana Frontera Charaña',
                                            ] as $u)
                                                <option value="{{ $u }}" {{ old('unidad', $servidor->unidad)==$u?'selected':'' }}>{{ $u }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label-custom">Sub-Unidad</label>
                                        <select name="sub_unidad" id="sub_unidad" class="form-select">
                                            <option value="">Seleccionar Sub-Unidad</option>
                                            @php $unidadSeleccionada = old('unidad', $servidor->unidad); @endphp
                                            @if(isset($subUnidades[$unidadSeleccionada]))
                                                @foreach($subUnidades[$unidadSeleccionada] as $sub)
                                                    <option value="{{ $sub }}" {{ old('sub_unidad', $servidor->sub_unidad) == $sub ? 'selected' : '' }}>{{ $sub }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label-custom"><i class="bi bi-calendar"></i> Ingreso a la Aduana</label>
                                        <input type="date" name="fecha_ingreso_aduana" class="form-control" value="{{ old('fecha_ingreso_aduana', $servidor->fecha_ingreso_aduana) }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label-custom"><i class="bi bi-calendar-check"></i> Inicio cargo</label>
                                        <input type="date" name="fecha_inicio_cargo" class="form-control" value="{{ old('fecha_inicio_cargo', $servidor->fecha_inicio_cargo) }}">
                                    </div>
                                </div>
                            </div>

                        @else

                            {{-- Consultoría --}}
                            <div class="form-section">
                                <div class="section-title"><i class="bi bi-briefcase"></i> Datos de Consultoría</div>
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <label class="form-label-custom"><i class="bi bi-file-text"></i> Contrato</label>
                                        <input type="text" name="contrato_numero" class="form-control" value="{{ old('contrato_numero', $servidor->contrato_numero) }}">
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label-custom"><i class="bi bi-person-badge"></i> Cód. Funcionario</label>
                                        <input type="text" name="cod_funcionario" class="form-control" value="{{ old('cod_funcionario', $servidor->cod_funcionario) }}">
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label-custom"><i class="bi bi-currency-dollar"></i> Escala Salarial</label>
                                        <input type="text" name="escala_salarial" class="form-control" value="{{ old('escala_salarial', $servidor->escala_salarial) }}">
                                    </div>
                                </div>

                                <hr class="section-divider">

                                <div class="section-title" style="font-size:0.8rem;"><i class="bi bi-person-fill"></i> Nombres del Servidor</div>
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
                                    <input type="text" name="cargo_consultoria" class="form-control" placeholder="Ingresar descripción del cargo..." value="{{ old('cargo_consultoria', $servidor->cargo_consultoria) }}">
                                </div>

                                <hr class="section-divider">

                                <div class="section-title" style="font-size:0.8rem;"><i class="bi bi-building"></i> Ubicación</div>
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <label class="form-label-custom">Unidad</label>
                                        <select name="unidad" id="unidad_cons" class="form-select" onchange="cargarSubUnidadesCons()">
                                            <option value="">Seleccionar Unidad</option>
                                            @foreach([
                                                'GERENCIA REGIONAL LA PAZ - GRLPZ',
                                                'Unidad Administrativa',
                                                'Unidad Fiscalización',
                                                'Unidad Jurídica',
                                                'Administración Aduana Interior La Paz',
                                                'Administración Aduana Frontera Guayaramerín',
                                                'Administración Aduana Aeropuerto',
                                                'Administración Aduana Zona Franca Patacamaya',
                                                'Administración Aduana Frontera Desaguadero',
                                                'Administración Aduana Frontera Cobija',
                                                'Administración Agencia Aduana Exterior Matarani',
                                                'Administración Aduana Frontera Charaña',
                                            ] as $u)
                                                <option value="{{ $u }}" {{ old('unidad', $servidor->unidad)==$u?'selected':'' }}>{{ $u }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label-custom">Sub-Unidad</label>
                                        <select name="sub_unidad" id="sub_unidad_cons" class="form-select">
                                            <option value="">Seleccionar Sub-Unidad</option>
                                            @if(isset($subUnidades[old('unidad', $servidor->unidad)]))
                                                @foreach($subUnidades[old('unidad', $servidor->unidad)] as $sub)
                                                    <option value="{{ $sub }}" {{ old('sub_unidad', $servidor->sub_unidad) == $sub ? 'selected' : '' }}>{{ $sub }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-4">
                                        <label class="form-label-custom"><i class="bi bi-calendar"></i> Ingreso Aduana</label>
                                        <input type="date" name="fecha_ingreso_aduana" class="form-control" value="{{ old('fecha_ingreso_aduana', $servidor->fecha_ingreso_aduana) }}">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label-custom"><i class="bi bi-calendar-check"></i> Inicio contrato</label>
                                        <input type="date" name="fecha_inicio_contrato" class="form-control" value="{{ old('fecha_inicio_contrato', $servidor->fecha_inicio_contrato) }}">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label-custom"><i class="bi bi-calendar-x"></i> Fin contrato</label>
                                        <input type="date" name="fecha_fin_contrato" class="form-control" value="{{ old('fecha_fin_contrato', $servidor->fecha_fin_contrato) }}">
                                    </div>
                                </div>
                            </div>

                        @endif

                        <hr class="section-divider">

                        {{-- Inamovilidad (común) --}}
                        <div class="form-section">
                            <div class="section-title"><i class="bi bi-shield-check"></i> Inamovilidad</div>
                            @foreach([
                                ['label'=>'1. Asignación Familiar:','desc'=>'asignacion_familiar_desc','grado'=>'asignacion_familiar_grado','check'=>'asignacion_familiar_check'],
                                ['label'=>'2. Casos especiales:','desc'=>'casos_especiales_desc','grado'=>'casos_especiales_grado','check'=>'casos_especiales_check'],
                            ] as $campo)
                            <div class="row g-3 align-items-center mb-3">
                                <div class="col-1 text-center">
                                    <input type="checkbox" name="{{ $campo['check'] }}" class="form-check-input custom-checkbox"
                                        {{ old($campo['check'], $servidor->{$campo['check']}) ? 'checked' : '' }}>
                                </div>
                                <div class="col-6">
                                    <label class="form-label-custom mb-0">{{ $campo['label'] }}</label>
                                    <input type="text" name="{{ $campo['desc'] }}" class="form-control"
                                           placeholder="Ingresar descripción..."
                                           value="{{ old($campo['desc'], $servidor->{$campo['desc']}) }}">
                                </div>
                                <div class="col-2"><label class="form-label-custom mb-0">Grado:</label></div>
                                <div class="col-3">
                                    <select name="{{ $campo['grado'] }}" class="form-select">
                                        @foreach(['G','MG','M','L'] as $g)
                                            <option value="{{ $g }}" {{ old($campo['grado'], $servidor->{$campo['grado']})==$g?'selected':'' }}>{{ $g }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endforeach

                            {{-- 3. Discapacidad --}}
                            <div class="mb-2">
                                <div class="row g-3 align-items-center">
                                    <div class="col-1 text-center">
                                        <input type="checkbox" name="discapacidad_check" class="form-check-input custom-checkbox" id="chk_disc_edit"
                                            {{ old('discapacidad_check', $servidor->discapacidad_check) ? 'checked' : '' }}
                                            onchange="document.getElementById('disc_detalle_edit').style.display=this.checked?'block':'none'">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label-custom mb-0">3. Discapacidad Ley N° 223:</label>
                                        <input type="text" name="discapacidad_desc" class="form-control"
                                               placeholder="Ingresar descripción..."
                                               value="{{ old('discapacidad_desc', $servidor->discapacidad_desc) }}">
                                    </div>
                                    <div class="col-2"><label class="form-label-custom mb-0">Grado:</label></div>
                                    <div class="col-3">
                                        <select name="discapacidad_grado" class="form-select">
                                            @foreach(['G','MG','M','L'] as $g)
                                                <option value="{{ $g }}" {{ old('discapacidad_grado', $servidor->discapacidad_grado)==$g?'selected':'' }}>{{ $g }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="disc_detalle_edit" class="mt-3 ms-4 ps-3 border-start border-2" style="border-color: #1a237e !important; display:{{ $servidor->discapacidad_tipo || $servidor->discapacidad_carnet || $servidor->discapacidad_vence ? 'block' : 'none' }};">
                                    <div class="row g-3">
                                        <div class="col-4">
                                            <label class="form-label-custom">Tipo de discapacidad</label>
                                            <input type="text" name="discapacidad_tipo" class="form-control"
                                                   placeholder="Ej: Visual, Motriz..."
                                                   value="{{ old('discapacidad_tipo', $servidor->discapacidad_tipo) }}">
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label-custom">Carnet de discapacidad</label>
                                            <input type="text" name="discapacidad_carnet" class="form-control"
                                                   placeholder="N° carnet"
                                                   value="{{ old('discapacidad_carnet', $servidor->discapacidad_carnet) }}">
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label-custom">Vence</label>
                                            <input type="date" name="discapacidad_vence" class="form-control"
                                                   value="{{ old('discapacidad_vence', $servidor->discapacidad_vence ? \Carbon\Carbon::parse($servidor->discapacidad_vence)->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">

                        <div class="form-section">
                            <div class="section-title"><i class="bi bi-camera"></i> Fotografía</div>
                            <div class="d-flex align-items-center gap-3">
                                @if($servidor->fotografia_url)
                                    <img src="{{ $servidor->fotografia_url }}"
                                         id="preview-edit" class="rounded-circle border border-2"
                                         style="border-color: #1a237e !important; width:55px;height:55px;object-fit:cover;">
                                @else
                                    <img id="preview-edit" src="" class="rounded-circle border border-2"
                                         style="border-color: #1a237e !important; width:55px;height:55px;object-fit:cover;display:none;">
                                @endif
                                <button type="button" class="upload-btn" onclick="document.getElementById('foto_edit').click()">
                                    <i class="bi bi-cloud-arrow-up" style="font-size: 1.2rem;"></i>
                                    Cambiar imagen
                                </button>
                                <input type="file" name="fotografia" id="foto_edit" accept="image/*" class="d-none" onchange="previewEdit(event)">
                            </div>
                        </div>

                        <div class="d-flex gap-3 justify-content-end mt-4">
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="bi bi-check-lg me-1"></i> Actualizar
                            </button>
                            <a href="{{ route('servidores.show', $servidor->id) }}" class="btn btn-gradient-secondary">
                                <i class="bi bi-x-lg me-1"></i> Cancelar
                            </a>
                        </div>

                    </form>
                </div>
            </div>
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

const subUnidadesData = @json($subUnidades);

function llenarSubUnidades(unidad, selectEl, valorActual) {
    selectEl.innerHTML = '<option value="">Seleccionar Sub-Unidad</option>';
    if (subUnidadesData[unidad]) {
        subUnidadesData[unidad].forEach(function(sub) {
            const opt = document.createElement('option');
            opt.value = sub;
            opt.text = sub;
            if (sub === valorActual) opt.selected = true;
            selectEl.appendChild(opt);
        });
    }
}

function cargarSubUnidades() {
    llenarSubUnidades(
        document.getElementById('unidad').value,
        document.getElementById('sub_unidad'),
        ''
    );
}

function cargarSubUnidadesCons() {
    llenarSubUnidades(
        document.getElementById('unidad_cons').value,
        document.getElementById('sub_unidad_cons'),
        ''
    );
}
</script>
@endsection
