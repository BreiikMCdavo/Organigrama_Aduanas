<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sistema Aduana</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/organigrama.css') }}">
    <style>
        .dropdown-report-btn {
            transition: all 0.2s ease;
        }
        .dropdown-report-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
        }
        .dropdown-report-btn:active {
            transform: translateY(0);
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark" style="background: linear-gradient(90deg, #0a1628 0%, #1a3a6b 100%); position: sticky; top: 0; z-index: 1000; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);">
        <div class="container-fluid px-4" style="min-height:64px;">

            <div class="d-flex align-items-center gap-3">

                <!-- LOGO -->
                <a href="{{ url('/index') }}"
                    style="background:#fff; border-radius:8px; padding:6px 12px; display:flex; align-items:center;">

                    <img src="{{ asset('img/logo_aduana.png') }}" alt="Aduana Nacional"
                        style="height:46px;width:auto;object-fit:contain;">

                </a>

                <!-- TEXTO SISTEMA -->
                <a class="navbar-brand d-flex flex-column lh-sm text-decoration-none"
                    href="{{ Request::is('servidores*') ? url('/') : url('/servidores') }}">

                    <span style="font-size:0.7rem;letter-spacing:2px;color:#a0b8d8;text-transform:uppercase;">
                        Sistema de Gestión
                    </span>

                    <span style="font-size:1rem;font-weight:700;color:#fff;">
                        Gerencia Regional La Paz
                    </span>

                </a>

            </div>

            <!-- BOTONES DERECHA -->
            <div class="d-flex gap-2 align-items-center">
                <!-- Estadísticas Rápidas -->
                <div class="d-flex gap-3 me-3">
                    <div class="text-center">
                        <div class="badge bg-success bg-opacity-25 text-white border border-success border-opacity-50 px-2 py-1" style="font-size: 0.7rem;">
                            <i class="bi bi-people-fill me-1"></i>
                            {{ \App\Models\ServidorPublico::where('tipo', 'item')->where(function($q){ $q->whereNull('acefalia')->orWhere('acefalia', false); })->count() }}
                        </div>
                        <small class="text-white-50 d-block" style="font-size: 0.6rem;">Items</small>
                    </div>
                    <div class="text-center">
                        <div class="badge bg-info bg-opacity-25 text-white border border-info border-opacity-50 px-2 py-1" style="font-size: 0.7rem;">
                            <i class="bi bi-briefcase-fill me-1"></i>
                            {{ \App\Models\ServidorPublico::where('tipo', 'consultoria')->where(function($q){ $q->whereNull('acefalia')->orWhere('acefalia', false); })->count() }}
                        </div>
                        <small class="text-white-50 d-block" style="font-size: 0.6rem;">Consultoría</small>
                    </div>
                    <div class="text-center">
                        <div class="badge bg-warning bg-opacity-25 text-white border border-warning border-opacity-50 px-2 py-1" style="font-size: 0.7rem;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            {{ \App\Models\ServidorPublico::where('acefalia', true)->count() }}
                        </div>
                        <small class="text-white-50 d-block" style="font-size: 0.6rem;">Acefalías</small>
                    </div>
                </div>

                <!-- Botones de Reportes -->
                <div class="dropdown">
                    <button class="btn btn-sm dropdown-toggle d-flex align-items-center gap-2 px-3 py-2 rounded-pill"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);color:#fff;border:none;box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);transition: all 0.3s ease; font-weight: 500;letter-spacing:0.5px;"
                        type="button" data-bs-toggle="dropdown" 
                        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.6)'"
                        onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.4)'">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span>Reportes</span>
                        <span class="badge bg-white bg-opacity-20 text-white ms-1" style="font-size: 0.6rem;">6</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0 overflow-hidden" 
                        style="background: linear-gradient(135deg, #1a3a6b 0%, #2c5282 100%);border: 1px solid rgba(255,255,255,0.1);min-width: 300px;border-radius: 16px;box-shadow: 0 8px 32px rgba(0,0,0,0.2);">
                        
                        <li style="padding: 10px;">
                            <!-- Items -->
                            <div class="rounded-3 p-3 mb-2" style="background: rgba(255,255,255,0.08); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.06);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-people-fill text-success" style="font-size: 1rem;"></i>
                                    <span style="color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase;">Servidores con Items</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="flex-fill btn btn-sm d-flex align-items-center justify-content-center gap-1 py-2 px-3 rounded-3 border-0 fw-semibold dropdown-report-btn"
                                            onclick="showExcelPreview('{{ route('reporte.items') }}', '📊 Reporte de Items - Excel')"
                                            style="font-size: 0.75rem; background: linear-gradient(135deg, #28a745, #20c997); color: #fff; box-shadow: 0 2px 8px rgba(40,167,69,0.3);">
                                        <i class="bi bi-file-earmark-excel"></i> Excel
                                    </button>
                                    <button class="flex-fill btn btn-sm d-flex align-items-center justify-content-center gap-1 py-2 px-3 rounded-3 border-0 fw-semibold dropdown-report-btn"
                                            onclick="showPdfPreview('{{ route('reporte.items.pdf') }}', '📊 Reporte de Items - Servidores Públicos')"
                                            style="font-size: 0.75rem; background: linear-gradient(135deg, #dc3545, #e4606d); color: #fff; box-shadow: 0 2px 8px rgba(220,53,69,0.3);">
                                        <i class="bi bi-file-earmark-pdf"></i> PDF
                                    </button>
                                </div>
                            </div>

                            <!-- Consultoría -->
                            <div class="rounded-3 p-3 mb-2" style="background: rgba(255,255,255,0.08); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.06);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-briefcase-fill text-info" style="font-size: 1rem;"></i>
                                    <span style="color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase;">Servidores de Consultoría</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="flex-fill btn btn-sm d-flex align-items-center justify-content-center gap-1 py-2 px-3 rounded-3 border-0 fw-semibold dropdown-report-btn"
                                            onclick="showExcelPreview('{{ route('reporte.consultoria') }}', '📋 Reporte de Consultoría - Excel')"
                                            style="font-size: 0.75rem; background: linear-gradient(135deg, #17a2b8, #6610f2); color: #fff; box-shadow: 0 2px 8px rgba(23,162,184,0.3);">
                                        <i class="bi bi-file-earmark-excel"></i> Excel
                                    </button>
                                    <button class="flex-fill btn btn-sm d-flex align-items-center justify-content-center gap-1 py-2 px-3 rounded-3 border-0 fw-semibold dropdown-report-btn"
                                            onclick="showPdfPreview('{{ route('reporte.consultoria.pdf') }}', '📋 Reporte de Consultoría - Servidores Públicos')"
                                            style="font-size: 0.75rem; background: linear-gradient(135deg, #dc3545, #e4606d); color: #fff; box-shadow: 0 2px 8px rgba(220,53,69,0.3);">
                                        <i class="bi bi-file-earmark-pdf"></i> PDF
                                    </button>
                                </div>
                            </div>

                            <!-- Acefalías -->
                            <div class="rounded-3 p-3" style="background: rgba(255,255,255,0.08); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.06);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 1rem;"></i>
                                    <span style="color: rgba(255,255,255,0.85); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase;">Plazas Vacantes (Acefalías)</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="flex-fill btn btn-sm d-flex align-items-center justify-content-center gap-1 py-2 px-3 rounded-3 border-0 fw-semibold dropdown-report-btn"
                                            onclick="showExcelPreview('{{ route('reporte.acefalias') }}', '⚠️ Reporte de Acefalías - Excel')"
                                            style="font-size: 0.75rem; background: linear-gradient(135deg, #ffc107, #fd7e14); color: #212529; box-shadow: 0 2px 8px rgba(255,193,7,0.3);">
                                        <i class="bi bi-file-earmark-excel"></i> Excel
                                    </button>
                                    <button class="flex-fill btn btn-sm d-flex align-items-center justify-content-center gap-1 py-2 px-3 rounded-3 border-0 fw-semibold dropdown-report-btn"
                                            onclick="showPdfPreview('{{ route('reporte.acefalias.pdf') }}', '⚠️ Reporte de Acefalías - Plazas Vacantes')"
                                            style="font-size: 0.75rem; background: linear-gradient(135deg, #dc3545, #e4606d); color: #fff; box-shadow: 0 2px 8px rgba(220,53,69,0.3);">
                                        <i class="bi bi-file-earmark-pdf"></i> PDF
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Botones Principales -->
                <a href="{{ route('servidores.index') }}" 
                   class="btn btn-sm px-3 rounded-pill d-flex align-items-center gap-2"
                   style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3);backdrop-filter: blur(10px);transition: all 0.3s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.25)';this.style.transform='translateY(-1px)'"
                   onmouseout="this.style.background='rgba(255,255,255,0.15)';this.style.transform='translateY(0)'">
                    <i class="bi bi-people"></i>
                    <span>Servidores</span>
                </a>

                <a href="{{ route('servidores.create') }}" 
                   class="btn btn-sm px-3 rounded-pill d-flex align-items-center gap-2"
                   style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);color:#fff;border:none;box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);transition: all 0.3s ease; font-weight: 500;"
                   onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(40, 167, 69, 0.6)'"
                   onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(40, 167, 69, 0.4)'">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Nuevo Servidor</span>
                </a>
            </div>

        </div>
    </nav>

    <!-- CONTENIDO DINÁMICO -->
    <div class="py-4">
        @yield('content')
    </div>

    <!-- MODAL GLOBAL (puede usarse en todas las páginas) -->
    <div class="modal fade" id="modalInfo" tabindex="-1">
        <div class="modal-dialog" style="max-width: 650px; width: 70vw;">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 id="tituloModal">Área</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="contenidoModal" style="overflow-x: hidden; overflow-y: auto;"></div>

            </div>
        </div>
    </div>

    <!-- MODAL PARA VISTA PREVIA DE PDFS -->
    <div class="modal fade" id="modalPdfPreview" tabindex="-1">
        <div class="modal-dialog modal-xl" style="max-width: 95vw; width: auto;">
            <div class="modal-content" style="height: auto; max-height: none;">
                <div class="modal-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="modal-title" id="pdfPreviewTitle">
                        <i class="bi bi-file-earmark-pdf me-2"></i>
                        <span id="pdfTitleText">Vista Previa de Reporte</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" style="height: auto; max-height: none; overflow: visible;">
                    <!-- Contenido del PDF se cargará aquí -->
                    <iframe id="pdfPreviewFrame" 
                            style="width: 100%; height: auto; min-height: 600px; border: none;"
                            src=""></iframe>
                </div>
                <div class="modal-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Vista previa del reporte. Use el botón de descarga para guardar el archivo.
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Cerrar
                            </button>
                            <button id="pdfDownloadBtn" 
                               class="btn btn-gradient-success text-white"
                               style="background: linear-gradient(135deg, #28a745, #20c997); border: none; padding: 8px 16px; border-radius: 6px;"
                               onclick="downloadCurrentPdf()">
                                <i class="bi bi-download me-1"></i>Descargar Reporte
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA VISTA PREVIA DE EXCEL -->
    <div class="modal fade" id="modalExcelPreview" tabindex="-1">
        <div class="modal-dialog modal-xl" style="max-width: 95vw; width: auto;">
            <div class="modal-content" style="height: auto; max-height: none;">
                <div class="modal-header bg-gradient text-white" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <h5 class="modal-title" id="excelPreviewTitle">
                        <i class="bi bi-file-earmark-excel me-2"></i>
                        <span id="excelTitleText">Vista Previa de Reporte Excel</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" style="height: auto; max-height: none; overflow: visible;">
                    <!-- Contenido del Excel se cargará aquí -->
                    <iframe id="excelPreviewFrame" 
                            style="width: 100%; height: auto; min-height: 600px; border: none;"
                            src=""></iframe>
                </div>
                <div class="modal-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Vista previa del reporte Excel. Use el botón de descarga para guardar el archivo.
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Cerrar
                            </button>
                            <button id="excelDownloadBtn" 
                               class="btn btn-gradient-success text-white"
                               style="background: linear-gradient(135deg, #28a745, #20c997); border: none; padding: 8px 16px; border-radius: 6px;"
                               onclick="downloadCurrentExcel()">
                                <i class="bi bi-download me-1"></i>Descargar Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPdfUrl = '';
        let currentExcelUrl = '';
        
        // Función para mostrar vista previa de PDF
        function showPdfPreview(url, title) {
            document.getElementById('pdfTitleText').textContent = title;
            document.getElementById('pdfPreviewFrame').src = url;
            currentPdfUrl = url;
            
            const modal = new bootstrap.Modal(document.getElementById('modalPdfPreview'));
            modal.show();
        }

        // Función para mostrar vista previa de Excel
        function showExcelPreview(url, title) {
            document.getElementById('excelTitleText').textContent = title;
            document.getElementById('excelPreviewFrame').src = url;
            currentExcelUrl = url;
            
            const modal = new bootstrap.Modal(document.getElementById('modalExcelPreview'));
            modal.show();
        }

        // Función para descargar el PDF actual usando jsPDF
        function downloadCurrentPdf() {
            if (currentPdfUrl) {
                // Mostrar feedback
                const btn = document.getElementById('pdfDownloadBtn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Generando PDF...';
                btn.disabled = true;
                
                // Obtener el contenido del iframe
                const iframe = document.getElementById('pdfPreviewFrame');
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                
                // Usar html2canvas para capturar el contenido
                html2canvas(iframeDoc.body, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff'
                }).then(canvas => {
                    // Crear PDF con jsPDF
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF('p', 'mm', 'a4');
                    
                    // Calcular dimensiones
                    const imgData = canvas.toDataURL('image/png');
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
                    
                    // Agregar imagen al PDF
                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    
                    // Generar nombre de archivo
                    const fileName = currentPdfUrl.includes('items') ? 'reporte_items' : 
                                   currentPdfUrl.includes('consultoria') ? 'reporte_consultoria' : 
                                   'reporte_acefalias';
                    const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, -5);
                    
                    // Descargar PDF
                    pdf.save(`${fileName}_${timestamp}.pdf`);
                    
                    // Mostrar feedback de completado
                    btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>¡PDF Descargado!';
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 2000);
                    
                }).catch(error => {
                    console.error('Error generando PDF:', error);
                    btn.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Error';
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 2000);
                });
            }
        }

        // Función para descargar Excel actual
        function downloadCurrentExcel() {
            if (currentExcelUrl) {
                // Crear un link temporal para descargar
                const link = document.createElement('a');
                link.href = currentExcelUrl;
                link.download = currentExcelUrl.split('/').pop() || 'reporte.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Mostrar feedback
                const btn = document.getElementById('excelDownloadBtn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Descargando...';
                btn.disabled = true;
                
                setTimeout(() => {
                    btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>¡Descargado!';
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 2000);
                }, 1000);
            }
        }

        // Función para abrir PDF en nueva pestaña (alternativa)
        function openPdfInNewTab(url) {
            window.open(url, '_blank');
        }
    </script>

    <!-- LIBRERÍAS PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
    <!-- SCRIPTS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/organigrama.js') }}"></script>

    @stack('scripts')

</body>

</html>