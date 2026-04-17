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
<div class="box mb-4">
GERENCIA REGIONAL LA PAZ - GRLPZ
</div>

<!-- PRIMER NIVEL -->
<div class="row justify-content-center">

<div class="col-md-2"><div class="box">ASESORÍA</div></div>
<div class="col-md-2"><div class="box">SECRETARIA</div></div>
<div class="col-md-2"><div class="box">SISTEMAS</div></div>
<div class="col-md-2"><div class="box">USO</div></div>
<div class="col-md-2"><div class="box">ARCHIVO</div></div>

</div>

<hr>

<!-- SEGUNDO NIVEL -->
<div class="row">

<div class="col-md-4">
<button class="box w-100" data-bs-toggle="collapse" data-bs-target="#admin">
Unidad Administrativa
</button>
<div id="admin" class="collapse">
<div class="sub-box">Contabilidad</div>
<div class="sub-box">Activos Fijos</div>
<div class="sub-box">Talento Humano</div>
<div class="sub-box">Contrataciones</div>
<div class="sub-box">Servicios Generales</div>
</div>
</div>

<div class="col-md-4">
<button class="box w-100" data-bs-toggle="collapse" data-bs-target="#fiscal">
Unidad Fiscalización
</button>
<div id="fiscal" class="collapse">
<div class="sub-box">Controles diferidos</div>
<div class="sub-box">Fiscalizaciones posteriores</div>
</div>
</div>

<div class="col-md-4">
<button class="box w-100" data-bs-toggle="collapse" data-bs-target="#juridica">
Unidad Jurídica
</button>
<div id="juridica" class="collapse">
<div class="sub-box">Cobranza coactiva</div>
<div class="sub-box">Procesos administrativos</div>
<div class="sub-box">Técnica jurídica</div>
</div>
</div>

</div>

<hr>

<!-- TERCER NIVEL -->
<div class="row justify-content-center">

<div class="col-md-3 mt-2"><div class="sub-box">ADMINISTRACIÓN ADUANA INTERIOR LA PAZ</div></div>
<div class="col-md-3 mt-2"><div class="sub-box">ADMINISTRACIÓN ADUANA FRONTERA GUAYARAMERIN</div></div>
<div class="col-md-3 mt-2"><div class="sub-box">ADMINISTRACIÓN ADUANA AEROPUERTO EL ALTO</div></div>
<div class="col-md-3 mt-2"><div class="sub-box">ADMINISTRACIÓN ADUANA ZONA FRANCA INDUSTRIAL PATACAMAYA</div></div>

<div class="col-md-3 mt-2"><div class="sub-box">ADMINISTRACIÓN ADUANA FRONTERA DESAGUADERO</div></div>
<div class="col-md-3 mt-2"><div class="sub-box">ADMINISTRACIÓN ADUANA ZONA FRANCA COMERCIAL FRONTERA COBIJA</div></div>
<div class="col-md-3 mt-2"><div class="sub-box">AGENCIA ADUANA EXTERIOR MATARANI</div></div>
<div class="col-md-3 mt-2"><div class="sub-box">ADMINISTRACIÓN ADUANA FRONTERA CHARANA</div></div>

</div>

</div>

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>