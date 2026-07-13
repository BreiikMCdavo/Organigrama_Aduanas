<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso autorizado - Sistema Aduana</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background:
                linear-gradient(135deg, rgba(10, 22, 40, 0.92), rgba(26, 58, 107, 0.88)),
                url('{{ asset('img/logo_1.png') }}') no-repeat center center fixed;
            background-size: cover;
            color: #172033;
        }

        .login-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .login-card {
            width: min(100%, 430px);
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.28);
            overflow: hidden;
        }

        .login-header {
            padding: 26px 30px 20px;
            border-bottom: 1px solid #e7edf5;
        }

        .brand-logo {
            width: 152px;
            height: 56px;
            object-fit: contain;
            margin-bottom: 16px;
        }

        .login-title {
            font-size: 1.28rem;
            font-weight: 800;
            color: #0a3267;
            margin: 0;
        }

        .login-subtitle {
            color: #657086;
            font-size: 0.92rem;
            margin: 6px 0 0;
        }

        .login-body {
            padding: 26px 30px 30px;
        }

        .form-label {
            font-weight: 700;
            color: #26364f;
            font-size: 0.9rem;
        }

        .form-control {
            min-height: 46px;
            border-radius: 7px;
        }

        .btn-login {
            min-height: 46px;
            border-radius: 7px;
            background: linear-gradient(135deg, #0d6efd, #0a3267);
            border: 0;
            font-weight: 800;
        }

        .btn-login:hover {
            filter: brightness(1.04);
        }
    </style>
</head>

<body>
    <main class="login-shell">
        <section class="login-card">
            <div class="login-header">
                <img class="brand-logo" src="{{ asset('img/logo_aduana.png') }}" alt="Aduana Nacional">
                <h1 class="login-title">Ingreso autorizado</h1>
                <p class="login-subtitle">Solo personas registradas pueden entrar al sistema.</p>
            </div>

            <div class="login-body">
                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="email">Correo</label>
                        <input
                            id="email"
                            class="form-control"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            autofocus
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Contraseña</label>
                        <input
                            id="password"
                            class="form-control"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Mantener sesión iniciada
                        </label>
                    </div>

                    <button class="btn btn-primary btn-login w-100" type="submit">
                        Entrar
                    </button>
                </form>
            </div>
        </section>
    </main>
</body>

</html>
