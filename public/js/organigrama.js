
const datos = {
    "GERENCIA REGIONAL LA PAZ - GRLPZ": { items:10, acefalias:1, personal:["Gerente","Asistente"] },
    "ASESORÍA": { items:2, acefalias:0, personal:["Asesor 1","Asesor 2"] },
    "SECRETARIA": { items:1, acefalias:0, personal:["Secretaria"] },
    "SISTEMAS": { items:3, acefalias:1, personal:["Soporte","Dev","Redes"] },
    "USO": { items:2, acefalias:0, personal:["Usuarios"] },
    "ARCHIVO": { items:2, acefalias:0, personal:["Archivista"] },

    "Unidad Administrativa": { items:8, acefalias:2, personal:["Admin","Contador"] },
    "Contabilidad": { items:5, acefalias:1, personal:["Contador","Auxiliar"] },
    "Activos Fijos": { items:3, acefalias:0, personal:["Encargado"] },
    "Talento Humano": { items:4, acefalias:2, personal:["RRHH"] },
    "Contrataciones": { items:2, acefalias:1, personal:["Contratador"] },
    "Servicios Generales": { items:6, acefalias:0, personal:["Servicios"] },

    "Unidad Fiscalización": { items:5, acefalias:1, personal:["Fiscal 1"] },
    "Fiscalizaciones posteriores": { items:3, acefalias:1, personal:["Fiscal A"] },
    "Controles diferidos": { items:2, acefalias:0, personal:["Controlador"] },

    "Unidad Jurídica": { items:4, acefalias:1, personal:["Abogado"] },
    "Cobranza coactiva": { items:2, acefalias:1, personal:["Cobranza"] },
    "Técnica jurídica": { items:2, acefalias:0, personal:["Técnico"] },
    "Procesos administrativos": { items:3, acefalias:1, personal:["Proceso"] },

    "Administración Aduana Interior La Paz": { items:6, acefalias:0, personal:["Equipo"] },
    "SPCC (Comisos)": { items:2, acefalias:0, personal:["Comisos"] },
    "Disposición de mercancías": { items:2, acefalias:0, personal:["Depósito"] },
    "Despachos": { items:3, acefalias:0, personal:["Despachador"] },
    "Gestión": { items:2, acefalias:0, personal:["Gestor"] },

    "Aduana Frontera Guayaramerín": { items:3, acefalias:1, personal:["Frontera"] },
    "Aduana Aeropuerto El Alto": { items:4, acefalias:0, personal:["Aeropuerto"] }
};

function mostrarInfo(area){
    let data = datos[area];

    let html = data ? `
        <div class="text-center">
            <div class="bg-primary text-white p-2 rounded mb-3"><b>${area}</b></div>

            <div class="row mb-3">
                <div class="col">
                    <div class="card"><div class="card-body">
                        <h6>Total Items</h6>
                        <span class="badge bg-success">${data.items}</span>
                    </div></div>
                </div>

                <div class="col">
                    <div class="card"><div class="card-body">
                        <h6>Acefalías</h6>
                        <span class="badge bg-danger">${data.acefalias}</span>
                    </div></div>
                </div>
            </div>

            <ul class="list-group">
                ${data.personal.map(p => `<li class="list-group-item">${p}</li>`).join('')}
            </ul>
        </div>
    ` : "<p class='text-center'>Sin datos</p>";

    document.getElementById("tituloModal").innerText = area;
    document.getElementById("contenidoModal").innerHTML = html;

    new bootstrap.Modal(document.getElementById('modalInfo')).show();
}