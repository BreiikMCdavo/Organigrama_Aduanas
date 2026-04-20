@extends('layouts.app')

@section('content')

    <div class="container text-center mt-4">

        <!-- GERENCIA -->
        <div class="box mb-4" onclick="mostrarInfo('GERENCIA REGIONAL LA PAZ - GRLPZ')">
            GERENCIA REGIONAL LA PAZ - GRLPZ
        </div>

        <!-- PRIMER NIVEL -->
        <div class="zigzag-container mb-4">

            <div class="box left" onclick="mostrarInfo('ASESORÍA')">ASESORÍA</div>
            <div class="box right" onclick="mostrarInfo('SECRETARIA')">SECRETARIA</div>
            <div class="box left" onclick="mostrarInfo('SISTEMAS')">SISTEMAS</div>
            <div class="box right" onclick="mostrarInfo('USO')">USO</div>
            <div class="box left" onclick="mostrarInfo('ARCHIVO')">ARCHIVO</div>

        </div>

        <hr>

        <!-- SEGUNDO NIVEL -->
        <!-- <div class="row g-3 mb-3"> -->
        <div class="row nivel-hijos g-3 mb-3">

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
                    <div class="sub-box" onclick="mostrarInfo('Fiscalizaciones posteriores')">Fiscalizaciones posteriores
                    </div>
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
        <!-- <div class="row g-3"> -->

        <!-- TERCER NIVEL -->
        <div class="row nivel-hijos nivel-tercer g-3">

            <!-- INTERIOR LA PAZ -->
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

            <!-- GUAYARAMERIN -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#guayaramerin">
                    Aduana Frontera Guayaramerín
                </button>
                <div id="guayaramerin" class="collapse mt-2">
                    <div class="sub-box">Control Operativo</div>
                    <div class="sub-box">Despachos</div>
                    <div class="sub-box">Administración</div>
                </div>
            </div>

            <!-- AEROPUERTO -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#aeropuerto">
                    Aduana Aeropuerto El Alto
                </button>
                <div id="aeropuerto" class="collapse mt-2">
                    <div class="sub-box">Carga Aérea</div>
                    <div class="sub-box">Equipajes</div>
                    <div class="sub-box">Administración</div>
                </div>
            </div>

            <!-- PATACAMAYA -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#patacamaya">
                    Administración Aduana Zona Franca Industrial Patacamaya
                </button>
                <div id="patacamaya" class="collapse mt-2">
                    <div class="sub-box">Operaciones</div>
                    <div class="sub-box">Control</div>
                    <div class="sub-box">Administración</div>
                </div>
            </div>

            <!-- DESAGUADERO -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#desaguadero">
                    Administración Aduana Frontera Desaguadero
                </button>
                <div id="desaguadero" class="collapse mt-2">
                    <div class="sub-box">Control Fronterizo</div>
                    <div class="sub-box">Despachos</div>
                    <div class="sub-box">Administración</div>
                </div>
            </div>

            <!-- COBIJA -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#cobija">
                    Zona Franca Comercial / Frontera Cobija
                </button>
                <div id="cobija" class="collapse mt-2">
                    <div class="sub-box">Comercial</div>
                    <div class="sub-box">Control</div>
                    <div class="sub-box">Administración</div>
                </div>
            </div>

            <!-- MATARANI -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#matarani">
                    Agencia Aduana Exterior Matarani
                </button>
                <div id="matarani" class="collapse mt-2">
                    <div class="sub-box">Operaciones</div>
                    <div class="sub-box">Despachos</div>
                    <div class="sub-box">Administración</div>
                </div>
            </div>

            <!-- CHARANA -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#charana">
                    Administración Aduana Frontera Charaña
                </button>
                <div id="charana" class="collapse mt-2">
                    <div class="sub-box">Control</div>
                    <div class="sub-box">Despachos</div>
                    <div class="sub-box">Administración</div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('js')
    <script>
        const servidores = @json($servidores ?? []);
    </script>

    <script src="{{ asset('js/organigrama.js') }}"></script>
@endsection