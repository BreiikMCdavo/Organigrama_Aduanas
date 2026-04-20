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
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ Request::is('servidores') ? url('/') : url('/servidores') }}">
                Sistema Aduana - Gerencia Regional La Paz
            </a>
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