<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gafetes - Aduana Nacional</title>
  <link rel="icon" type="image/png" href="{{ asset('img/favicon.ico') }}"/>
  <link rel="stylesheet" href="{{ asset('css/gafetes.css') }}"/>
</head>
<body>

<!-- ENCABEZADO -->
<div class="page-header">
  <div class="logo-wrap">
    <img src="{{ asset('img/logo_aduana.png') }}" alt="Aduana Nacional" class="logo-aduana"/>
  </div>
  <div class="header-text">
    <h1>Gafetes — Aduana Nacional</h1>
    <p class="subtitle">Sistema de Impresión</p>
  </div>
</div>

<!-- SECCION: IMPORTAR DESDE EXCEL -->
<div class="form-container">

  <div class="section-header-row">
    <h2 class="section-title section-title--excel">
      Importar desde Excel
      <span class="info-tooltip">
        <span class="info-icon">ℹ</span>
        <span class="info-bubble">
          Las columnas requeridas son <strong>NOMBRE</strong>, <strong>CARGO</strong>
          e <strong>INTERNO</strong>.<br/>
          La columna <strong>UNIDAD</strong> es opcional:<br/>
          • Con UNIDAD → Gerencia Regional La Paz<br/>
          • Sin UNIDAD → Administración Aduana Interior La Paz
        </span>
      </span>
    </h2>
    <div class="gafete-counter gafete-counter--empty" id="excel-counter">
      <span class="counter-num" id="excel-counter-num">0</span>
      <span class="counter-label">gafete(s)</span>
    </div>
  </div>

  <p class="excel-instructions">
    Carga un archivo <strong>.xlsx</strong> o <strong>.xls</strong> —
    haz clic en <strong>ℹ</strong> para ver el formato requerido.
  </p>

  <input type="file" id="excel-file" accept=".xlsx,.xls"
         onchange="onExcelSelected(event)" style="display:none"/>

  <div class="btn-row" style="justify-content:flex-start; margin-top:0; margin-bottom:16px;">
    <button class="btn-excel" onclick="document.getElementById('excel-file').click()">
      📂 Seleccionar archivo Excel
    </button>
    <span id="excel-filename" class="excel-filename">Ningún archivo seleccionado</span>
  </div>

  <p id="excel-status" class="excel-status"></p>
  <div id="excel-preview"></div>

  <div id="excel-actions" class="btn-row" style="display:none">
    <button class="btn-preview" onclick="generarDesdeExcel()">👁 Vista previa</button>
    <button class="btn-print"   onclick="imprimirDesdeExcel()">🖨 Imprimir todo</button>
    <button class="btn-clear"   onclick="limpiarImportacion()">🗑 Limpiar</button>
  </div>

</div>

