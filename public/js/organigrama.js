function mostrarInfo(area){

    fetch(`/organigrama/${encodeURIComponent(area)}`)
        .then(res => res.json())
        .then(data => {

            let html = `
                <div class="text-center">

                    <div class="bg-primary text-white p-2 rounded mb-3">
                        <b>${area}</b>
                    </div>

                    <div class="row mb-3">

                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Total Items</h6>
                                    <span class="badge bg-success">
                                        ${data.items}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Acefalías</h6>
                                    <span class="badge bg-danger">
                                        ${data.acefalias}
                                    </span>
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

            document.getElementById("tituloModal").innerText = area;
            document.getElementById("contenidoModal").innerHTML = html;

            new bootstrap.Modal(
                document.getElementById('modalInfo')
            ).show();
        });
}
function verPersonal(area){

    window.location.href = 
        `/servidores?area=${encodeURIComponent(area)}`;

}