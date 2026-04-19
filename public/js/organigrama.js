function mostrarInfo(area, tipo = "unidad") {

    let url = "";

    if (tipo === "resumen") {
        url = `/organigrama/resumen-general`;
    } else if (tipo === "gerencia") {
        url = `/organigrama/gerencia/${encodeURIComponent(area)}`;
    } else {
        url = `/organigrama/${encodeURIComponent(area)}`;
    }

    fetch(url)
        .then(res => res.json())
        .then(data => {

            let html = "";

            // =========================
            // 🔥 RESUMEN GENERAL
            // =========================
            if (tipo === "resumen") {

                html = `
                    <div class="text-center">
                        <div class="bg-dark text-white p-2 rounded mb-3">
                            <b>RESUMEN GENERAL DE TODAS LAS ÁREAS</b>
                        </div>

                        <div class="row mb-3">

                            <div class="col">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h6>Total Ítems</h6>
                                        <span class="badge bg-primary fs-6">${data.total_items ?? 0}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h6>Total Acefalías</h6>
                                        <span class="badge bg-danger fs-6">${data.total_acefalias ?? 0}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                `;
            }

            // =========================
            // 🏢 GERENCIA (CORRECTO)
            // =========================
            
            else if (tipo === "gerencia") {

                html = `
                    <div class="text-center">

                        <!-- GERENCIA -->
                        <div class="bg-primary text-white p-2 rounded mb-3">
                            <b>${data.gerencia ?? area}</b>
                        </div>

                        <div class="mb-3">
                            <h6>Unidades dependientes</h6>
                        </div>

                        <div class="row">

                            ${(data.unidades || []).map(u => `
                                <div class="col-md-4 mb-3">

                                    <div class="card shadow-sm h-100"
                                        onclick="mostrarInfo('${u.nombre}', 'unidad')"
                                        style="cursor:pointer">

                                        <div class="card-body text-center">

                                            <h6 class="fw-bold">${u.nombre}</h6>

                                            <hr>

                                            <div class="text-success">
                                                ✔ Ítems: ${u.items ?? 0}
                                            </div>

                                            <div class="text-danger">
                                                ✖ Acefalías: ${u.acefalias ?? 0}
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            `).join('')}

                        </div>

                    </div>
                `;
            }

            // =========================
            // 🏬 UNIDAD
            // =========================
            else {

                const personalHTML = (data.personal || []).length
                    ? data.personal.map(p => `<li class="list-group-item">${p}</li>`).join('')
                    : `<li class="list-group-item text-muted">Sin personal</li>`;

                html = `
                    <div class="text-center">

                        <div class="bg-dark text-white p-2 rounded mb-3">
                            <b>${area}</b>
                        </div>

                        <div class="row mb-3">

                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Ítems</h6>
                                        <span class="badge bg-primary">${data.items ?? 0}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Acefalías</h6>
                                        <span class="badge bg-danger">${data.acefalias ?? 0}</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <ul class="list-group mb-3">
                            ${personalHTML}
                        </ul>

                    </div>
                    
                `;
            }

            // =========================
            // MODAL
            // =========================
            
            document.getElementById("tituloModal").innerText =
                tipo === "resumen" ? "Resumen General" : area;

            document.getElementById("contenidoModal").innerHTML = html;

            new bootstrap.Modal(document.getElementById('modalInfo')).show();
        });
}