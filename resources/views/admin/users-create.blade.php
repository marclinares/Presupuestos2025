<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap');

        /* Fondo */
        .min-h-screen {
            background-color: #f8fafc !important;
        }

        /* Tarjeta principal */
        .admin-card {
            font-family: 'DM Sans', sans-serif;
            max-width: 400px;
            margin: 0 auto;
        }

        /* Logo */
        .logo-container {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 2rem;
        }
        
        .admin-logo {
            height: 65px;
            width: auto;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05));
        }

        /* Encabezados */
        .admin-eyebrow {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #9CA3AF;
            margin-bottom: 0.4rem;
        }

        .admin-title {
            font-family: 'DM Serif Display', serif;
            font-size: 32px;
            font-weight: 400;
            color: #111827;
            line-height: 1.1;
            margin-bottom: 0.4rem;
        }

        .admin-title em {
            font-style: italic;
            color: #0ea5e9;
        }

        .admin-divider {
            width: 32px;
            height: 3px;
            background: #00569d;
            border-radius: 2px;
            margin-bottom: 2rem;
        }

        /* Formularios */
        .admin-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #4b5563;
            margin-bottom: 6px;
        }

        .admin-input {
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

        .admin-input:focus {
            border-color: #00569d;
            box-shadow: 0 0 0 4px rgba(0, 86, 157, 0.1);
        }

        /* Botones */
        .admin-btn-primary {
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
            width: 100%;
        }

        .admin-btn-primary:hover {
            background: #00447c !important;
            transform: translateY(-1px);
        }
    </style>

    <div class="admin-card py-12">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Ayuntamiento" class="admin-logo">
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <p class="admin-eyebrow">Acceso Admin</p>
        <h1 class="admin-title">Panel de <em>Control</em></h1>
        <div class="admin-divider"></div>

        <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="admin-label" for="name">Nombre Completo</label>
                {{-- Añadido old('name') --}}
                <input type="text" name="name" id="name" class="admin-input" 
                       value="{{ old('name') }}" placeholder="Ej. Juan Pérez" required autofocus>
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-red-500" />
            </div>

            <div>
                <label class="admin-label" for="email">Correo Electrónico</label>
                {{-- Añadido old('email') --}}
                <input type="email" name="email" id="email" class="admin-input" 
                       value="{{ old('email') }}" placeholder="juan@almussafes.es" required>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500" />
            </div>

            <div>
                <label class="admin-label" for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="admin-input" required>
            </div>

            <div>
                <label class="admin-label" for="password_confirmation">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="admin-input" required>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500" />
            </div>

            <div class="pt-4">
                <button type="submit" class="admin-btn-primary">
                    Registrar Usuario →
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