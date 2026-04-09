<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap');

        /* Sobreescribir el fondo de Breeze si fuera necesario */
        .min-h-screen {
            background-color: #f8fafc !important;
        }

        .login-card {
            font-family: 'DM Sans', sans-serif;
            max-width: 400px;
            margin: 0 auto;
        }
        
        /* Contenedor del logo del Ayuntamiento */
        .logo-container {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 2rem;
        }
        
        .login-logo {
            height: 65px;
            width: auto;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05));
        }

        .login-eyebrow {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #9CA3AF;
            margin-bottom: 0.4rem;
        }

        .login-title {
            font-family: 'DM Serif Display', serif;
            font-size: 32px;
            font-weight: 400;
            color: #111827;
            line-height: 1.1;
            margin-bottom: 0.4rem;
        }

        .login-title em {
            font-style: italic;
            color: #0ea5e9; /* Azul acento */
        }

        .login-divider {
            width: 32px;
            height: 3px;
            background: #00569d; /* Azul corporativo */
            border-radius: 2px;
            margin-bottom: 2rem;
        }

        .login-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #4b5563;
            margin-bottom: 6px;
        }

        .login-input {
            width: 100%;
            background: #ffffff;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 12px 16px;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            color: #111827;
            outline: none;
            transition: all 0.2s;
        }

        .login-input:focus {
            border-color: #00569d;
            box-shadow: 0 0 0 4px rgba(0, 86, 157, 0.1);
        }

        .login-btn-primary {
            background: #00569d !important;
            color: #fff !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 12px 28px !important;
            font-family: 'DM Sans', sans-serif !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            cursor: pointer;
            transition: all 0.2s !important;
            box-shadow: 0 4px 12px rgba(0, 86, 157, 0.2);
        }

        .login-btn-primary:hover {
            background: #00447c !important;
            transform: translateY(-1px);
        }

        .login-forgot {
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
        }

        .login-forgot:hover {
            color: #00569d;
        }
    </style>

    <div class="login-card">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Ayuntamiento" class="login-logo">
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <p class="login-eyebrow">Acceso exclusivo</p>
        <h1 class="login-title">Inicia <em>sesión</em></h1>
        <div class="login-divider"></div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-6">
                <label class="login-label" for="email">Email</label>
                <input id="email" class="login-input" type="email" name="email"
                    value="{{ old('email') }}" required autofocus autocomplete="username"
                    placeholder="usuario@almussafes.es" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500" />
            </div>

            <div class="mb-6">
                <label class="login-label" for="password">Contraseña</label>
                <input id="password" class="login-input" type="password" name="password"
                    required autocomplete="current-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500" />
            </div>

            <div class="flex items-center gap-3 mb-8">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    style="accent-color: #00569d;">
                <label for="remember_me" class="text-sm text-gray-500 cursor-pointer font-medium">
                    Recordarme
                </label>
            </div>

            <div class="flex items-center justify-between">
                @if (Route::has('password.request'))
                    <a class="login-forgot" href="{{ route('password.request') }}">
                        ¿Olvidaste la clave?
                    </a>
                @endif

                <button type="submit" class="login-btn-primary">
                    Entrar →
                </button>
            </div>
        </form>

        <div class="mt-12 pt-6 border-t border-gray-100">
            <a href="{{ url('/') }}" class="text-xs text-gray-400 hover:text-blue-600 transition-colors flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Volver a la web
            </a>
        </div>
    </div>
</x-guest-layout>