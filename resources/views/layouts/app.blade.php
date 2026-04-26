<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sistema Aduana</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/organigrama.css') }}">
    @stack('styles')
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark" style="background: linear-gradient(90deg, #0a1628 0%, #1a3a6b 100%);">
        <div class="container-fluid px-4" style="min-height:64px;">
            <a class="navbar-brand d-flex align-items-center gap-3" href="{{ url('/index') }}">
                <div style="background:#fff; border-radius:8px; padding:6px 12px; display:flex; align-items:center;">
                    <img src="{{ asset('img/logo_aduana.png') }}" alt="Aduana Nacional"
                         style="height:46px;width:auto;object-fit:contain;">
                </div>
                <div class="d-flex flex-column lh-sm">
                    <span style="font-size:0.7rem;letter-spacing:2px;color:#a0b8d8;text-transform:uppercase;">Sistema de Gestión</span>
                    <span style="font-size:1rem;font-weight:700;color:#fff;">Gerencia Regional La Paz</span>
                </div>
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('servidores.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.1);color:#fff;border:1px solid rgba(255,255,255,0.2);">
                    Servidores
                </a>
                <a href="{{ route('servidores.create') }}" class="btn btn-sm" style="background:#1565c0;color:#fff;border:none;">
                    + Agregar
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
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 id="tituloModal">Área</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="contenidoModal"></div>

            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/organigrama.js') }}"></script>

</body>

</html>