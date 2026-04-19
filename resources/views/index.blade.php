<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Organigrama Aduana</title>

<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/organigrama.css') }}">


</head>

<body>

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
            <button class="box w-100" onclick="mostrarInfo('Unidad Administrativa')" data-bs-toggle="collapse" data-bs-target="#admin">
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
            <button class="box w-100" onclick="mostrarInfo('Unidad Fiscalización')" data-bs-toggle="collapse" data-bs-target="#fiscal">
                Unidad Fiscalización
            </button>
            <div id="fiscal" class="collapse mt-2">
                <div class="sub-box" onclick="mostrarInfo('Fiscalizaciones posteriores')">Fiscalizaciones posteriores</div>
                <div class="sub-box" onclick="mostrarInfo('Controles diferidos')">Controles diferidos</div>
            </div>
        </div>

        <div class="col-md-4">
            <button class="box w-100" onclick="mostrarInfo('Unidad Jurídica')" data-bs-toggle="collapse" data-bs-target="#juridica">
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
            <button class="box w-100" onclick="mostrarInfo('Administración Aduana Interior La Paz')" data-bs-toggle="collapse" data-bs-target="#interior">
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
            <button class="box w-100" onclick="mostrarInfo('Aduana Frontera Guayaramerín')" data-bs-toggle="collapse" data-bs-target="#guaya">
                Aduana Frontera Guayaramerín
            </button>
            <div id="guaya" class="collapse mt-2">
                <div class="sub-box">Sin datos aún</div>
            </div>
        </div>

        <div class="col-md-4">
            <button class="box w-100" onclick="mostrarInfo('Aduana Aeropuerto El Alto')" data-bs-toggle="collapse" data-bs-target="#alto">
                Aduana Aeropuerto El Alto
            </button>
            <div id="alto" class="collapse mt-2">
                <div class="sub-box">Sin datos aún</div>
            </div>
        </div>

    </div>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalInfo" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 id="tituloModal">Área</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="contenidoModal"></div>

    </div>
  </div>
</div>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/organigrama.js') }}"></script>


</body>
</html>
