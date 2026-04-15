@extends('layouts.app')

@section('content')
    <style>
        /* Override global styles for Login Page */
        body {
            background: #1a365d; /* Professional Navy */
            background-image: radial-gradient(#2c5282 0.5px, transparent 0.5px);
            background-size: 30px 30px;
            overflow: hidden;
        }

        #wrapper {
            background: transparent !important;
        }

        #page-content-wrapper {
            background: transparent !important;
            padding: 0 !important;
        }

        .navbar,
        footer {
            display: none !important;
        }

        .login-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #1a365d;
        }

        .card-login {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }

        .login-header {
            background: transparent;
            padding: 2.5rem 2rem 1rem;
            text-align: center;
        }

        .login-icon {
            width: 80px;
            height: 80px;
            background: #1e3a8a;
            color: white;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-control-lg {
            border-radius: 0.75rem;
            padding: 0.75rem 1.25rem;
            font-size: 1rem;
            border: 2px solid #e9ecef;
        }

        .form-control-lg:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        .btn-login {
            padding: 0.8rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 0.75rem;
            background: #1a365d;
            border: none;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: #102a43;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .copyright {
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            margin-top: 2rem;
            font-size: 0.85rem;
        }
    </style>

    <div class="login-container">
        <div class="w-100" style="max-width: 450px;">
            <div class="card card-login animated fadeInDown">
                <div class="login-header">
                    <div class="login-icon">
                        <i class="bi bi-hospital"></i>
                    </div>
                    <h3 class="fw-bold text-dark">Portal del Agente</h3>
                    <p class="text-muted mb-0">Bienvenido al sistema de gestión</p>
                </div>

                <div class="card-body p-4 pt-2">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold px-1">IDENTIFICACIÓN</label>
                            <div class="input-group">
                                <span
                                    class="input-group-text bg-white border-end-0 border-2 rounded-start-pill ps-3 text-primary">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <input id="tarjeta" type="text"
                                    class="form-control form-control-lg border-start-0 ps-2 @error('tarjeta') is-invalid @enderror"
                                    name="tarjeta" value="{{ old('tarjeta') }}" required autofocus
                                    placeholder="N° Tarjeta o Legajo">
                            </div>
                            @error('tarjeta')
                                <div class="text-danger small mt-1 ps-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold px-1">SEGURIDAD</label>
                            <div class="input-group">
                                <span
                                    class="input-group-text bg-white border-end-0 border-2 rounded-start-pill ps-3 text-primary">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input id="password" type="password"
                                    class="form-control form-control-lg border-start-0 ps-2 @error('password') is-invalid @enderror"
                                    name="password" required placeholder="Contraseña">
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1 ps-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-primary btn-login text-white">
                                Ingresar al Sistema
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="copyright">
                &copy; 2026 <strong>Portal Hospital</strong><br>
                Departamento de Sistemas
            </div>
        </div>
    </div>
@endsection
