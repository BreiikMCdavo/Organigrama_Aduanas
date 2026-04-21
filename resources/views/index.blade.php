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

            <!-- UNIDAD ADMINISTRATIVA -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#admin"
                    onclick="mostrarInfo('Unidad Administrativa')">
                    Unidad Administrativa
                </button>

                <div id="admin" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Responsable Administrativo Financiero')">Responsable Administrativo Financiero</div>
                    <div class="sub-box" onclick="mostrarInfo('Auxiliar Unidad Administrativa')">Auxiliar Unidad Administrativa</div>
                    <div class="sub-box" onclick="mostrarInfo('Activos Fijos')">Activos Fijos</div>
                    <div class="sub-box" onclick="mostrarInfo('Contabilidad')">Contabilidad</div>
                    <div class="sub-box" onclick="mostrarInfo('Talento Humano')">Talento Humano</div>
                    <div class="sub-box" onclick="mostrarInfo('Contrataciones')">Contrataciones</div>
                    <div class="sub-box" onclick="mostrarInfo('Servicios Generales')">Servicios Generales</div>
                </div>
            </div>

            <!-- UNIDAD FISCALIZACIÓN -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#fiscal">Unidad Fiscalización</button>

                <div id="fiscal" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Jefes Unidad Fiscalización')">Jefes Unidad Fiscalización</div>
                    <div class="sub-box" onclick="mostrarInfo('Supervisores Fiscalización')">Supervisores Fiscalización</div>
                    <div class="sub-box" onclick="mostrarInfo('Auxiliar Fiscalización')">Auxiliar Fiscalización</div>
                    <div class="sub-box" onclick="mostrarInfo('Fiscalizaciones posteriores / Controles diferidos')">Fiscalizaciones posteriores / Controles diferidos</div>
                </div>
            </div>

            <!-- UNIDAD JURÍDICA -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#juridica"
                    onclick="mostrarInfo('Unidad Jurídica')">
                    Unidad Jurídica
                </button>

                <div id="juridica" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Responsable Administrativo Jurídica')">Responsable Administrativo Jurídica</div>
                    <div class="sub-box" onclick="mostrarInfo('Auxiliar Unidad Jurídica')">Auxiliar Unidad Jurídica</div>
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
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#interior"
                    onclick="mostrarInfo('Administración Aduana Interior La Paz')">
                    Administración Aduana Interior La Paz
                </button>

                <div id="interior" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Secretaria Aduana Interior La Paz'">Secretaria Aduana Interior La Paz</div>
                    <div class="sub-box" onclick="mostrarInfo('Administrador Aduana Interior La Paz'">Administrador Aduana Interior La Paz</div>
                    <div class="sub-box" onclick="mostrarInfo('SPCC (Comisos)')">SPCC (Comisos)</div>
                    <div class="sub-box" onclick="mostrarInfo('Disposición de mercancías')">Disposición de mercancías</div>
                    <div class="sub-box" onclick="mostrarInfo('Despachos')">Despachos</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestión')">Gestión</div>
                </div>
            </div>

            <!-- GUAYARAMERIN -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#guayaramerin"
                    onclick="mostrarInfo('Aduana Frontera Guayaramerín')">
                    Aduana Frontera Guayaramerín
                </button>

                <div id="guayaramerin" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Secretaria Guayaramerin')">Secretaria Guayaramerin</div>
                    <div class="sub-box" onclick="mostrarInfo('Administrador Guayamerin')">Administrador Guayamerin</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestion Aduanera / Operativa Guayamerin')">Gestion Aduanera / Operativa Guayamerin</div>
                </div>
            </div>

            <!-- AEROPUERTO -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#aeropuerto"
                    onclick="mostrarInfo('Aduana Aeropuerto El Alto')">
                    Aduana Aeropuerto El Alto
                </button>

                <div id="aeropuerto" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Secretaria Aeropuerto El Altoa')">Secretaria Aeropuerto El Alto</div>
                    <div class="sub-box" onclick="mostrarInfo('Administrador Aeropuerto El Alto')">Administrador Aeropuerto El Alto</div>
                    <div class="sub-box" onclick="mostrarInfo('Supervisor Aeropuerto El Alto')">Supervisor Aeropuerto El Alto Aérea</div>
                    <div class="sub-box" onclick="mostrarInfo('Despachos Aeropuerto El Alto')">Despachos Aeropuerto El Alto</div>
                    <div class="sub-box" onclick="mostrarInfo('Tecnico gestion Aeropuerto El Alto')">Tecnico gestion Aeropuerto El Alto</div>
                    <div class="sub-box" onclick="mostrarInfo('SPCC Aeropuerto El Alto')">SPCC Aeropuerto El Alto </div>
                </div>
            </div>

            <!-- PATACAMAYA -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#patacamaya"
                    onclick="mostrarInfo('Administración Aduana Zona Franca Industrial Patacamaya')">
                    Administración Aduana Zona Franca Industrial Patacamaya
                </button>

                <div id="patacamaya" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Secretaria Patacamaya')">Secretaria Patacamaya</div>
                    <div class="sub-box" onclick="mostrarInfo('Administrador Patacamaya')">Administrador Patacamaya</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestion Aduanera / Operativa Patacamaya')">Gestion Aduanera / Operativa Patacamaya</div>
                </div>
            </div>

            <!-- DESAGUADERO -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#desaguadero"
                    onclick="mostrarInfo('Administración Aduana Frontera Desaguadero')">
                    Administración Aduana Frontera Desaguadero
                </button>

                <div id="desaguadero" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Secretaria Frontera Desaguadero')">Secretaria Frontera Desaguadero</div>
                    <div class="sub-box" onclick="mostrarInfo('Administrador Frontera Desaguadero')">Administrador Frontera Desaguadero</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestion Aduanera / Operativa Desaguadero')">Gestion Aduanera / Operativa Desaguadero</div>
                </div>
            </div>

            <!-- COBIJA -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#cobija"
                    onclick="mostrarInfo('Zona Franca Comercial / Frontera Cobija')">
                    Zona Franca Comercial / Frontera Cobija
                </button>
                
                <div id="cobija" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Secretaria Frontera Cobija')">Secretaria Frontera Cobija</div>
                    <div class="sub-box" onclick="mostrarInfo('Administrador Frontera Cobija')">Administrador Frontera Cobija</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestion Aduanera / Operativa Cobija')">Gestion Aduanera / Operativa Cobija</div>
                    <div class="sub-box" onclick="mostrarInfo('Zofra Cobija')">Zofra Cobija</div>
                    <div class="sub-box" onclick="mostrarInfo('Aeropuerto Cobija')">Aeropuerto Cobija</div>
                </div>
            </div>

            <!-- MATARANI -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#matarani"
                    onclick="mostrarInfo('Agencia Aduana Exterior Matarani')">
                    Agencia Aduana Exterior Matarani
                </button>

                <div id="matarani" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('OperaciSecretaria Exterior Mataraniones')">Secretaria Exterior Matarani</div>
                    <div class="sub-box" onclick="mostrarInfo('Administrador Exterior Matarani')">Administrador Exterior Matarani</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestion Aduanera / Operativa Matarani')">Gestion Aduanera / Operativa Matarani</div>
                </div>
            </div>

            <!-- CHARANA -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#charana"
                    onclick="mostrarInfo('Administración Aduana Frontera Charaña')">
                    Administración Aduana Frontera Charaña
                </button>

                <div id="charana" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Secretaria Frontera Charaña')">Secretaria Frontera Charaña</div>
                    <div class="sub-box" onclick="mostrarInfo('Administrador Frontera Charaña')">Administrador Frontera Charaña</div>
                    <div class="sub-box" onclick="mostrarInfo('Despachos / Minimas cuantrillas')">Despachos / Minimas cuantrillas</div>
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