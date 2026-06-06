@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
        <h1 class="text-3xl font-semibold text-marron-oscuro">Panel de usuario</h1>
        <p class="mt-2 text-sm text-marron/80">Has iniciado sesión correctamente.</p>
    </div>

    <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
        <div class="space-y-4">
            <p class="text-marron-oscuro text-lg">Hola, <strong>{{ auth()->user()->name }}</strong>.</p>
            <p class="text-marron">Tu email registrado es <strong>{{ auth()->user()->email }}</strong>.</p>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('businesses.create') }}" class="rounded-2xl bg-marron-claro px-6 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">
                    Publicar nuevo negocio
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-2xl border border-marron-claro bg-white px-6 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-piel-oscuro">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
        <h2 class="text-xl font-semibold text-marron-oscuro">Tus negocios publicados</h2>
        <p class="mt-2 text-sm text-marron/80">Aquí aparecerán los negocios que hayas creado</p>
        
        <div class="mt-4 text-center text-marron/60 py-8">
            <p>Todavía no has publicado ningún negocio.</p>
            <a href="{{ route('businesses.create') }}" class="mt-2 inline-block text-marron-oscuro underline hover:text-marron-medio">
                Publicar mi primer negocio →
            </a>
        </div>
    </div>
</div>
@endsection