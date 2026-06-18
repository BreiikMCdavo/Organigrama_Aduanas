/**
 * Construye el HTML de cada gafete y organiza las hojas de impresion.
 * Cada hoja carta contiene 2 gafetes.
 */

const ConstructorGafetes = (() => {

  // Constantes
  const GAFETES_POR_HOJA  = 2;
  const TELEFONO_FIJO     = ': (591) - 2 - 2128008';

  // Utilidades internas

  /** Escapa caracteres especiales HTML para prevenir XSS */
  function escaparHTML(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
  }

  // Constructores de HTML

  /**
   * Genera el HTML de la franja inferior con teléfono e interno
   * @param {string} unidad   - Nombre de la gerencia/unidad
   * @param {string} interno  - Numero interno
   * @returns {string}
   */
  function construirFranja(unidad, interno) {
    const texto = escaparHTML(unidad) + TELEFONO_FIJO +
                  (interno ? ` / Int. ${escaparHTML(interno)}` : '');

    return `
      <img class="franja-bg" src="img/franja_inferior.png" alt=""/>
      <div class="franja-datos">
        <span class="franja-tel">${texto}</span>
      </div>`;
  }

  /**
   * Genera el HTML del bloque de texto (nombre + cargo)
   * @param {string} nombre
   * @param {string} cargo
   * @returns {string}
   */
  function construirTexto(nombre, cargo) {
    return `
      <div class="gafete-nombre">${escaparHTML(nombre)}</div>
      <div class="gafete-cargo">${escaparHTML(cargo)}</div>`;
  }

  /**
   * Genera el HTML completo de un gafete individual
   * @param {{ nombre:string, cargo:string, unidad:string, interno:string }} funcionario
   * @returns {string}
   */
  function construirGafete({ nombre, cargo, unidad, interno }) {
    const franja = construirFranja(unidad, interno);
    const texto  = construirTexto(nombre, cargo);

    return `
    <div class="gafete-wrapper">
      <img class="bg" src="img/fondo_gafete.png" alt=""/>
      <div class="gafete-text-inv">${texto}</div>
      <div class="gafete-franja-inv">${franja}</div>
      <div class="gafete-text">${texto}</div>
      <div class="gafete-franja">${franja}</div>
    </div>`;
  }

  /**
   * Genera el HTML de una hoja carta con 1 o 2 gafetes.
   * @param {Array}  par         - Array de hasta 2 funcionarios
   * @param {number} numeroHoja  - Índice de la hoja (para el id)
   * @returns {string}
   */
  function construirHoja(par, numeroHoja) {
    const contenido = par.map(construirGafete).join('');
    return `<div class="sheet" id="sheet-${numeroHoja}">${contenido}</div>`;
  }

  // ── API pública ──────────────────────────────────────────────────────────────

  /**
   * Genera todas las hojas necesarias para una lista de funcionarios.
   * Agrupa de a 2 por hoja carta.
   *
   * @param {{ nombre:string, cargo:string, unidad:string, interno:string }[]} funcionarios
   * @returns {string} HTML listo para insertar en el área de impresión
   */
  function construirTodasLasHojas(funcionarios) {
    let html = '';

    for (let i = 0; i < funcionarios.length; i += GAFETES_POR_HOJA) {
      const par        = funcionarios.slice(i, i + GAFETES_POR_HOJA);
      const numeroHoja = Math.floor(i / GAFETES_POR_HOJA) + 1;
      html += construirHoja(par, numeroHoja);
    }

    return html;
  }

  return { construirGafete, construirTodasLasHojas };

})();
