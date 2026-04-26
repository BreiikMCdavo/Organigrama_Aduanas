function mostrarInfo(area){

    fetch(`/organigrama/${encodeURIComponent(area)}`)
        .then(res => res.json())
        .then(data => {

            let html;

            // Si es GERENCIA, mostrar el dashboard completo
            if (area === 'GERENCIA REGIONAL LA PAZ - GRLPZ') {
                const totalItems = data.items || 0;
                const totalAcefalias = data.acefalias || 0;
                const totalPlazas = totalItems + totalAcefalias;
                
                // Calcular porcentajes
                const porcentajeItems = totalPlazas > 0 ? Math.round((totalItems / totalPlazas) * 100) : 0;
                const porcentajeAcefalias = totalPlazas > 0 ? Math.round((totalAcefalias / totalPlazas) * 100) : 0;

                html = `
                    <div class="text-center">

                        <div class="bg-primary text-white p-3 rounded mb-4">
                            <h4 class="mb-0"><i class="bi bi-building me-2"></i>${area}</h4>
                        </div>

                        <div class="row g-4 mb-4">
                            <!-- Items -->
                            <div class="col-md-6">
                                <div class="card-datos h-100">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-check-circle-fill icon-datos icon-success"></i>
                                        <div class="numero-dato text-success">${totalItems}</div>
                                        <div class="texto-dato">Con Item</div>
                                        <div class="badge-items mt-2">${porcentajeItems}%</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Acefalías -->
                            <div class="col-md-6">
                                <div class="card-datos h-100">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-exclamation-triangle-fill icon-datos icon-danger"></i>
                                        <div class="numero-dato text-danger">${totalAcefalias}</div>
                                        <div class="texto-dato">Acefalía</div>
                                        <div class="badge-acefalias mt-2">${porcentajeAcefalias}%</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Total Items -->
                            <div class="col-md-6">
                                <div class="card-datos h-100">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-list-check icon-datos icon-primary"></i>
                                        <div class="numero-dato text-primary">${totalPlazas}</div>
                                        <div class="texto-dato">Total de Items</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Total -->
                            <div class="col-md-6">
                                <div class="card-datos h-100">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-calculator icon-datos icon-info"></i>
                                        <div class="numero-dato text-info">${totalPlazas}</div>
                                        <div class="texto-dato">Total</div>
                                        <div class="badge-total mt-2">100%</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DATOS GENERALES DE TODAS LAS UNIDADES -->
                        <div class="mt-4">
                            <div class="bg-light rounded-3 p-4">
                                <h5 class="text-center mb-4 fw-bold text-primary">
                                    <i class="bi bi-graph-up me-2"></i>📊 Datos Generales por Unidad
                                </h5>
                                <div id="datos-unidades">
                                    <div class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                        <p class="mt-3 text-muted fw-bold">Cargando datos de unidades...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button 
                                class="btn btn-primary"
                                onclick="verPersonal('${area}')"
                            >
                                Ver Personal de ${area}
                            </button>
                        </div>

                    </div>
                `;
            } else {
                // Para otras áreas, mostrar el modal con el mismo diseño
                const totalItems = data.items || 0;
                const totalAcefalias = data.acefalias || 0;
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
                                    <div class="col-6">
                                        <div class="bg-success bg-opacity-10 rounded p-3 border border-success">
                                            <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                                            <h3 class="text-success mb-1" style="font-size: 2rem; font-weight: 700;">${totalItems}</h3>
                                            <div class="text-success" style="font-size: 0.9rem; font-weight: 600;">Items</div>
                                            <div class="badge bg-success mt-2" style="font-size: 0.8rem;">${Math.round((totalItems / totalPlazas) * 100)}%</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-danger bg-opacity-10 rounded p-3 border border-danger">
                                            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2rem;"></i>
                                            <h3 class="text-danger mb-1" style="font-size: 2rem; font-weight: 700;">${totalAcefalias}</h3>
                                            <div class="text-danger" style="font-size: 0.9rem; font-weight: 600;">Acefalías</div>
                                            <div class="badge bg-danger mt-2" style="font-size: 0.8rem;">${Math.round((totalAcefalias / totalPlazas) * 100)}%</div>
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
                                        <div class="col-4">
                                            <div style="font-size: 0.8rem; color: #6c757d; margin-bottom: 0.5rem;">Total Items:</div>
                                            <div style="font-size: 1.2rem; font-weight: 700; color: #0d6efd;">${totalPlazas}</div>
                                        </div>
                                        <div class="col-4">
                                            <div style="font-size: 0.8rem; color: #6c757d; margin-bottom: 0.5rem;">Con Items:</div>
                                            <div style="font-size: 1.2rem; font-weight: 700; color: #198754;">${totalItems}</div>
                                        </div>
                                        <div class="col-4">
                                            <div style="font-size: 0.8rem; color: #6c757d; margin-bottom: 0.5rem;">Acefalías:</div>
                                            <div style="font-size: 1.2rem; font-weight: 700; color: #dc3545;">${totalAcefalias}</div>
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
        const porcentaje = unidad.total > 0 ? Math.round((unidad.items / unidad.total) * 100) : 0;
        const colorPorcentaje = porcentaje >= 80 ? 'success' : porcentaje >= 50 ? 'warning' : 'danger';
        
        return `
        <div class="card-datos">
            <div class="card-body d-flex flex-column p-2" style="min-height: 160px;">
                <!-- Header compacto -->
                <div class="text-center mb-2">
                    <h6 class="titulo-unidad mb-1" style="font-size: 1rem; line-height: 1.2; word-wrap: break-word; font-weight: 600;">
                        ${unidad.nombre.length > 25 ? unidad.nombre.substring(0, 25) + '...' : unidad.nombre}
                    </h6>
                    <span class="badge-total" style="font-size: 0.9rem; font-weight: 600;">${unidad.total}</span>
                </div>
                
                <!-- Métricas compactas -->
                <div class="d-flex justify-content-between mb-2 flex-grow-1">
                    <!-- Items -->
                    <div class="d-flex flex-column align-items-center justify-content-center" style="flex: 1; min-height: 80px;">
                        <div class="text-center">
                            <i class="bi bi-check-circle-fill icon-datos icon-success mb-1" style="font-size: 1.5rem;"></i>
                            <div class="numero-dato text-success mb-0" style="font-size: 1.6rem; font-weight: 700;">${unidad.items}</div>
                            <div class="texto-dato mb-1" style="font-size: 0.85rem; font-weight: 600;">Items</div>
                            <div class="badge-items" style="font-size: 0.8rem; font-weight: 600;">${Math.round((unidad.items / unidad.total) * 100)}%</div>
                        </div>
                    </div>
                    
                    <!-- Acefalía -->
                    <div class="d-flex flex-column align-items-center justify-content-center" style="flex: 1; min-height: 80px;">
                        <div class="text-center">
                            <i class="bi bi-exclamation-triangle-fill icon-datos icon-danger mb-1" style="font-size: 1.5rem;"></i>
                            <div class="numero-dato text-danger mb-0" style="font-size: 1.6rem; font-weight: 700;">${unidad.acefalias}</div>
                            <div class="texto-dato mb-1" style="font-size: 0.85rem; font-weight: 600;">Acefalía</div>
                            <div class="badge-acefalias" style="font-size: 0.8rem; font-weight: 600;">${Math.round((unidad.acefalias / unidad.total) * 100)}%</div>
                        </div>
                    </div>
                </div>
                
                <!-- Barra de progreso compacta -->
                <div class="mb-2">
                    <div class="progress" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar bg-${colorPorcentaje}" style="width: ${porcentaje}%; border-radius: 3px; font-size: 0.6rem;">
                        </div>
                    </div>
                </div>
                
                <!-- Estado y botón compactos -->
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-${colorPorcentaje} bg-opacity-10 text-${colorPorcentaje}" style="font-size: 0.8rem; font-weight: 600; padding: 0.4rem 0.8rem;">
                        ${porcentaje >= 80 ? '✅ Óptimo' : porcentaje >= 50 ? '⚠️ Regular' : '🚨 Crítico'}
                    </span>
                    <button class="btn-generate-report" style="font-size: 0.75rem; padding: 0.4rem 0.6rem;" onclick="generateReport('${unidad.nombre}')">
                        <i class="bi bi-file-earmark-text me-1"></i>Report
                    </button>
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
function generateReport(unidad) {
    console.log('Generando reporte para:', unidad);
    
    // Crear alerta flotante
    const alerta = document.createElement('div');
    alerta.className = 'alert alert-info alert-dismissible fade show position-fixed';
    alerta.style.cssText = 'bottom: 20px; right: 20px; z-index: 9999; min-width: 250px;';
    alerta.innerHTML = `
        <strong><i class="bi bi-file-earmark-text me-2"></i>Generando Reporte</strong><br>
        <small>${unidad}...</small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alerta);
    
    // Simular generación
    setTimeout(() => {
        alerta.classList.remove('alert-info');
        alerta.classList.add('alert-success');
        alerta.innerHTML = `
            <strong><i class="bi bi-check-circle me-2"></i>¡Reporte Generado!</strong><br>
            <small>${unidad} listo para descargar</small>
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