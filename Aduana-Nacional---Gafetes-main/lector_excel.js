/**
 * Lee archivos .xlsx y .xls y devuelve un array de funcionarios listos para imprimir
 * Reglas:
 *   - Si el archivo tiene columna UNIDAD  → "Gerencia Regional La Paz - <unidad>"
 *   - Si el archivo NO tiene columna UNIDAD → "Administracion Aduana Interior La Paz"
 */

const LectorExcel = (() => {

  // Constantes

  const GERENCIA_REGIONAL   = 'GERENCIA REGIONAL LA PAZ';
  const ADMINISTRACION      = 'ADMINISTRACIÓN ADUANA INTERIOR LA PAZ';

  
  // Palabras clave para detectar cada columna del Excel
  
  const PALABRAS_CLAVE = {
    nombre:  ['nombre', 'name'],
    cargo:   ['cargo', 'puesto', 'position'],
    unidad:  ['unidad', 'oficina', 'dependencia'],
    interno: ['interno', 'extension', 'int'],
  };

  // Utilidades internas

  // Convierte un string a minusculas sin tildes ni espacios dobles

  function normalizar(str) {
    return String(str)
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .replace(/\s+/g, ' ')
      .trim();
  }

  /**
   * Busca el indice de la columna que coincide con las palabras clave del campo
   * Devuelve -1 si no se encuentra
   */
  function buscarColumna(encabezados, campo) {
    const palabras = PALABRAS_CLAVE[campo];
    return encabezados.findIndex(h => {
      const norm = normalizar(h);
      return palabras.some(p => norm.includes(p));
    });
  }

  // Parseo de filas

  /**
   * Lanza un error descriptivo si faltan columnas requeridas
   * @param {any[][]} filas
   * @returns {{ nombre:string, cargo:string, unidad:string, interno:string }[]}
   */
  function parsearFilas(filas) {
    if (!filas || filas.length < 2) {
      throw new Error('El archivo está vacío o no tiene datos suficientes.');
    }

    // Encontrar la primera fila no vacia (los encabezados)
    const indiceEncabezados = filas.findIndex(f => f.some(c => String(c).trim() !== ''));
    if (indiceEncabezados === -1) {
      throw new Error('El archivo no contiene datos.');
    }

    const encabezados = filas[indiceEncabezados];

    // Validar columnas requeridas
    const columnas = {
      nombre:  buscarColumna(encabezados, 'nombre'),
      cargo:   buscarColumna(encabezados, 'cargo'),
      interno: buscarColumna(encabezados, 'interno'),
    };

    const faltantes = Object.entries(columnas)
      .filter(([, idx]) => idx === -1)
      .map(([campo]) => campo.toUpperCase());

    if (faltantes.length > 0) {
      const detectadas = encabezados
        .filter(h => String(h).trim())
        .map(h => `"${h}"`)
        .join(', ');
      throw new Error(
        `Faltan las columnas: ${faltantes.join(', ')}.\n` +
        `Columnas detectadas en el archivo: ${detectadas}`
      );
    }

    // Columna opcional UNIDAD — determina que gerencia se asigna
    const colUnidad   = buscarColumna(encabezados, 'unidad');
    const tieneUnidad = colUnidad !== -1;

    // Construir gafetes de funcionario
    return filas
      .slice(indiceEncabezados + 1)
      .filter(fila => String(fila[columnas.nombre] ?? '').trim() !== '')
      .map(fila => {
        const unidadRaw = tieneUnidad
          ? String(fila[colUnidad] ?? '').trim()
          : '';

        const gerencia = tieneUnidad && unidadRaw
          ? `${GERENCIA_REGIONAL} - ${unidadRaw}`
          : ADMINISTRACION;

        return {
          nombre:  String(fila[columnas.nombre]  ?? '').trim(),
          cargo:   String(fila[columnas.cargo]   ?? '').trim(),
          unidad:  gerencia,
          interno: String(fila[columnas.interno] ?? '').trim(),
        };
      });
  }

  // API publica 

  /**
   * Lee un archivo Excel y, cuando termina, entrega el array de funcionarios
   * @param {File} archivo
   * @returns {Promise<{ nombre:string, cargo:string, unidad:string, interno:string }[]>}
   */
  function leerArchivo(archivo) {
    return new Promise((resolve, reject) => {
      if (typeof XLSX === 'undefined') {
        reject(new Error(
          'La librería de Excel no cargó correctamente. ' +
          'Verifica tu conexión a internet y recarga la página.'
        ));
        return;
      }

      const lector = new FileReader();

      lector.onload = (evento) => {
        try {
          const datos    = new Uint8Array(evento.target.result);
          const libro    = XLSX.read(datos, { type: 'array' });

          if (!libro.SheetNames.length) {
            reject(new Error('El archivo no contiene hojas.'));
            return;
          }

          const hoja  = libro.Sheets[libro.SheetNames[0]];
          const filas = XLSX.utils.sheet_to_json(hoja, { header: 1, defval: '' });

          resolve(parsearFilas(filas));
        } catch {
          reject(new Error('No se pudo leer el archivo. Asegúrate de que sea un Excel válido.'));
        }
      };

      lector.onerror = () => reject(new Error('Error al leer el archivo.'));
      lector.readAsArrayBuffer(archivo);
    });
  }

  return { leerArchivo };

})();
