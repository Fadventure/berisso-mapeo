<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
        </style>
    @endif
</head>
<body class="min-h-screen bg-blue-50 flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md rounded-3xl border border-blue-200 bg-white shadow-lg shadow-blue-100/50 overflow-hidden">
        <div class="bg-blue-600 px-8 py-8 text-white">
            <h1 class="text-3xl font-semibold">Bienvenido</h1>
            <p class="mt-2 text-blue-100">Inicia sesión con Nombre, Email y Contraseña.</p>
        </div>

        <div class="px-8 py-10">
            @if ($errors->has('authentication'))
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first('authentication') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-blue-900">Nombre</label>
                    <input id="name" name="name" value="{{ old('name') }}" required autofocus
                        class="mt-2 w-full rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-blue-900">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                        class="mt-2 w-full rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-blue-900">Contraseña</label>
                    <input id="password" name="password" type="password" required
                        class="mt-2 w-full rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between gap-4">
                    <label class="inline-flex items-center gap-2 text-sm text-blue-900">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-blue-300 text-blue-600 focus:ring-blue-500" />
                        Recordarme
                    </label>
                    <a href="{{ route('register') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Crear cuenta</a>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">Iniciar sesión</button>
            </form>
        </div>
    </div>
</body>
</html>
