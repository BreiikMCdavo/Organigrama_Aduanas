/**
 * Controlador principal
 * Coordina el formulario manual, la importaciOn desde Excel,
 * la validaciOn, la vista previa, la impresion y la descarga PDF
 */

// Constantes

const GERENCIA_CON_UNIDAD = 'GERENCIA REGIONAL LA PAZ';

// Campos requeridos por gafete junto a su mensaje de error
const CAMPOS_REQUERIDOS = [
  { campo: 'nombre',  mensaje: 'El nombre es obligatorio.'            },
  { campo: 'cargo',   mensaje: 'El cargo es obligatorio.'             },
  { campo: 'gerencia',mensaje: 'Selecciona una gerencia.'             },
  { campo: 'interno', mensaje: 'El número interno es obligatorio.'    },
];

// Utilidades de DOM

// Devuelve el valor recortado de un input o select por su id
function valorDe(id) {
  const el = document.getElementById(id);
  return el ? el.value.trim() : '';
}

// Muestra el area de vista previa y hace scroll hacia ella
function mostrarVistaPrevia() {
  const area = document.getElementById('preview-area');
  area.style.display = 'block';
  area.scrollIntoView({ behavior: 'smooth' });
}

// Oculta el área de vista previa
function hidePreviewArea() {
  document.getElementById('preview-area').style.display = 'none';
}

// Elimina todas las hojas generadas del area de vista previa
function limpiarHojas() {
  document.querySelectorAll('#preview-area .sheet').forEach(el => el.remove());
}

// Inserta el HTML de hojas en el area de vista previa
function insertarHojas(htmlHojas) {
  limpiarHojas();
  document.querySelector('#preview-area > .btn-row')
    .insertAdjacentHTML('afterend', htmlHojas);
}

// Formulario manual: Gerencia / Unidad

/**
 * Muestra u oculta el campo Unidad según la gerencia elegida.
 * @param {1|2} num
 */
function onGerenciaChange(num) {
  const gerencia    = valorDe(`g${num}-gerencia`);
  const filaUnidad  = document.getElementById(`g${num}-unidad-row`);
  const inputUnidad = document.getElementById(`g${num}-unidad`);

  const esGerenciaRegional = gerencia === GERENCIA_CON_UNIDAD;
  filaUnidad.style.display = esGerenciaRegional ? '' : 'none';
  if (!esGerenciaRegional) inputUnidad.value = '';

  limpiarErrorCampo(`g${num}-gerencia`);
}

/**
 * Limpia el error del campo activo.
 * @param {1|2} num  — no usado directamente, el campo activo se detecta solo
 */
function onFieldInput() {
  const activo = document.activeElement;
  if (activo) limpiarErrorCampo(activo.id);
}

/**
 * Construye el string de gerencia/unidad para el gafete del formulario
 * @param {1|2} num
 * @returns {string}
 */
function construirGerenciaManual(num) {
  const gerencia = valorDe(`g${num}-gerencia`) || GERENCIA_CON_UNIDAD;
  const unidad   = valorDe(`g${num}-unidad`);

  return gerencia === GERENCIA_CON_UNIDAD && unidad
    ? `${gerencia} - ${unidad}`
    : gerencia;
}

// Validacion

/**
 * Indica si un gafete tiene al menos un campo con dato
 * @param {1|2} num
 * @returns {boolean}
 */
function gafeteEstaTocado(num) {
  return ['nombre', 'cargo', 'gerencia']
    .some(campo => valorDe(`g${num}-${campo}`) !== '');
}

/**
 * Valida todos los campos requeridos de un gafete
 * @param {1|2} num
 * @returns {true|false|null}
 *   true  → válido y completo
 *   false → tocado pero con errores
 *   null  → vacio, se ignora
 */
function validarGafete(num) {
  if (!gafeteEstaTocado(num)) {
    limpiarErroresGafete(num);
    return null;
  }

  // Construir la lista: fijos + Unidad si aplica
  const checks = [...CAMPOS_REQUERIDOS];
  if (valorDe(`g${num}-gerencia`) === GERENCIA_CON_UNIDAD) {
    checks.push({ campo: 'unidad', mensaje: 'La unidad es obligatoria para Gerencia Regional.' });
  } else {
    limpiarErrorCampo(`g${num}-unidad`);
  }

  let valido = true;
  checks.forEach(({ campo, mensaje }) => {
    const id = `g${num}-${campo}`;
    if (!valorDe(id)) {
      marcarErrorCampo(id, mensaje);
      valido = false;
    } else {
      limpiarErrorCampo(id);
    }
  });

  return valido;
}

