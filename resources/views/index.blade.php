@extends('layouts.app')

@push('styles')
<style>
    body {
        background: url('{{ asset('storage/fondo/fondo.jpg') }}') no-repeat center top fixed;
        background-size: 100% 100%;
        min-height: 100vh;
        background-attachment: fixed;
    }

    .menu-burbuja {
      position: fixed;
      bottom: 30px;
      right: 30px;
      z-index: 1000;
    }

    .menu-burbuja .boton-menu {
      width: 65px;
      height: 65px;
      background: linear-gradient(135deg, #0037ff 0%, #0564b6 100%);
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      box-shadow: 0 6px 25px rgba(0, 55, 255, 0.4);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      border: 2px solid rgba(255, 255, 255, 0.2);
      color: white;
      font-size: 26px;
    }

    .menu-burbuja .boton-menu:hover {
      transform: scale(1.15) rotate(90deg);
      box-shadow: 0 10px 35px rgba(0, 55, 255, 0.6);
      border-color: rgba(255, 255, 255, 0.4);
    }

    .menu-burbuja .opciones {
      position: absolute;
      bottom: 80px;
      right: 0;
      display: flex;
      flex-direction: column;
      gap: 18px;
      opacity: 0;
      visibility: hidden;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      transform: translateY(15px);
    }

    .menu-burbuja .opciones.active {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .menu-burbuja .opcion {
      width: 55px;
      height: 55px;
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      text-decoration: none;
      color: #0037ff;
      font-weight: bold;
      font-size: 16px;
      border: 2px solid rgba(0, 55, 255, 0.2);
    }

    .menu-burbuja .opcion:hover {
      transform: scale(1.2);
      background: linear-gradient(135deg, #0037ff 0%, #0564b6 100%);
      color: white;
      border-color: transparent;
      box-shadow: 0 8px 30px rgba(0, 55, 255, 0.5);
    }

    .menu-burbuja .opcion span {
      position: absolute;
      right: 70px;
      background: linear-gradient(135deg, #0037ff 0%, #0564b6 100%);
      color: white;
      padding: 8px 16px;
      border-radius: 25px;
      font-size: 13px;
      font-weight: 600;
      white-space: nowrap;
      opacity: 0;
      visibility: hidden;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      transform: translateX(10px);
      box-shadow: 0 5px 20px rgba(0, 55, 255, 0.3);
      letter-spacing: 0.5px;
    }

    .menu-burbuja .opcion:hover span {
      opacity: 1;
      visibility: visible;
      transform: translateX(0);
    }

    /* Estilos mejorados del organigrama */
    .box {
      background: linear-gradient(135deg, #0f2bb5 0%, #3182ce 100%);
      color: white;
      padding: 16px 24px;
      border-radius: 10px;
      font-weight: 700;
      font-size: 14px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 3px 12px rgba(15, 43, 181, 0.2);
      letter-spacing: 0.3px;
    }

    .box-gerencia {
      background: linear-gradient(135deg, #010466 0%, #0564b6 100%);
    }

    .box:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(15, 43, 181, 0.35);
      border-color: rgba(255, 255, 255, 0.25);
    }

    .box.right {
      background: linear-gradient(135deg, #0037ff 0%, #a00000 100%);
    }

    .box.left {
      background: linear-gradient(135deg, #0037ff 0%, #a00000 100%);
    }

    .sub-box {
      background: rgba(255, 255, 255, 0.95);
      color: #0f2bb5;
      padding: 10px 16px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 13px;
      margin-bottom: 6px;
      cursor: pointer;
      transition: all 0.3s ease;
      border-left: 3px solid #0f2bb5;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .sub-box:hover {
      transform: translateX(5px);
      background: white;
      border-left-color: #8B0000;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
    }

    .zigzag-container {
      display: flex;
      justify-content: center;
      gap: 16px;
      flex-wrap: wrap;
    }

    .zigzag-container .box {
      min-width: 140px;
    }

    .nivel-hijos .box {
      background: linear-gradient(135deg, #0f2bb5 0%, #3182ce 100%);
      font-size: 13px;
      padding: 14px 20px;
    }

    .nivel-hijos .box:hover {
      background: linear-gradient(135deg, #3182ce 0%, #4299e1 100%);
    }

    .nivel-tercer .box {
      background: linear-gradient(135deg, #0f2bb5 0%, #3182ce 100%);
      font-size: 12px;
      padding: 12px 16px;
    }

    .nivel-tercer .box:hover {
      background: linear-gradient(135deg, #3182ce 0%, #4299e1 100%);
    }

    hr {
      border: none;
      height: 1px;
      background: linear-gradient(90deg, transparent, #0f2bb5, transparent);
      margin: 25px 0;
      opacity: 0.4;
    }

    /* Animación de entrada */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(15px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .container > div {
      animation: fadeInUp 0.5s ease-out;
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
        <div class="box box-gerencia mb-4" onclick="mostrarInfo('GERENCIA REGIONAL LA PAZ - GRLPZ')">
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