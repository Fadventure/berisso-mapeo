@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
        <h1 class="text-3xl font-semibold text-marron-oscuro">Mis negocios</h1>
        <p class="mt-2 text-sm text-marron/80">Administrá los negocios que publicaste</p>
    </div>

    @if($myBusinesses->isEmpty())
        <div class="rounded-3xl border border-marron-claro bg-white p-12 text-center shadow-sm shadow-marron-claro/80">
            <p class="text-lg text-marron">Todavía no publicaste ningún negocio.</p>
            <a href="{{ route('businesses.create') }}" class="mt-4 inline-block rounded-2xl bg-marron-claro px-6 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">
                + Publicar mi primer negocio
            </a>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($myBusinesses as $business)
                <div class="rounded-3xl border border-marron-claro bg-white overflow-hidden shadow-sm shadow-marron-claro/80 transition hover:-translate-y-1 flex flex-col h-full">
                    <!-- ... imagen ... -->
                    <div class="p-4 flex flex-col flex-grow">
                        <div>
                            <span class="text-xs text-marron/70">{{ $business->category?->name ?? 'Sin categoría' }}</span>
                            
                            <!-- Badge de estado -->
                            <span class="text-xs ml-2 px-2 py-1 rounded-full 
                                @if($business->status === 'approved') bg-green-100 text-green-800
                                @elseif($business->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($business->status) }}
                            </span>
                            
                            <h3 class="mt-1 text-lg font-semibold text-marron-oscuro">{{ $business->name }}</h3>
                            
                            @if($business->status === 'pending')
                                <p class="text-xs text-yellow-600 mt-1">⏳ Pendiente de aprobación</p>
                            @elseif($business->status === 'rejected')
                                <p class="text-xs text-red-600 mt-1">❌ Rechazado</p>
                                @if($business->rejection_reason)
                                    <p class="text-xs text-red-500 mt-1">Motivo: {{ $business->rejection_reason }}</p>
                                @endif
                            @endif
                            
                            <!-- ... resto del contenido ... -->
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('businesses.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-marron-claro px-6 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">
                + Publicar nuevo negocio
            </a>
        </div>
    @endif
</div>
@endsection