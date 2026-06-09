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
                {{-- Tarjeta con flexbox para mantener el botón abajo --}}
                <div class="rounded-3xl border border-marron-claro bg-white overflow-hidden shadow-sm shadow-marron-claro/80 transition hover:-translate-y-1 flex flex-col h-full">
                    
                    {{-- Imagen - altura fija --}}
                    @if($business->image)
                        <div class="h-40 w-full overflow-hidden flex-shrink-0">
                            @php
                                $imageSrc = null;
                                if (Str::startsWith($business->image, ['http://', 'https://'])) {
                                    $imageSrc = $business->image;
                                } else {
                                    $imageSrc = Storage::url($business->image);
                                }
                            @endphp
                            <img src="{{ $imageSrc }}" alt="{{ $business->name }}" class="h-full w-full object-cover" />
                        </div>
                    @else
                        <div class="h-40 w-full bg-piel flex items-center justify-center text-marron/60 flex-shrink-0">
                            Sin imagen
                        </div>
                    @endif
                    
                    {{-- Contenido - flex-grow para ocupar espacio disponible --}}
                    <div class="p-4 flex flex-col flex-grow">
                        <div>
                            <span class="text-xs text-marron/70">{{ $business->category?->name ?? 'Sin categoría' }}</span>
                            <h3 class="mt-1 text-lg font-semibold text-marron-oscuro">{{ $business->name }}</h3>
                            
                            {{-- Descripción --}}
                            <p class="mt-2 text-sm text-marron/80 line-clamp-2">{{ $business->description ?? 'Sin descripción' }}</p>
                        </div>
                        
                        {{-- Botón - mt-auto lo empuja al fondo --}}
                        <div class="mt-auto pt-4">
                            <a href="{{ route('businesses.show', $business) }}" class="block text-center rounded-xl bg-marron-claro px-3 py-2 text-sm font-medium text-marron-oscuro transition hover:bg-marron-medio hover:text-white">
                                Ver
                            </a>
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