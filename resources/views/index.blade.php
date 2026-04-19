@extends('layouts.app')

@section('content')

<div class="container text-center mt-4">

    <!-- GERENCIA -->
    <div class="box mb-4" onclick="mostrarInfo('GERENCIA REGIONAL LA PAZ - GRLPZ')">
        GERENCIA REGIONAL LA PAZ - GRLPZ
        
    </div>

    <!-- PRIMER NIVEL -->
    <div class="row justify-content-center g-2 mb-3">
        <div class="col"><div class="box" onclick="mostrarInfo('ASESORÍA')">ASESORÍA</div></div>
        <div class="col"><div class="box" onclick="mostrarInfo('SECRETARIA')">SECRETARIA</div></div>
        <div class="col"><div class="box" onclick="mostrarInfo('SISTEMAS')">SISTEMAS</div></div>
        <div class="col"><div class="box" onclick="mostrarInfo('USO')">USO</div></div>
        <div class="col"><div class="box" onclick="mostrarInfo('ARCHIVO')">ARCHIVO</div></div>
    </div>

    <hr>

    <!-- SEGUNDO NIVEL -->
    <div class="row g-3 mb-3">

        <div class="col-md-4">
            <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#admin">
                Unidad Administrativa
            </button>
            <div id="admin" class="collapse mt-2">
                <div class="sub-box" onclick="mostrarInfo('Contabilidad')">Contabilidad</div>
                <div class="sub-box" onclick="mostrarInfo('Activos Fijos')">Activos Fijos</div>
                <div class="sub-box" onclick="mostrarInfo('Talento Humano')">Talento Humano</div>
                <div class="sub-box" onclick="mostrarInfo('Contrataciones')">Contrataciones</div>
                <div class="sub-box" onclick="mostrarInfo('Servicios Generales')">Servicios Generales</div>
            </div>
        </div>

        <div class="col-md-4">
            <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#fiscal">
                Unidad Fiscalización
            </button>
            <div id="fiscal" class="collapse mt-2">
                <div class="sub-box" onclick="mostrarInfo('Fiscalizaciones posteriores')">Fiscalizaciones posteriores</div>
                <div class="sub-box" onclick="mostrarInfo('Controles diferidos')">Controles diferidos</div>
            </div>
        </div>

        <div class="col-md-4">
            <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#juridica">
                Unidad Jurídica
            </button>
            <div id="juridica" class="collapse mt-2">
                <div class="sub-box" onclick="mostrarInfo('Cobranza coactiva')">Cobranza coactiva</div>
                <div class="sub-box" onclick="mostrarInfo('Técnica jurídica')">Técnica jurídica</div>
                <div class="sub-box" onclick="mostrarInfo('Procesos administrativos')">Procesos administrativos</div>
            </div>
        </div>

    </div>

    <hr>

    <!-- TERCER NIVEL -->
    <div class="row g-3">

        <div class="col-md-4">
            <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#interior">
                Administración Aduana Interior La Paz
            </button>
            <div id="interior" class="collapse mt-2">
                <div class="sub-box" onclick="mostrarInfo('SPCC (Comisos)')">SPCC (Comisos)</div>
                <div class="sub-box" onclick="mostrarInfo('Disposición de mercancías')">Disposición de mercancías</div>
                <div class="sub-box" onclick="mostrarInfo('Despachos')">Despachos</div>
                <div class="sub-box" onclick="mostrarInfo('Gestión')">Gestión</div>
            </div>
        </div>

        <div class="col-md-4">
            <button class="box w-100" onclick="mostrarInfo('Aduana Frontera Guayaramerín')">
                Aduana Frontera Guayaramerín
            </button>
        </div>

        <div class="col-md-4">
            <button class="box w-100" onclick="mostrarInfo('Aduana Aeropuerto El Alto')">
                Aduana Aeropuerto El Alto
            </button>
        </div>

    </div>

</div>

<script>
    const servidores = @json($servidores ?? []);
</script>

<script src="{{ asset('js/organigrama.js') }}"></script>
@endsection

@section('js')
<script>
    const servidores = @json($servidores ?? []);
</script>

<script src="{{ asset('js/organigrama.js') }}"></script>
@endsection