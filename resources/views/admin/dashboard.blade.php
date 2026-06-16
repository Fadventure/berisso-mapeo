@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
        <h1 class="text-3xl font-semibold text-marron-oscuro">Panel de Administración</h1>
        <p class="mt-2 text-sm text-marron/80">Gestioná los negocios pendientes de aprobación.</p>
    </div>

    <!-- Estadísticas -->
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-marron-claro bg-white p-6 text-center shadow-sm shadow-marron-claro/80">
            <p class="text-3xl font-bold text-yellow-600">{{ $pendingCount }}</p>
            <p class="text-sm text-marron/80">⏳ Pendientes</p>
        </div>
        <div class="rounded-3xl border border-marron-claro bg-white p-6 text-center shadow-sm shadow-marron-claro/80">
            <p class="text-3xl font-bold text-green-600">{{ $approvedCount }}</p>
            <p class="text-sm text-marron/80">✅ Aprobados</p>
        </div>
        <div class="rounded-3xl border border-marron-claro bg-white p-6 text-center shadow-sm shadow-marron-claro/80">
            <p class="text-3xl font-bold text-red-600">{{ $rejectedCount }}</p>
            <p class="text-sm text-marron/80">❌ Rechazados</p>
        </div>
    </div>

    <!-- Negocios pendientes -->
    <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
        <h2 class="text-xl font-semibold text-marron-oscuro mb-4">📋 Negocios pendientes de revisión</h2>
        
        @if($pendingBusinesses->isEmpty())
            <p class="text-center text-marron/60 py-8">✅ ¡No hay negocios pendientes por revisar!</p>
        @else
            <div class="space-y-4">
                @foreach($pendingBusinesses as $business)
                    <div class="border border-marron-claro rounded-2xl p-4 bg-piel/30">
                        <div class="flex flex-wrap justify-between items-start gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-semibold text-marron-oscuro bg-white px-2 py-1 rounded-full border border-marron-claro">
                                        {{ $business->category?->name ?? 'Sin categoría' }}
                                    </span>
                                    <span class="text-xs text-marron/60">
                                        👤 {{ $business->user?->name ?? 'Usuario desconocido' }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-semibold text-marron-oscuro">{{ $business->name }}</h3>
                                <p class="text-sm text-marron/80 mt-1 line-clamp-2">{{ $business->description ?? 'Sin descripción' }}</p>
                                <p class="text-sm text-marron/80 mt-1">📍 {{ $business->address }}</p>
                                <p class="text-xs text-marron/60 mt-1">📅 Creado: {{ $business->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.businesses.show', $business) }}" 
                                   class="rounded-xl bg-marron-claro px-4 py-2 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">
                                    Ver detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection