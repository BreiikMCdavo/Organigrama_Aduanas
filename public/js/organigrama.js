function mostrarInfo(area){

    fetch(`/organigrama/${encodeURIComponent(area)}`)
        .then(res => res.json())
        .then(data => {

            let html;

            // Si es GERENCIA, mostrar el dashboard completo
            if (area === 'GERENCIA REGIONAL LA PAZ - GRLPZ') {
                const totalItems = data.items || 0;
                const totalAcefalias = data.acefalias || 0;
                const totalInamoviles = data.inamoviles || 0;
                const totalPlazas = totalItems + totalAcefalias;
                
                // Calcular porcentajes
                const porcentajeItems = totalPlazas > 0 ? Math.round((totalItems / totalPlazas) * 100) : 0;
                const porcentajeAcefalias = totalPlazas > 0 ? Math.round((totalAcefalias / totalPlazas) * 100) : 0;
                const porcentajeInamoviles = totalPlazas > 0 ? Math.round((totalInamoviles / totalPlazas) * 100) : 0;
                const porcentajeInamovilesDisplay = porcentajeInamoviles;

                html = `
                    <div class="text-center">

                        <div class="p-4 rounded-4 mb-4" style="background: linear-gradient(135deg, #1a237e 0%, #0d47a1 50%, #1565c0 100%); box-shadow: 0 8px 32px rgba(13, 71, 161, 0.3);">
                            <h4 class="mb-0 text-white fw-bold" style="font-size: 1.3rem; letter-spacing: 0.5px;">
                                <i class="bi bi-building me-2"></i>${area}
                            </h4>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- Items -->
                            <div class="col-6 col-md-3 col-xl">
                                <div class="card-datos h-100 border-0" style="border-radius: 14px; box-shadow: 0 4px 16px rgba(0,0,0,0.06);">
                                    <div class="card-body text-center p-3">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 48px; height: 48px; background: rgba(40, 167, 69, 0.12);">
                                            <i class="bi bi-check-circle-fill" style="font-size: 1.4rem; color: #28a745;"></i>
                                        </div>
                                        <div class="numero-dato text-success fw-bold" style="font-size: 1.8rem;">${totalItems}</div>
                                        <div class="texto-dato text-muted small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Con Item</div>
                                        <div class="badge-items mt-2 d-inline-block" style="font-size: 0.7rem; padding: 0.2rem 0.7rem;">${porcentajeItems}%</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Acefalías -->
                            <div class="col-6 col-md-3 col-xl">
                                <div class="card-datos h-100 border-0" style="border-radius: 14px; box-shadow: 0 4px 16px rgba(0,0,0,0.06);">
                                    <div class="card-body text-center p-3">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 48px; height: 48px; background: rgba(220, 53, 69, 0.12);">
                                            <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.4rem; color: #dc3545;"></i>
                                        </div>
                                        <div class="numero-dato text-danger fw-bold" style="font-size: 1.8rem;">${totalAcefalias}</div>
                                        <div class="texto-dato text-muted small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Acefalía</div>
                                        <div class="badge-acefalias mt-2 d-inline-block" style="font-size: 0.7rem; padding: 0.2rem 0.7rem;">${porcentajeAcefalias}%</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Inamovibles -->
                            <div class="col-6 col-md-3 col-xl">
                                <div class="card-datos h-100 border-0" style="border-radius: 14px; box-shadow: 0 4px 16px rgba(0,0,0,0.06);">
                                    <div class="card-body text-center p-3">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 48px; height: 48px; background: rgba(255, 193, 7, 0.12);">
                                            <i class="bi bi-shield-fill" style="font-size: 1.4rem; color: #ffc107;"></i>
                                        </div>
                                        <div class="numero-dato text-warning fw-bold" style="font-size: 1.8rem;">${totalInamoviles}</div>
                                        <div class="texto-dato text-muted small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Inamovibles</div>
                                        <div class="badge-inamoviles mt-2 d-inline-block" style="font-size: 0.7rem; padding: 0.2rem 0.7rem;">${porcentajeInamovilesDisplay}%</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Total Items -->
                            <div class="col-6 col-md-3 col-xl">
                                <div class="card-datos h-100 border-0" style="border-radius: 14px; box-shadow: 0 4px 16px rgba(0,0,0,0.06);">
                                    <div class="card-body text-center p-3">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 48px; height: 48px; background: rgba(13, 110, 253, 0.12);">
                                            <i class="bi bi-list-check" style="font-size: 1.4rem; color: #0d6efd;"></i>
                                        </div>
                                        <div class="numero-dato text-primary fw-bold" style="font-size: 1.8rem;">${totalPlazas}</div>
                                        <div class="texto-dato text-muted small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Total Plazas</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Total -->
                            <div class="col-6 col-md-3 col-xl">
                                <div class="card-datos h-100 border-0" style="border-radius: 14px; box-shadow: 0 4px 16px rgba(0,0,0,0.06);">
                                    <div class="card-body text-center p-3">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 48px; height: 48px; background: rgba(23, 162, 184, 0.12);">
                                            <i class="bi bi-calculator" style="font-size: 1.4rem; color: #17a2b8;"></i>
                                        </div>
                                        <div class="numero-dato text-info fw-bold" style="font-size: 1.8rem;">${totalPlazas}</div>
                                        <div class="texto-dato text-muted small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Total</div>
                                        <div class="badge-total mt-2 d-inline-block" style="font-size: 0.7rem; padding: 0.2rem 0.7rem; background: linear-gradient(135deg, #0d6efd, #6610f2);">100%</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DATOS GENERALES DE TODAS LAS UNIDADES -->
                        <div class="mt-4">
                            <div class="rounded-4 p-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid rgba(0,0,0,0.04);">
                                <h5 class="text-center mb-4 fw-bold" style="color: #1a237e; font-size: 1.1rem; letter-spacing: 1px; text-transform: uppercase;">
                                    <i class="bi bi-graph-up me-2"></i> Datos Generales por Unidad
                                </h5>
                                <div id="datos-unidades">
                                    <div class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                        <p class="mt-3 text-muted fw-bold" style="font-size: 0.9rem;">Cargando datos de unidades...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 mb-2">
                            <button 
                                class="btn px-4 py-2 rounded-3 border-0 fw-semibold"
                                style="background: linear-gradient(135deg, #1a237e, #0d47a1); color: #fff; box-shadow: 0 4px 16px rgba(13, 71, 161, 0.3); transition: all 0.25s; font-size: 0.9rem;"
                                onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 24px rgba(13, 71, 161, 0.4)'"
                                onmouseout="this.style.transform='';this.style.boxShadow='0 4px 16px rgba(13, 71, 161, 0.3)'"
                                onclick="verPersonal('${area}')"
                            >
                                <i class="bi bi-people-fill me-2"></i> Ver Personal de ${area}
                            </button>
                        </div>

                    </div>
                `;
            } else {
                // Para otras áreas, mostrar el modal con el mismo diseño
                const totalItems = data.items || 0;
                const totalAcefalias = data.acefalias || 0;
                const totalInamoviles = data.inamoviles || 0;
                const totalPlazas = totalItems + totalAcefalias;
                const porcentaje = totalPlazas > 0 ? Math.round((totalItems / totalPlazas) * 100) : 0;
                const colorPorcentaje = porcentaje >= 80 ? 'success' : porcentaje >= 50 ? 'warning' : 'danger';

                html = `
                    <div class="text-center">

                        <div class="bg-primary text-white p-3 rounded mb-4">
                            <h4 style="font-size: 1.2rem; margin: 0; font-weight: bold;">${area}</h4>
                        </div>

                        <div class="card-datos mb-4" style="max-width: 500px; margin: 0 auto;">
                            <div class="card-body p-3">
                                <!-- Título principal -->
                                <div class="text-center mb-3">
                                    <h5 style="font-size: 1.1rem; color: #0d6efd; font-weight: bold;">
                                        <i class="bi bi-people-fill me-2"></i>Cuántos tienen Item y Acefalías
                                    </h5>
                                </div>
                                
                                <!-- Métricas principales -->
                                <div class="row text-center mb-4">
                                    <div class="col-4">
                                        <div class="bg-success bg-opacity-10 rounded p-3 border border-success">
                                            <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>
                                            <h3 class="text-success mb-1" style="font-size: 1.5rem; font-weight: 700;">${totalItems}</h3>
                                            <div class="text-success" style="font-size: 0.8rem; font-weight: 600;">Items</div>
                                            <div class="badge bg-success mt-1" style="font-size: 0.7rem;">${Math.round((totalItems / totalPlazas) * 100)}%</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="bg-danger bg-opacity-10 rounded p-3 border border-danger">
                                            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 1.5rem;"></i>
                                            <h3 class="text-danger mb-1" style="font-size: 1.5rem; font-weight: 700;">${totalAcefalias}</h3>
                                            <div class="text-danger" style="font-size: 0.8rem; font-weight: 600;">Acefalías</div>
                                            <div class="badge bg-danger mt-1" style="font-size: 0.7rem;">${Math.round((totalAcefalias / totalPlazas) * 100)}%</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="bg-warning bg-opacity-10 rounded p-3 border border-warning">
                                            <i class="bi bi-shield-fill text-warning" style="font-size: 1.5rem;"></i>
                                            <h3 class="text-warning mb-1" style="font-size: 1.5rem; font-weight: 700;">${totalInamoviles}</h3>
                                            <div class="text-warning" style="font-size: 0.8rem; font-weight: 600;">Inamoviles</div>
                                            <div class="badge bg-warning mt-1" style="font-size: 0.7rem;">${Math.round((totalInamoviles / totalPlazas) * 100)}%</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Barra de progreso -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span style="font-size: 0.9rem; font-weight: 600; color: #6c757d;">Ocupación Total</span>
                                        <span style="font-size: 0.9rem; font-weight: 700; color: #0d6efd;">${porcentaje}%</span>
                                    </div>
                                    <div class="progress" style="height: 12px; border-radius: 6px;">
                                        <div class="progress-bar bg-${colorPorcentaje}" style="width: ${porcentaje}%; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                                            ${porcentaje > 10 ? porcentaje + '%' : ''}
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Totales -->
                                <div class="bg-light rounded p-3 mb-4">
                                    <div class="row text-center">
                                        <div class="col-3">
                                            <div style="font-size: 0.7rem; color: #6c757d; margin-bottom: 0.5rem;">Total Items:</div>
                                            <div style="font-size: 1.1rem; font-weight: 700; color: #0d6efd;">${totalPlazas}</div>
                                        </div>
                                        <div class="col-3">
                                            <div style="font-size: 0.7rem; color: #6c757d; margin-bottom: 0.5rem;">Con Items:</div>
                                            <div style="font-size: 1.1rem; font-weight: 700; color: #198754;">${totalItems}</div>
                                        </div>
                                        <div class="col-3">
                                            <div style="font-size: 0.7rem; color: #6c757d; margin-bottom: 0.5rem;">Acefalías:</div>
                                            <div style="font-size: 1.1rem; font-weight: 700; color: #dc3545;">${totalAcefalias}</div>
                                        </div>
                                        <div class="col-3">
                                            <div style="font-size: 0.7rem; color: #6c757d; margin-bottom: 0.5rem;">Inamoviles:</div>
                                            <div style="font-size: 1.1rem; font-weight: 700; color: #ffc107;">${totalInamoviles}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Estado -->
                                <div class="text-center mb-4">
                                    <span class="badge bg-${colorPorcentaje} bg-opacity-10 text-${colorPorcentaje}" style="font-size: 0.9rem; font-weight: 600; padding: 0.5rem 1rem;">
                                        ${porcentaje >= 80 ? '✅ Óptimo' : porcentaje >= 50 ? '⚠️ Regular' : '🚨 Crítico'}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Botón elevado -->
                        <div class="text-center" style="margin-bottom: 2rem;">
                            <button 
                                class="btn btn-primary btn-lg"
                                style="font-size: 1rem; padding: 0.75rem 2rem; box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);"
                                onclick="verPersonal('${area}')"
                            >
                                <i class="bi bi-people-fill me-2"></i>Ver Personal
                            </button>
                        </div>

                    </div>
                `;
            }

            document.getElementById("tituloModal").innerText = area;
            document.getElementById("contenidoModal").innerHTML = html;

            // Si es GERENCIA, cargar datos de todas las unidades
            if (area === 'GERENCIA REGIONAL LA PAZ - GRLPZ') {
                cargarDatosUnidades();
            }

            new bootstrap.Modal(
                document.getElementById('modalInfo')
            ).show();
        });
        
}
function verPersonal(area){

    window.location.href = 
        `/servidores?area=${encodeURIComponent(area)}`;

}

// Función para cargar datos de todas las unidades principales
async function cargarDatosUnidades() {
    const unidadesPrincipales = [
        'Unidad Administrativa',
        'Unidad Fiscalización', 
        'Unidad Jurídica',
        'Administración Aduana Interior La Paz',
        'Aduana Frontera Guayaramerín',
        'Aduana Aeropuerto El Alto',
        'Administración Aduana Zona Franca',
        'Administración Aduana Zona Franca Industrial Patacamaya',
        'Administración Aduana Frontera Desaguadero',
        'Zona Franca Comercial / Frontera Cobija',
        'Agencia Aduana Exterior Matarani',
        'Administración Aduana Frontera Charaña'
    ];

    let datosUnidades = [];

    // Cargar datos de cada unidad
    for (const unidad of unidadesPrincipales) {
        try {
            const response = await fetch(`/organigrama/${encodeURIComponent(unidad)}`);
            const data = await response.json();
            
            datosUnidades.push({
                nombre: unidad,
                items: data.items || 0,
                acefalias: data.acefalias || 0,
                total: (data.items || 0) + (data.acefalias || 0)
            });
        } catch (error) {
            console.error(`Error cargando ${unidad}:`, error);
            // Agregar unidad con datos vacíos para mantener el grid consistente
            datosUnidades.push({
                nombre: unidad,
                items: 0,
                acefalias: 0,
                total: 0
            });
        }
    }

    // Generar HTML para mostrar los datos
    const htmlUnidades = datosUnidades.map(unidad => {
            const total = unidad.total || 0;
            const maximo = unidad.maximo || total || 1;
            const porcentaje = Math.min(100, Math.round((total / maximo) * 100));
            const colorPorcentaje = porcentaje >= 80 ? 'success' : porcentaje >= 50 ? 'warning' : 'danger';
            
            // Safe percentage calculation (avoid NaN)
            const pctItems = total > 0 ? Math.round((unidad.items / total) * 100) : 0;
            const pctAcefalias = total > 0 ? Math.round((unidad.acefalias / total) * 100) : 0;
            
            return `
            <div class="card-datos border-0" style="border-radius: 14px; box-shadow: 0 4px 16px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                <div class="card-body p-3">
                    <div class="text-center mb-2">
                        <h6 class="mb-1 fw-bold" style="font-size: 0.85rem; color: #1a237e; line-height: 1.3;">
                            ${unidad.nombre.length > 30 ? unidad.nombre.substring(0, 28) + '...' : unidad.nombre}
                        </h6>
                        <span class="d-inline-block px-2 py-1 rounded-3 fw-bold" style="font-size: 0.8rem; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);">${unidad.total}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="text-center flex-fill">
                            <i class="bi bi-check-circle-fill" style="font-size: 1.2rem; color: #28a745;"></i>
                            <div class="fw-bold text-success" style="font-size: 1.3rem;">${unidad.items}</div>
                            <div class="text-muted small text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.3px;">Items</div>
                            <span class="d-inline-block rounded-3 px-2 py-0 mt-1 fw-semibold" style="font-size: 0.65rem; background: rgba(40,167,69,0.12); color: #28a745;">${pctItems}%</span>
                        </div>
                        <div class="text-center flex-fill">
                            <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.2rem; color: #dc3545;"></i>
                            <div class="fw-bold text-danger" style="font-size: 1.3rem;">${unidad.acefalias}</div>
                            <div class="text-muted small text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.3px;">Acefalía</div>
                            <span class="d-inline-block rounded-3 px-2 py-0 mt-1 fw-semibold" style="font-size: 0.65rem; background: rgba(220,53,69,0.12); color: #dc3545;">${pctAcefalias}%</span>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <div class="progress" style="height: 5px; border-radius: 4px; background: #e9ecef;">
                            <div class="progress-bar rounded-3 bg-${colorPorcentaje}" style="width: ${porcentaje}%; transition: width 0.6s ease;"></div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="d-inline-flex align-items-center gap-1 fw-semibold rounded-3 px-2 py-1" style="font-size: 0.65rem; background: ${porcentaje >= 80 ? 'rgba(25,135,84,0.1)' : porcentaje >= 50 ? 'rgba(255,193,7,0.1)' : 'rgba(220,53,69,0.1)'}; color: ${porcentaje >= 80 ? '#198754' : porcentaje >= 50 ? '#856404' : '#dc3545'};">
                            ${porcentaje >= 80 ? '✅ Óptimo' : porcentaje >= 50 ? '⚠️ Regular' : '🚨 Crítico'}
                        </span>
                        <div class="d-flex gap-1">
                            <button class="btn-generate-report-excel" onclick="generateReportExcel('${unidad.nombre}')">
                                <i class="bi bi-file-earmark-excel"></i> Excel
                            </button>
                            <button class="btn-generate-report-pdf" onclick="generateReportPdf('${unidad.nombre}')">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;}).join('');

    // Actualizar el contenedor
    const contenedor = document.getElementById('datos-unidades');
    if (contenedor) {
        contenedor.innerHTML = `
            <div class="unidades-grid">
                ${htmlUnidades}
            </div>
        `;
    }
}

// Función para ver detalles de una unidad específica
function verDetallesUnidad(unidad) {
    // Cerrar el modal actual y abrir el de la unidad específica
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalInfo'));
    if (modal) {
        modal.hide();
    }
    
    // Pequeña espera para que se cierre el modal actual
    setTimeout(() => {
        mostrarInfo(unidad);
    }, 300);
}

// Función para exportar datos de una unidad
function exportarDatosUnidad(unidad) {
    // Simular exportación de datos
    console.log('Exportando datos de:', unidad);
    
    // Crear un mensaje de confirmación
    const alerta = document.createElement('div');
    alerta.className = 'alert alert-info alert-dismissible fade show position-fixed';
    alerta.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alerta.innerHTML = `
        <strong><i class="bi bi-download me-2"></i>Exportando Reporte</strong><br>
        <small>Generando reporte de ${unidad}...</small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alerta);
    
    // Simular descarga
    setTimeout(() => {
        alerta.classList.remove('alert-info');
        alerta.classList.add('alert-success');
        alerta.innerHTML = `
            <strong><i class="bi bi-check-circle me-2"></i>¡Exportación Completada!</strong><br>
            <small>El reporte de ${unidad} ha sido descargado</small>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Remover alerta después de 3 segundos
        setTimeout(() => {
            if (alerta.parentNode) {
                alerta.parentNode.removeChild(alerta);
            }
        }, 3000);
    }, 2000);
}

// Función para generar reporte de cada unidad
function generateReportExcel(unidad) {
    console.log('Generando reporte Excel para:', unidad);
    
    // Crear alerta flotante
    const alerta = document.createElement('div');
    alerta.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alerta.style.cssText = 'bottom: 20px; right: 20px; z-index: 9999; min-width: 250px;';
    alerta.innerHTML = `
        <strong><i class="bi bi-file-earmark-excel me-2"></i>Generando Excel</strong><br>
        <small>${unidad}...</small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alerta);
    
    // Codificar el nombre de la unidad para URL
    const unidadCodificada = encodeURIComponent(unidad);
    
    // Descargar el reporte Excel desde el backend
    const link = document.createElement('a');
    link.href = `/reporte/unidad/${unidadCodificada}`;
    link.download = ''; // El navegador usará el nombre del archivo del backend
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Actualizar alerta a éxito
    setTimeout(() => {
        alerta.classList.remove('alert-success');
        alerta.classList.add('alert-success');
        alerta.innerHTML = `
            <strong><i class="bi bi-check-circle me-2"></i>¡Excel Descargado!</strong><br>
            <small>${unidad} descargado exitosamente</small>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Remover alerta después de 3 segundos
        setTimeout(() => {
            if (alerta.parentNode) {
                alerta.parentNode.removeChild(alerta);
            }
        }, 3000);
    }, 1500);
}

function generateReportPdf(unidad) {
    console.log('Generando reporte PDF para:', unidad);
    
    // Crear alerta flotante
    const alerta = document.createElement('div');
    alerta.className = 'alert alert-danger alert-dismissible fade show position-fixed';
    alerta.style.cssText = 'bottom: 20px; right: 20px; z-index: 9999; min-width: 250px;';
    alerta.innerHTML = `
        <strong><i class="bi bi-file-earmark-pdf me-2"></i>Generando PDF</strong><br>
        <small>${unidad}...</small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alerta);
    
    // Codificar el nombre de la unidad para URL
    const unidadCodificada = encodeURIComponent(unidad);
    
    // Descargar el reporte PDF desde el backend
    const link = document.createElement('a');
    link.href = `/reporte/unidad/${unidadCodificada}/pdf`;
    link.download = ''; // El navegador usará el nombre del archivo del backend
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Actualizar alerta a éxito
    setTimeout(() => {
        alerta.classList.remove('alert-danger');
        alerta.classList.add('alert-success');
        alerta.innerHTML = `
            <strong><i class="bi bi-check-circle me-2"></i>¡PDF Descargado!</strong><br>
            <small>${unidad} descargado exitosamente</small>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Remover alerta después de 3 segundos
        setTimeout(() => {
            if (alerta.parentNode) {
                alerta.parentNode.removeChild(alerta);
            }
        }, 3000);
    }, 1500);
}