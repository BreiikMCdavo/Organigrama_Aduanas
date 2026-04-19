<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Aduana</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/organigrama.css') }}">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/servidores') }}">
            Sistema Aduana - Gerencia Regional La Paz
        </a>
    </div>
</nav>

<!-- CONTENIDO DINÁMICO -->
<div class="py-4">
    @yield('content')
</div>

<!-- SCRIPTS -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/organigrama.js') }}"></script>

</body>
</html>