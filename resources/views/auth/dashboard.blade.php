<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
        </style>
    @endif
</head>
<body class="min-h-screen bg-blue-50 flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-2xl rounded-3xl border border-blue-200 bg-white shadow-lg shadow-blue-100/50 overflow-hidden">
        <div class="bg-blue-600 px-8 py-8 text-white">
            <h1 class="text-3xl font-semibold">Panel de usuario</h1>
            <p class="mt-2 text-blue-100">Has iniciado sesión correctamente.</p>
        </div>

        <div class="px-8 py-10 space-y-6">
            <p class="text-blue-900 text-lg">Hola, <strong>{{ auth()->user()->name }}</strong>.</p>
            <p class="text-blue-700">Tu email registrado es <strong>{{ auth()->user()->email }}</strong>.</p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">Cerrar sesión</button>
            </form>
        </div>
    </div>
</body>
</html>