<!-- SECCION: FORMULARIO MANUAL -->
<div class="form-container">

  <h2 class="section-title">Formulario Manual</h2>

  <!-- Gafete 1 -->
  <div class="gafete-block" id="block-g1">
    <h3>Gafete 1</h3>
    <div class="fields-grid">

      <div class="field-full">
        <label for="g1-nombre">Nombre completo <span class="req">*</span></label>
        <input type="text" id="g1-nombre" placeholder="Nombre Apellido"
               oninput="onFieldInput()"/>
        <span class="field-error" id="g1-nombre-err"></span>
      </div>

      <div class="field-full">
        <label for="g1-cargo">Cargo <span class="req">*</span></label>
        <input type="text" id="g1-cargo" placeholder="Cargo"
               oninput="onFieldInput()"/>
        <span class="field-error" id="g1-cargo-err"></span>
      </div>

      <div class="field-full">
        <label for="g1-gerencia">Gerencia <span class="req">*</span></label>
        <select id="g1-gerencia" onchange="onGerenciaChange(1)">
          <option value="">— Seleccionar —</option>
          <option value="GERENCIA REGIONAL LA PAZ">GERENCIA REGIONAL LA PAZ</option>
          <option value="ADMINISTRACIÓN ADUANA INTERIOR LA PAZ">ADMINISTRACIÓN ADUANA INTERIOR LA PAZ</option>
        </select>
        <span class="field-error" id="g1-gerencia-err"></span>
      </div>

      <div class="field-full" id="g1-unidad-row" style="display:none">
        <label for="g1-unidad">Unidad <span class="req">*</span></label>
        <input type="text" id="g1-unidad" placeholder="Ej: Unidad de Fiscalización"
               oninput="onFieldInput()"/>
        <span class="field-error" id="g1-unidad-err"></span>
      </div>

      <div>
        <label for="g1-interno">Número interno <span class="req">*</span></label>
        <input type="text" id="g1-interno" placeholder="0000" maxlength="4"
               oninput="this.value=this.value.replace(/\D/g,'').slice(0,4); onFieldInput()"/>
        <span class="field-error" id="g1-interno-err"></span>
      </div>

    </div>
  </div>

  <!-- Gafete 2 -->
  <div class="gafete-block" id="block-g2">
    <h3>Gafete 2</h3>
    <div class="fields-grid">

      <div class="field-full">
        <label for="g2-nombre">Nombre completo <span class="req">*</span></label>
        <input type="text" id="g2-nombre" placeholder="Nombre Apellido"
               oninput="onFieldInput()"/>
        <span class="field-error" id="g2-nombre-err"></span>
      </div>

      <div class="field-full">
        <label for="g2-cargo">Cargo <span class="req">*</span></label>
        <input type="text" id="g2-cargo" placeholder="Cargo"
               oninput="onFieldInput()"/>
        <span class="field-error" id="g2-cargo-err"></span>
      </div>

      <div class="field-full">
        <label for="g2-gerencia">Gerencia <span class="req">*</span></label>
        <select id="g2-gerencia" onchange="onGerenciaChange(2)">
          <option value="">— Seleccionar —</option>
          <option value="GERENCIA REGIONAL LA PAZ">GERENCIA REGIONAL LA PAZ</option>
          <option value="ADMINISTRACIÓN ADUANA INTERIOR LA PAZ">ADMINISTRACIÓN ADUANA INTERIOR LA PAZ</option>
        </select>
        <span class="field-error" id="g2-gerencia-err"></span>
      </div>

      <div class="field-full" id="g2-unidad-row" style="display:none">
        <label for="g2-unidad">Unidad <span class="req">*</span></label>
        <input type="text" id="g2-unidad" placeholder="Ej: Unidad de Fiscalización"
               oninput="onFieldInput()"/>
        <span class="field-error" id="g2-unidad-err"></span>
      </div>

      <div>
        <label for="g2-interno">Número interno <span class="req">*</span></label>
        <input type="text" id="g2-interno" placeholder="0000" maxlength="4"
               oninput="this.value=this.value.replace(/\D/g,'').slice(0,4); onFieldInput()"/>
        <span class="field-error" id="g2-interno-err"></span>
      </div>

    </div>
  </div>

  <div class="btn-row">
    <button class="btn-clear"   onclick="limpiar()">🗑 Limpiar</button>
    <button class="btn-preview" onclick="generarVista()">👁 Vista previa</button>
    <button class="btn-print"   onclick="imprimir()">🖨 Imprimir</button>
  </div>

</div>

<!-- AREA DE IMPRESION -->
<div id="preview-area">
  <div class="btn-row">
    <button class="btn-print"    onclick="window.print()">🖨 Imprimir ahora</button>
    <button class="btn-download" onclick="descargarPDF()">⬇ Descargar PDF</button>
    <button class="btn-clear"    onclick="hidePreviewArea()">✕ Cerrar</button>
  </div>
  <!-- Las hojas se insertan aqui dinamicamente -->
</div>

<!-- LIBRERIAS -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- MODULOS -->
<script src="{{ asset('js/gafetes/constructor_gafetes.js') }}"></script>
<script src="{{ asset('js/gafetes/lector_excel.js') }}"></script>
<script src="{{ asset('js/gafetes/app.js') }}"></script>

</body>
</html>
