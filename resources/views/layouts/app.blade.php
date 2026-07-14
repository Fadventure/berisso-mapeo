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
<body class="min-h-screen bg-piel text-marron-texto font-medium">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(210,180,140,0.15),_transparent_40%),_linear-gradient(#FDF8F0,_#F5EADD)]">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <header class="mb-8 flex flex-col gap-4 rounded-3xl border border-marron-claro bg-white/90 p-6 shadow-sm shadow-marron-claro/70 backdrop-blur-sm md:flex-row md:items-center md:justify-between">
                <div>
                    <a href="{{ route('home') }}" class="flex items-center gap-4">
                        <!-- LOGO CON INICIALES (Opción 3) -->
                        <img src="{{ asset('images/Logo2.png') }}" 
                             alt="Berisso Mapeo" 
                             class="h-14 h-18 w-18 rounded-full">
                        <div>
                            <span class="text-3xl font-semibold text-marron-oscuro">Berisso Market</span>
                            <p class="mt-1 text-sm text-marron/80 hidden md:block">Direccion de los negocios locales y servicios.</p>
                        </div>
                    </a>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('home') }}" class="rounded-full border border-marron-claro bg-white px-4 py-2 text-sm text-marron-oscuro transition hover:bg-piel-oscuro">Inicio</a>

                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" 
                               class="rounded-full border border-marron-claro bg-amber-100 px-4 py-2 text-sm text-amber-800 transition hover:bg-amber-200">
                                Admin
                            </a>
                        @endif
                    
                        <a href="{{ route('dashboard') }}" class="rounded-full border border-marron-claro bg-white px-4 py-2 text-sm text-marron-oscuro transition hover:bg-piel-oscuro">
                            Mis negocios
                        </a>
                        <a href="{{ route('businesses.create') }}" class="rounded-full bg-marron-claro px-4 py-2 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">Publicar negocio</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="rounded-full border border-marron-claro bg-white px-4 py-2 text-sm text-marron-oscuro transition hover:bg-piel-oscuro">Cerrar sesión</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-full border border-marron-claro bg-white px-4 py-2 text-sm text-marron-oscuro transition hover:bg-piel-oscuro">Ingresar</a>
                        <a href="{{ route('register') }}" class="rounded-full bg-marron-claro px-4 py-2 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">Crear cuenta</a>
                    @endauth
                </div>  
            </header>

            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-marron-claro/50 bg-piel-oscuro px-5 py-4 text-marron-oscuro shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-300 bg-red-50 px-5 py-4 text-red-800 shadow-sm">
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

    @stack('scripts')
</body>
</html>