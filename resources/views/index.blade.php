@extends('layouts.app')

@push('styles')
<style>
    .menu-burbuja {
      position: fixed;
      bottom: 30px;
      right: 30px;
      z-index: 1000;
    }

    .menu-burbuja .boton-menu {
      width: 60px;
      height: 60px;
      background: #8B0000;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(0, 0, 0, .3);
      transition: .3s;
      border: none;
      color: white;
      font-size: 24px;
    }

    .menu-burbuja .boton-menu:hover {
      transform: scale(1.1);
      background: #a00000;
    }

    .menu-burbuja .opciones {
      position: absolute;
      bottom: 70px;
      right: 0;
      display: flex;
      flex-direction: column;
      gap: 15px;
      opacity: 0;
      visibility: hidden;
      transition: .3s;
    }

    .menu-burbuja .opciones.active {
      opacity: 1;
      visibility: visible;
    }

    .menu-burbuja .opcion {
      width: 50px;
      height: 50px;
      background: white;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(0, 0, 0, .2);
      transition: .3s;
      text-decoration: none;
      color: #8B0000;
      font-weight: bold;
      font-size: 14px;
      border: 2px solid #8B0000;
    }

    .menu-burbuja .opcion:hover {
      transform: scale(1.1);
      background: #8B0000;
      color: white;
    }

    .menu-burbuja .opcion span {
      position: absolute;
      right: 60px;
      background: #8B0000;
      color: white;
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 12px;
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: .3s;
    }

    .menu-burbuja .opcion:hover span {
      opacity: 1;
      visibility: visible;
    }
</style>
@endpush