// Marca un campo con borde rojo y muestra su mensaje de error
function marcarErrorCampo(idCampo, mensaje) {
  document.getElementById(idCampo)?.classList.add('input-error');
  const err = document.getElementById(`${idCampo}-err`);
  if (err) err.textContent = mensaje;
}

// Elimina el error visual de un campo
function limpiarErrorCampo(idCampo) {
  document.getElementById(idCampo)?.classList.remove('input-error');
  const err = document.getElementById(`${idCampo}-err`);
  if (err) err.textContent = '';
}
// Limpia todos los errores de un gafete
function limpiarErroresGafete(num) {
  ['nombre', 'cargo', 'gerencia', 'unidad', 'interno'].forEach(campo => {
    limpiarErrorCampo(`g${num}-${campo}`);
  });
}

// Formulario manual: acciones

// Valida y genera la vista previa de los gafetes del formulario
function generarVista() {
  const resultado1 = validarGafete(1);
  const resultado2 = validarGafete(2);

  if (resultado1 === null && resultado2 === null) {
    marcarErrorCampo('g1-nombre', 'Completa al menos un gafete.');
    return;
  }
  if (resultado1 === false || resultado2 === false) return;

  const funcionarios = [];
  if (resultado1 === true) funcionarios.push(leerDatosGafete(1));
  if (resultado2 === true) funcionarios.push(leerDatosGafete(2));

  insertarHojas(ConstructorGafetes.construirTodasLasHojas(funcionarios));
  mostrarVistaPrevia();
}

// Valida e imprime los gafetes del formulario
function imprimir() {
  const resultado1 = validarGafete(1);
  const resultado2 = validarGafete(2);

  if (resultado1 === null && resultado2 === null) {
    marcarErrorCampo('g1-nombre', 'Completa al menos un gafete.');
    return;
  }
  if (resultado1 === false || resultado2 === false) return;

  generarVista();
  setTimeout(() => window.print(), 350);
}

/**
 * Lee los datos de un gafete del formulario y devuelve el gafete del funcionario
 * @param {1|2} num
 * @returns {{ nombre:string, cargo:string, unidad:string, interno:string }}
 */
function leerDatosGafete(num) {
  return {
    nombre:  valorDe(`g${num}-nombre`),
    cargo:   valorDe(`g${num}-cargo`),
    unidad:  construirGerenciaManual(num),
    interno: valorDe(`g${num}-interno`) || '0000',
  };
}

// Limpia el formulario manual completo
function limpiar() {
  ['nombre', 'cargo', 'unidad', 'interno'].forEach(campo => {
    [1, 2].forEach(num => {
      const el = document.getElementById(`g${num}-${campo}`);
      if (el) el.value = '';
    });
  });

  [1, 2].forEach(num => {
    const select = document.getElementById(`g${num}-gerencia`);
    if (select) select.value = '';
    const filaUnidad = document.getElementById(`g${num}-unidad-row`);
    if (filaUnidad) filaUnidad.style.display = 'none';
    limpiarErroresGafete(num);
  });

  hidePreviewArea();
}

// Importación desde Excel

// Funcionarios cargados del ultimo Excel importado
let funcionariosImportados = [];

/**
 * Manejador del input de archivo Excel
 * @param {Event} evento
 */
async function onExcelSelected(evento) {
  const archivo = evento.target.files[0];
  if (!archivo) return;

  const elEstado   = document.getElementById('excel-status');
  const elPreview  = document.getElementById('excel-preview');
  const elNombre   = document.getElementById('excel-filename');
  const elAcciones = document.getElementById('excel-actions');

  elNombre.textContent = archivo.name;
  actualizarEstadoExcel(elEstado, 'loading', '⏳ Leyendo archivo…');
  elPreview.innerHTML  = '';
  elAcciones.style.display = 'none';

  try {
    const funcionarios = await LectorExcel.leerArchivo(archivo);

    if (funcionarios.length === 0) {
      actualizarEstadoExcel(elEstado, 'error', '⚠️ El archivo no contiene datos.');
      return;
    }

    funcionariosImportados = funcionarios;
    actualizarContadorExcel(funcionarios.length);
    actualizarEstadoExcel(elEstado, 'success',
      `✅ ${funcionarios.length} funcionario(s) importado(s) correctamente.`
    );

    renderizarTablaPrevia(elPreview, funcionarios);
    elAcciones.style.display = 'flex';

  } catch (error) {
    actualizarEstadoExcel(elEstado, 'error', `❌ ${error.message}`);
    elPreview.innerHTML = `<div class="excel-error-detail">${escaparHTML(error.message)}</div>`;
    console.error('[LectorExcel]', error);
  }
}

