<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name', 'Berisso Mapeo') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-blue-50 text-slate-900">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.12),_transparent_40%),linear-gradient(#eff6ff,_white)]">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <header class="mb-8 flex flex-col gap-4 rounded-3xl border border-blue-100 bg-white/90 p-6 shadow-sm shadow-blue-100/70 backdrop-blur-sm md:flex-row md:items-center md:justify-between">
                <div>
                    <a href="{{ route('home') }}" class="text-2xl font-semibold text-blue-900">Berisso Mapeo</a>
                    <p class="mt-1 text-sm text-slate-600">Directorios locales de negocios y servicios.</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('home') }}" class="rounded-full border border-blue-200 bg-white px-4 py-2 text-sm text-blue-700 transition hover:bg-blue-50">Inicio</a>

                    @auth
                        <a href="{{ route('businesses.create') }}" class="rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">Publicar negocio</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="rounded-full border border-blue-200 bg-white px-4 py-2 text-sm text-blue-700 transition hover:bg-blue-50">Cerrar sesión</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-full border border-blue-200 bg-white px-4 py-2 text-sm text-blue-700 transition hover:bg-blue-50">Ingresar</a>
                        <a href="{{ route('register') }}" class="rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">Crear cuenta</a>
                    @endauth
                </div>
            </header>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-800 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 shadow-sm">
                    <ul class="space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</body>
</html>