@section('content')

    <div class="menu-burbuja">
      <div class="opciones">
        <a href="/index" class="opcion">
          1
          <span>Organigrama</span>
        </a>
        <a href="/gafetes" class="opcion">
          2
          <span>Gafetes</span>
        </a>
        <a href="/diagramas" class="opcion">
          3
          <span>Diagramas</span>
        </a>
      </div>
      <button class="boton-menu" onclick="toggleMenu()">☰</button>
    </div>

    <div class="container text-center mt-4">

        <!-- GERENCIA -->
        <div class="box mb-4" onclick="mostrarInfo('GERENCIA REGIONAL LA PAZ - GRLPZ')">
            GERENCIA REGIONAL LA PAZ - GRLPZ
        </div>

        <!-- PRIMER NIVEL -->
        <div class="zigzag-container mb-4">

            <div class="box right" onclick="mostrarInfo('GERENTE')">GERENTE</div>
            <div class="box left" onclick="mostrarInfo('ASESORÍA')">ASESORÍA</div>
            <div class="box right" onclick="mostrarInfo('PLATAFORMA')">PLATAFORMA</div>
            <div class="box left" onclick="mostrarInfo('SISTEMAS')">SISTEMAS</div>
            <div class="box right" onclick="mostrarInfo('ARCHIVO')">ARCHIVO</div>
            <div class="box left" onclick="mostrarInfo('SECRETARIA')">SECRETARIA</div>

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
                    <div class="sub-box" onclick="mostrarInfo('Resposanble Administrativa')">Resposanble Administrativa</div>
                    <div class="sub-box" onclick="mostrarInfo('Auxiliar Unidad Administrativa')">Auxiliar Unidad Administrativa</div>
                    <!-- <div class="sub-box" onclick="mostrarInfo('Bienes y Servicios Unidad Administrativa')">Bienes y Servicios</div> -->
                    <div class="sub-box" onclick="mostrarInfo('Bienes y Servicios / Activos Fijos')">Bienes y Servicios / Activos Fijos</div>
                    <div class="sub-box" onclick="mostrarInfo('Bienes y Servicios / Servicios Generales')">Bienes y Servicios / Servicios Generales</div>
                    <div class="sub-box" onclick="mostrarInfo('Bienes y Servicios / Contrataciones')">Bienes y Servicios / Contrataciones</div>
                    <!-- <div class="sub-box" onclick="mostrarInfo('Talento Humano Unidad Administrativa')">Talento Humano</div> -->
                    <div class="sub-box" onclick="mostrarInfo('Talento Humano / Regimiento Laboral')">Talento Humano / Regimiento Laboral</div>
                    <div class="sub-box" onclick="mostrarInfo('Talento Humano / Planillal')">Talento Humano / Planillal</div>
                    <!-- <div class="sub-box" onclick="mostrarInfo('Finanzas Unidad Administrativa')"></div> -->
                    <div class="sub-box" onclick="mostrarInfo('Finanzas / Contabilidad y Presupuesto')">Finanzas / Contabilidad y Presupuesto</div>
                    <div class="sub-box" onclick="mostrarInfo('Finanzas / Tesorería y Archivo')">Finanzas / Tesorería y Archivo</div>
                </div>
            </div>

            <!-- UNIDAD FISCALIZACIÓN -->
             <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#fiscal"
                    onclick="mostrarInfo('Unidad Fiscalización')">
                    Unidad Fiscalización
                </button>
            <!-- <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#fiscal">Unidad Fiscalización</button> -->

                <div id="fiscal" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Jefe de Fiscalización')">Jefe de Fiscalización</div>
                    <div class="sub-box" onclick="mostrarInfo('Supervisores Fiscalización')">Supervisores Fiscalización</div>
                    <div class="sub-box" onclick="mostrarInfo('Auxiliar Fiscalización')">Auxiliar Fiscalización</div>
                    <div class="sub-box" onclick="mostrarInfo('Fiscalizaciones Control Posterior')">Fiscalizaciones Control Posterior</div>
                </div>
            </div>

            <!-- UNIDAD JURÍDICA -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#juridica"
                    onclick="mostrarInfo('Unidad Jurídica')">
                    Unidad Jurídica
                </button>

                <div id="juridica" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Jefe de Jurídica')">Jefe de Jurídica</div>
                    <div class="sub-box" onclick="mostrarInfo('Supervisor Jurídica')">Supervisor Jurídica</div>
                    <div class="sub-box" onclick="mostrarInfo('Procurador Jurídica')">Procurador Jurídica</div>
                    <div class="sub-box" onclick="mostrarInfo('Auxiliar Unidad Jurídica')">Auxiliar Unidad Jurídica</div>
                    <div class="sub-box" onclick="mostrarInfo('Cobranza Coactiva Jurídica')">Cobranza Coactiva Jurídica</div>
                    <div class="sub-box" onclick="mostrarInfo('Técnica Jurídica')">Técnica Jurídica</div>
                    <div class="sub-box" onclick="mostrarInfo('Procesos Judiciales y Administrativos Jurídica')">Procesos Judiciales y Administrativos Jurídica</div>

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
                    <div class="sub-box" onclick="mostrarInfo('Administrador Aduana Interior La Paz')">Administrador Aduana Interior La Paz</div>
                    <div class="sub-box" onclick="mostrarInfo('Supervisor Aduana Interior La Paz')">Supervisor Aduana Interior La Paz</div>
                    <div class="sub-box" onclick="mostrarInfo('Auxiliar Aduana Interior La Paz')">Auxiliar Aduana Interior La Paz</div>
                    <div class="sub-box" onclick="mostrarInfo('Archivo Aduana Interior La Paz')">Archivo Aduana Interior La Paz</div>
                    <div class="sub-box" onclick="mostrarInfo('Administrador Aduana Interior La Paz')">Administrador Aduana Interior La Paz</div>
                    <div class="sub-box" onclick="mostrarInfo('SPCC Aduana Interior La Paz')">SPCC Aduana Interior La Paz</div>
                    <div class="sub-box" onclick="mostrarInfo('Disposición de mercancías Aduana Interior La Paz')">Disposición de mercancías Aduana Interior La Paz</div>
                    <div class="sub-box" onclick="mostrarInfo('Despachos Aduana Interior La Paz')">Despachos Aduana Interior La Paz</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestión Aduana Interior La Paz')">Gestión Aduana Interior La Paz</div>
                </div>
            </div>

            <!-- GUAYARAMERIN -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#guayaramerin"
                    onclick="mostrarInfo('Administración Aduana Frontera Guayaramerín')">
                    Administración Aduana Frontera Guayaramerín
                </button>

                <div id="guayaramerin" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Administrador Guayaramerín')">Administrador Guayaramerín</div>
                    <div class="sub-box" onclick="mostrarInfo('Despachos Guayaramerín')">Secretaria Guayaramerín</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestión aduanera Guayaramerín')">Gestion Aduanera Guayaramerín</div>
                </div>
            </div>

            <!-- AEROPUERTO -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#aeropuerto"
                    onclick="mostrarInfo('Administración Aduana Aeropuerto')">
                    Administración Aduana Aeropuerto
                </button>

                <div id="aeropuerto" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Administrador Aeropuerto')">Administrador Aeropuerto</div>
                    <div class="sub-box" onclick="mostrarInfo('Supervisor Aeropuerto')">Supervisor Aeropuerto</div>
                    <div class="sub-box" onclick="mostrarInfo('Secretario Aeropuerto')">Secretario Aeropuerto</div>
                    <div class="sub-box" onclick="mostrarInfo('Archivo Aeropuerto')">Archivo Aeropuerto</div>
                    <div class="sub-box" onclick="mostrarInfo('Despachos Aeropuerto')">Despachos Aeropuerto</div>
                    <div class="sub-box" onclick="mostrarInfo('Disposición Aeropuerto')">Disposición Aeropuerto</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestión aduanera Aeropuerto')">Gestión aduanera Aeropuerto</div>
                </div>
            </div>

            <!-- PATACAMAYA -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#patacamaya"
                    onclick="mostrarInfo('Administración Aduana Zona Franca Patacamaya')">
                    Administración Aduana Zona Franca Patacamaya
                </button>

                <div id="patacamaya" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Administrador Patacamaya')">Administrador Patacamaya</div>
                    <div class="sub-box" onclick="mostrarInfo('Despachos Patacamaya')">Despachos Patacamaya</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestión aduanera Patacamaya')">Gestión aduanera Patacamaya</div>
                </div>
            </div>

            <!-- DESAGUADERO -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#desaguadero"
                    onclick="mostrarInfo('Administración Aduana Frontera Desaguadero')">
                    Administración Aduana Frontera Desaguadero
                </button>

                <div id="desaguadero" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Administrador Frontera Desaguadero')">Administrador Frontera Desaguadero</div>
                    <div class="sub-box" onclick="mostrarInfo('Secretario Frontera Desaguadero')">Secretario Frontera Desaguadero</div>
                    <div class="sub-box" onclick="mostrarInfo('Archivo Frontera Desaguadero')">Archivo Frontera Desaguadero</div>
                    <div class="sub-box" onclick="mostrarInfo('Despachos Frontera Desaguadero')">Despachos Frontera Desaguadero</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestión aduanera Frontera Desaguadero')">Gestión aduanera Frontera Desaguadero</div>
                    <div class="sub-box" onclick="mostrarInfo('Disposición Frontera Desaguadero')">Disposición Frontera Desaguadero</div>
                    <div class="sub-box" onclick="mostrarInfo('Plataforma Frontera Desaguadero')">Plataforma Frontera Desaguadero</div>
                </div>
            </div>

            <!-- COBIJA -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#cobija"
                    onclick="mostrarInfo('Administración Aduana Frontera Cobija')">
                    Administración Aduana Frontera Cobija
                </button>
                
                <div id="cobija" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Administrador Frontera Cobija')">Administrador Frontera Cobija</div>
                    <div class="sub-box" onclick="mostrarInfo('Despachos Frontera Cobija')">Despachos Frontera Cobija</div>
                    <div class="sub-box" onclick="mostrarInfo('Disposición Frontera Cobija')">Disposición Frontera Cobija</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestión aduanera Frontera Cobija')">Gestión aduanera Frontera Cobija</div>
                </div>
            </div>

            <!-- MATARANI -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#matarani"
                    onclick="mostrarInfo('Administración Agencia Aduana Exterior Matarani')">
                    Administración Agencia Aduana Exterior Matarani
                </button>

                <div id="matarani" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Administrador Exterior Mataraniones')">Administrador Exterior Mataraniones</div>
                    <div class="sub-box" onclick="mostrarInfo('Despachos Exterior Mataraniones')">Despachos Exterior Mataraniones</div>
                    <div class="sub-box" onclick="mostrarInfo('Disposición Exterior Matarani')">Disposición Exterior Matarani</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestión aduanera Exterior Matarani')">Gestión aduanera Exterior Matarani</div>
                </div>
            </div>

            <!-- CHARANA -->
            <div class="col-md-4">
                <button class="box w-100" data-bs-toggle="collapse" data-bs-target="#charana"
                    onclick="mostrarInfo('Administración Aduana Frontera Charaña')">
                    Administración Aduana Frontera Charaña
                </button>

                <div id="charana" class="collapse mt-2">
                    <div class="sub-box" onclick="mostrarInfo('Administrador aduanera Frontera Charaña')">Administrador aduanera Frontera Charaña</div>
                    <div class="sub-box" onclick="mostrarInfo('Gestión aduanera Frontera Charaña')">Gestión Frontera Charaña</div>
                    <div class="sub-box" onclick="mostrarInfo('Tránsitos Frontera Charaña')">Tránsitos Frontera Charaña</div>
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

    @push('scripts')
        <script>
            function toggleMenu() {
              const opciones = document.querySelector('.menu-burbuja .opciones');
              opciones.classList.toggle('active');
            }
        </script>
    @endpush