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
        <div class="col-6 col-md-2"><div class="box" onclick="mostrarInfo('ASESORÍA')">ASESORÍA</div></div>
        <div class="col-6 col-md-2"><div class="box" onclick="mostrarInfo('SECRETARIA')">SECRETARIA</div></div>
        <div class="col-6 col-md-2"><div class="box" onclick="mostrarInfo('SISTEMAS')">SISTEMAS</div></div>
        <div class="col-6 col-md-2"><div class="box" onclick="mostrarInfo('USO')">USO</div></div>
        <div class="col-6 col-md-2"><div class="box" onclick="mostrarInfo('ARCHIVO')">ARCHIVO</div></div>
    </div>

    <hr>

    <!-- SEGUNDO NIVEL -->
    <div class="row g-3 mb-3">

        <div class="col-md-4">
            <button class="box w-100"
                onclick="mostrarInfo('Unidad Administrativa')"
                data-bs-toggle="collapse" data-bs-target="#admin">
                Unidad Administrativa
            </button>
            <div id="admin" class="collapse mt-2">
                <div class="sub-box">Contabilidad</div>
                <div class="sub-box">Activos Fijos</div>
                <div class="sub-box">Talento Humano</div>
                <div class="sub-box">Contrataciones</div>
                <div class="sub-box">Servicios Generales</div>
            </div>
        </div>

        <div class="col-md-4">
            <button class="box w-100"
                onclick="mostrarInfo('Unidad Fiscalización')"
                data-bs-toggle="collapse" data-bs-target="#fiscal">
                Unidad Fiscalización
            </button>
            <div id="fiscal" class="collapse mt-2">
                <div class="sub-box">Fiscalizaciones posteriores</div>
                <div class="sub-box">Controles diferidos</div>
            </div>
        </div>

        <div class="col-md-4">
            <button class="box w-100"
                onclick="mostrarInfo('Unidad Jurídica')"
                data-bs-toggle="collapse" data-bs-target="#juridica">
                Unidad Jurídica
            </button>
            <div id="juridica" class="collapse mt-2">
                <div class="sub-box">Cobranza coactiva</div>
                <div class="sub-box">Técnica jurídica</div>
                <div class="sub-box">Procesos administrativos</div>
            </div>
        </div>

    </div>

    <hr>

    <!-- TERCER NIVEL -->
    <div class="row g-3">

        <div class="col-md-4">
            <button class="box w-100"
                onclick="mostrarInfo('Administración Aduana Interior La Paz')"
                data-bs-toggle="collapse" data-bs-target="#interior">
                Administración Aduana Interior La Paz
            </button>
            <div id="interior" class="collapse mt-2">
                <div class="sub-box">SPCC (Comisos)</div>
                <div class="sub-box">Disposición de mercancías</div>
                <div class="sub-box">Despachos</div>
                <div class="sub-box">Gestión</div>
            </div>
        </div>

        <div class="col-md-4">
            <button class="box w-100"
                onclick="mostrarInfo('Aduana Frontera Guayaramerín')"
                data-bs-toggle="collapse" data-bs-target="#guaya">
                Aduana Frontera Guayaramerín
            </button>
            <div id="guaya" class="collapse mt-2">
                <div class="sub-box">Sin datos aún</div>
            </div>
        </div>

        <div class="col-md-4">
            <button class="box w-100"
                onclick="mostrarInfo('Aduana Aeropuerto El Alto')"
                data-bs-toggle="collapse" data-bs-target="#alto">
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

      <div class="modal-body" id="contenidoModal">
        Cargando...
      </div>

    </div>
  </div>
</div>

<!-- JS -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<script>
const datos = {
    "GERENCIA REGIONAL LA PAZ - GRLPZ": {
        items: 10,
        acefalias: 1,
        personal: ["Gerente Regional", "Asistente"]
    },
    "ASESORÍA": {
        items: 2,
        acefalias: 0,
        personal: ["Asesor Legal", "Asesor Técnico"]
    },
    "SECRETARIA": {
        items: 1,
        acefalias: 0,
        personal: ["Secretaria"]
    },
    "SISTEMAS": {
        items: 3,
        acefalias: 1,
        personal: ["Soporte", "Dev", "Redes"]
    },
    "USO": {
        items: 2,
        acefalias: 0,
        personal: ["Usuarios"]
    },
    "ARCHIVO": {
        items: 2,
        acefalias: 0,
        personal: ["Archivista"]
    },
    "Unidad Administrativa": {
        items: 8,
        acefalias: 2,
        personal: ["Administrador", "Contador"]
    },
    "Unidad Fiscalización": {
        items: 5,
        acefalias: 1,
        personal: ["Fiscal 1", "Fiscal 2"]
    },
    "Unidad Jurídica": {
        items: 4,
        acefalias: 1,
        personal: ["Abogado 1", "Abogado 2"]
    },
    "Administración Aduana Interior La Paz": {
        items: 6,
        acefalias: 0,
        personal: ["Equipo Interior"]
    },
    "Aduana Frontera Guayaramerín": {
        items: 3,
        acefalias: 1,
        personal: ["Frontera"]
    },
    "Aduana Aeropuerto El Alto": {
        items: 4,
        acefalias: 0,
        personal: ["Aeropuerto"]
    }
};

function mostrarInfo(area) {

    let data = datos[area];

    let html = data ? `
        <div class="text-center">
            <div class="bg-primary text-white p-2 rounded mb-3">
                <b>${area}</b>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6>Total Items</h6>
                            <span class="badge bg-success fs-6">${data.items}</span>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6>Acefalías</h6>
                            <span class="badge bg-danger fs-6">${data.acefalias}</span>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <h6>👥 Personal</h6>

            <ul class="list-group">
                ${data.personal.map(p => `<li class="list-group-item">${p}</li>`).join('')}
            </ul>
        </div>
    ` : "<p class='text-center'>Sin datos</p>";

    document.getElementById("tituloModal").innerText = area;
    document.getElementById("contenidoModal").innerHTML = html;

    new bootstrap.Modal(document.getElementById('modalInfo')).show();
}
</script>

</body>
</html>