<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva contraseña - Ayuntamiento Almussafes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-sky-50 via-white to-blue-50 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">

    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 shadow-lg mb-4">
            <i class="fas fa-lock text-white text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Establece tu nueva contraseña</h1>
        <p class="text-gray-500 text-sm mt-2">Elige una contraseña segura para tu cuenta</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf

            {{-- Token oculto que viene en la URL --}}
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-envelope text-sky-500 mr-1"></i> Correo electrónico
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
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

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-lock text-sky-500 mr-1"></i> Nueva contraseña
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        placeholder="Mínimo 8 caracteres"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 pr-12 bg-white focus:ring-2 focus:ring-sky-300 focus:outline-none focus:border-sky-400 transition-all duration-300 @error('password') border-red-400 ring-2 ring-red-200 @enderror"
                    >
                    <button type="button" onclick="togglePassword('password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye" id="icon-password"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-lock text-sky-500 mr-1"></i> Confirmar contraseña
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        placeholder="Repite la contraseña"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 pr-12 bg-white focus:ring-2 focus:ring-sky-300 focus:outline-none focus:border-sky-400 transition-all duration-300"
                    >
                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye" id="icon-password_confirmation"></i>
                    </button>
                </div>
            </div>

            {{-- Indicador de fortaleza --}}
            <div id="strength-bar" class="hidden">
                <div class="flex gap-1 mb-1">
                    <div class="h-1.5 flex-1 rounded-full bg-gray-200" id="s1"></div>
                    <div class="h-1.5 flex-1 rounded-full bg-gray-200" id="s2"></div>
                    <div class="h-1.5 flex-1 rounded-full bg-gray-200" id="s3"></div>
                    <div class="h-1.5 flex-1 rounded-full bg-gray-200" id="s4"></div>
                </div>
                <p class="text-xs text-gray-400" id="strength-text"></p>
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 text-white font-semibold hover:from-sky-600 hover:to-blue-700 transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Guardar nueva contraseña
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

<script>
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        const icon  = document.getElementById('icon-' + fieldId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    document.getElementById('password').addEventListener('input', function () {
        const val = this.value;
        const bar = document.getElementById('strength-bar');
        bar.classList.toggle('hidden', val.length === 0);

        let score = 0;
        if (val.length >= 8)          score++;
        if (/[A-Z]/.test(val))        score++;
        if (/[0-9]/.test(val))        score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const colors  = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-emerald-500'];
        const labels  = ['Muy débil', 'Débil', 'Buena', 'Muy segura'];

        for (let i = 1; i <= 4; i++) {
            const seg = document.getElementById('s' + i);
            seg.className = 'h-1.5 flex-1 rounded-full ' + (i <= score ? colors[score - 1] : 'bg-gray-200');
        }
        document.getElementById('strength-text').textContent = score > 0 ? labels[score - 1] : '';
    });
</script>

</body>
</html>