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
                            <h5 class="mb-0"><i class="bi bi-building me-2"></i>${area}</h5>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- Items -->
                            <div class="col-6">
                                <div class="card-datos">
                                    <div class="card-body text-center">
                                        <i class="bi bi-check-circle-fill icon-datos icon-success"></i>
                                        <div class="numero-dato text-success">${totalItems}</div>
                                        <div class="texto-dato">Con Item</div>
                                        <div class="badge-items mt-2">${porcentajeItems}%</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Acefalías -->
                            <div class="col-6">
                                <div class="card-datos">
                                    <div class="card-body text-center">
                                        <i class="bi bi-exclamation-triangle-fill icon-datos icon-danger"></i>
                                        <div class="numero-dato text-danger">${totalAcefalias}</div>
                                        <div class="texto-dato">Acefalía</div>
                                        <div class="badge-acefalias mt-2">${porcentajeAcefalias}%</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Total Items -->
                            <div class="col-6">
                                <div class="card-datos">
                                    <div class="card-body text-center">
                                        <i class="bi bi-list-check icon-datos icon-primary"></i>
                                        <div class="numero-dato text-primary">${totalPlazas}</div>
                                        <div class="texto-dato">Total de Items</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Total -->
                            <div class="col-6">
                                <div class="card-datos">
                                    <div class="card-body text-center">
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
                            <h6 class="text-center mb-3">📊 Datos Generales por Unidad</h6>
                            <div id="datos-unidades" class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2">Cargando datos de unidades...</p>
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

                        <div class="bg-primary text-white p-2 rounded mb-3">
                            <b>${area}</b>
                        </div>

                        <div class="card-datos mb-4">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h6 class="titulo-unidad mb-0"><i class="bi bi-graph-up me-2"></i>Resumen</h6>
                                    <span class="badge-total">${totalPlazas}</span>
                                </div>
                                
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <i class="bi bi-check-circle-fill icon-datos icon-success"></i>
                                        <div class="numero-dato text-success">${totalItems}</div>
                                        <div class="texto-dato">Items</div>
                                    </div>
                                    <div class="col-6">
                                        <i class="bi bi-exclamation-triangle-fill icon-datos icon-danger"></i>
                                        <div class="numero-dato text-danger">${totalAcefalias}</div>
                                        <div class="texto-dato">Acefalías</div>
                                    </div>
                                </div>
                                
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar bg-${colorPorcentaje}" style="width: ${porcentaje}%"></div>
                                </div>
                                
                                <div class="text-center">
                                    <small class="text-muted">${porcentaje}% ocupación</small>
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
        }
    }

    // Generar HTML para mostrar los datos
    const htmlUnidades = datosUnidades.map(unidad => {
        const porcentaje = unidad.total > 0 ? Math.round((unidad.items / unidad.total) * 100) : 0;
        const colorPorcentaje = porcentaje >= 80 ? 'success' : porcentaje >= 50 ? 'warning' : 'danger';
        
        return `
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card-datos">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="titulo-unidad text-truncate" style="max-width: 200px;" title="${unidad.nombre}">
                            <i class="bi bi-building me-1"></i>${unidad.nombre}
                        </h6>
                        <span class="badge-total">${unidad.total}</span>
                    </div>
                    
                    <div class="row text-center mb-2">
                        <div class="col-6">
                            <i class="bi bi-check-circle-fill icon-datos icon-success"></i>
                            <div class="numero-dato text-success">${unidad.items}</div>
                            <div class="texto-dato">Items</div>
                        </div>
                        <div class="col-6">
                            <i class="bi bi-exclamation-triangle-fill icon-datos icon-danger"></i>
                            <div class="numero-dato text-danger">${unidad.acefalias}</div>
                            <div class="texto-dato">Acefalías</div>
                        </div>
                    </div>
                    
                    <div class="progress mb-2" style="height: 6px;">
                        <div class="progress-bar bg-${colorPorcentaje}" style="width: ${porcentaje}%"></div>
                    </div>
                    
                    <div class="text-center">
                        <small class="text-muted">${porcentaje}% ocupación</small>
                    </div>
                </div>
            </div>
        </div>
    `;}).join('');

    // Actualizar el contenedor
    const contenedor = document.getElementById('datos-unidades');
    if (contenedor) {
        contenedor.innerHTML = `
            <div class="row g-2">
                ${htmlUnidades}
            </div>
        `;
    }
}