/**
 * Actualiza el badge de estado del area Excel
 * @param {HTMLElement} el
 * @param {'loading'|'success'|'error'|''} tipo
 * @param {string} mensaje
 */
function actualizarEstadoExcel(el, tipo, mensaje) {
  el.textContent = mensaje;
  el.className   = `excel-status excel-status--${tipo}`;
}

/**
 * Actualiza el contador de gafetes del Excel
 * @param {number} total
 */
function actualizarContadorExcel(total) {
  const num   = document.getElementById('excel-counter-num');
  const badge = document.getElementById('excel-counter');
  if (!num || !badge) return;

  num.textContent = total;
  badge.className = `gafete-counter gafete-counter--${total > 0 ? 'full' : 'empty'}`;
}

/**
 * Renderiza la tabla de previsualización
 * @param {HTMLElement} contenedor
 * @param {Array} funcionarios
 */
function renderizarTablaPrevia(contenedor, funcionarios) {
  const MAX_FILAS   = 5;
  const visibles    = funcionarios.slice(0, MAX_FILAS);
  const restantes   = funcionarios.length - visibles.length;

  const filas = visibles.map(f => `
    <tr>
      <td>${escaparHTML(f.nombre)}</td>
      <td>${escaparHTML(f.cargo)}</td>
      <td>${escaparHTML(f.unidad)}</td>
      <td>${escaparHTML(f.interno)}</td>
    </tr>`).join('');

  const nota = restantes > 0
    ? `<p class="excel-table-note">… y ${restantes} funcionario(s) más.</p>`
    : '';

  contenedor.innerHTML = `
    <table class="excel-table">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Cargo</th>
          <th>Gerencia / Unidad</th>
          <th>Interno</th>
        </tr>
      </thead>
      <tbody>${filas}</tbody>
    </table>
    ${nota}`;
}

// Genera la vista previa de todos los gafetes importados desde Excel
function generarDesdeExcel() {
  if (funcionariosImportados.length === 0) return;
  insertarHojas(ConstructorGafetes.construirTodasLasHojas(funcionariosImportados));
  mostrarVistaPrevia();
}

// Imprime todos los gafetes importados desde Excel
function imprimirDesdeExcel() {
  generarDesdeExcel();
  setTimeout(() => window.print(), 400);
}

// Resetea la seccion de importacion Excel a su estado inicial
function limpiarImportacion() {
  funcionariosImportados = [];

  const elArchivo  = document.getElementById('excel-file');
  const elEstado   = document.getElementById('excel-status');
  const elPreview  = document.getElementById('excel-preview');
  const elNombre   = document.getElementById('excel-filename');
  const elAcciones = document.getElementById('excel-actions');

  if (elArchivo) elArchivo.value = '';
  actualizarEstadoExcel(elEstado, '', '');
  elPreview.innerHTML      = '';
  elNombre.textContent     = 'Ningún archivo seleccionado';
  elAcciones.style.display = 'none';
  actualizarContadorExcel(0);
  hidePreviewArea();
}

// Descarga PDF

// Descarga todas las hojas visibles como un archivo PDF
async function descargarPDF() {
  const hojas = document.querySelectorAll('#preview-area .sheet');
  if (hojas.length === 0) return;

  const { jsPDF } = window.jspdf;
  const pdf = new jsPDF({ unit: 'cm', format: [21.59, 27.94], orientation: 'portrait' });

  for (let i = 0; i < hojas.length; i++) {
    if (i > 0) pdf.addPage();

    const canvas = await html2canvas(hojas[i], {
      scale:           3,
      useCORS:         true,
      allowTaint:      true,
      backgroundColor: '#ffffff',
      width:           hojas[i].offsetWidth,
      height:          hojas[i].offsetHeight,
    });

    pdf.addImage(canvas.toDataURL('image/jpeg', 1.0), 'JPEG', 0, 0, 21.59, 27.94);
  }

  pdf.save('gafetes.pdf');
}

// Utilidades

// Escapa caracteres especiales HTML
function escaparHTML(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
}

// Inicializacion

window.addEventListener('load', () => {
  // Tooltip: clic para fijar, clic fuera para cerrar
  document.querySelectorAll('.info-tooltip').forEach(tooltip => {
    tooltip.addEventListener('click', e => {
      e.stopPropagation();
      tooltip.classList.toggle('open');
    });
  });

  document.addEventListener('click', () => {
    document.querySelectorAll('.info-tooltip.open')
      .forEach(t => t.classList.remove('open'));
  });
});
