<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña - Ayuntamiento Almussafes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-sky-50 via-white to-blue-50 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">

    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 shadow-lg mb-4">
            <i class="fas fa-key text-white text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">¿Olvidaste tu contraseña?</h1>
        <p class="text-gray-500 text-sm mt-2">Introduce tu email y te enviaremos un enlace para restablecerla</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <span class="text-sm font-medium">{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-envelope text-sky-500 mr-1"></i> Correo electrónico
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="tu@email.com"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-sky-300 focus:outline-none focus:border-sky-400 transition-all duration-300 @error('email') border-red-400 ring-2 ring-red-200 @enderror"
                >
                @error('email')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 text-white font-semibold hover:from-sky-600 hover:to-blue-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane"></i> Enviar enlace de recuperación
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-sky-600 hover:text-sky-800 font-medium transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
            </a>
        </div>
    </div>

    <p class="text-center text-xs text-gray-400 mt-6">
        Ayuntamiento de Almussafes · Gestión Presupuestaria 2026
    </p>
</div>

</body>
</html>