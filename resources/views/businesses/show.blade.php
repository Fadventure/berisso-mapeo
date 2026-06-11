@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- GALERÍA DE IMÁGENES -->
    <div class="rounded-3xl border border-marron-claro bg-white shadow-sm shadow-marron-claro/80 overflow-hidden">
        @php
            $allImages = collect();
            
            // Procesar imagen principal del negocio
            if($business->image) {
                if (Str::startsWith($business->image, ['http://', 'https://'])) {
                    $allImages->push($business->image);
                } else {
                    $allImages->push(Storage::url($business->image));
                }
            }
            
            // Procesar imágenes adicionales de la galería
            foreach($business->images as $img) {
                if (Str::startsWith($img->image_url, ['http://', 'https://'])) {
                    $allImages->push($img->image_url);
                } else {
                    $allImages->push(Storage::url($img->image_url));
                }
            }
            $allImages = $allImages->unique();
        @endphp

        @if($allImages->count() > 0)
            <div class="relative">
                <!-- Imagen principal visible -->
                <div class="relative h-64 md:h-96 w-full overflow-hidden bg-piel">
                    <img id="mainImage" src="{{ $allImages->first() }}" alt="{{ $business->name }}" class="h-full w-full object-cover" />
                </div>

                <!-- Miniaturas / Galería -->
                @if($allImages->count() > 1)
                    <div class="flex gap-2 p-3 overflow-x-auto bg-white border-t border-marron-claro">
                        @foreach($allImages as $index => $imgUrl)
                            <button 
                                onclick="document.getElementById('mainImage').src = '{{ $imgUrl }}'; updateActiveThumbnail(this)"
                                class="thumbnail-btn flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition-all {{ $index === 0 ? 'border-marron-medio' : 'border-marron-claro hover:border-marron-medio' }}">
                                <img src="{{ $imgUrl }}" alt="Miniatura {{ $index + 1 }}" class="w-full h-full object-cover" />
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
        
        <div class="p-6 md:p-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex-1">
                    <span class="inline-flex rounded-full border border-marron-claro bg-piel px-3 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-marron-oscuro">
                        {{ $business->category?->name ?? 'Sin categoría' }}
                    </span>
                    <h1 class="mt-3 text-3xl md:text-4xl font-bold text-marron-oscuro">{{ $business->name }}</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Diseño de dos columnas -->
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- COLUMNA IZQUIERDA -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Sobre el negocio -->
            <div class="rounded-3xl border border-marron-claro bg-white p-6 md:p-8 shadow-sm shadow-marron-claro/80">
                <h2 class="text-xl font-semibold text-marron-oscuro">Sobre el negocio</h2>
                <p class="mt-4 text-base leading-relaxed text-marron">
                    {{ $business->description ?? 'No hay descripción disponible para este negocio.' }}
                </p>
            </div>

            <!-- Ubicación / Mapa -->
            <div class="rounded-3xl border border-marron-claro bg-white p-6 md:p-8 shadow-sm shadow-marron-claro/80">
                <h2 class="text-xl font-semibold text-marron-oscuro mb-4">Ubicación</h2>
                
                @if($business->address)
                    <p class="text-marron font-medium mb-4">{{ $business->address }}</p>
                @endif

                <div class="rounded-2xl overflow-hidden border border-marron-claro">
                    <div id="map" class="h-80 w-full" style="background: #f5eadd;"></div>
                </div>

                @if($business->address)
                    @php
                        $addressEncoded = urlencode($business->address . ', Berisso, Provincia de Buenos Aires, Argentina');
                    @endphp
                    <div class="flex flex-wrap gap-3 mt-4">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $addressEncoded }}" 
                           target="_blank" 
                           rel="noreferrer"
                           class="inline-flex items-center gap-2 rounded-2xl bg-marron-claro px-5 py-2.5 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">
                            📍 Abrir en Google Maps
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- COLUMNA DERECHA - BARRA LATERAL -->
        <div class="space-y-6">
            <!-- Información de contacto -->
            <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
                <h3 class="text-lg font-semibold text-marron-oscuro mb-4">Información de contacto</h3>
                
                <div class="space-y-4">
                    @if($business->hours)
                        <div>
                            <p class="text-sm font-semibold text-marron-oscuro uppercase tracking-wide">Horario</p>
                            <p class="mt-1 text-marron">{{ $business->hours }}</p>
                        </div>
                    @endif

                    @if($business->phone)
                        <div>
                            <p class="text-sm font-semibold text-marron-oscuro uppercase tracking-wide">Teléfono</p>
                            <p class="mt-1 text-marron">{{ $business->phone }}</p>
                        </div>
                    @endif

                    @if($business->phone)
                        <div>
                            <p class="text-sm font-semibold text-marron-oscuro uppercase tracking-wide">WhatsApp</p>
                            <p class="mt-1 text-marron">{{ $business->phone }}</p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $business->phone) }}" 
                               target="_blank"
                               class="inline-flex items-center gap-2 mt-2 rounded-2xl bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-700">
                                💬 Escribir por WhatsApp
                            </a>
                        </div>
                    @endif

                    <div class="pt-2">
                        <p class="text-sm font-semibold text-marron-oscuro uppercase tracking-wide mb-2">Redes sociales</p>
                        <div class="space-y-2">
                            @if($business->facebook)
                                <a href="{{ $business->facebook }}" 
                                target="_blank" 
                                rel="noreferrer"
                                class="inline-flex items-center gap-2 text-marron-oscuro hover:text-marron-medio transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12z"/>
                                    </svg>
                                    Facebook
                                </a>
                            @endif

                            @if($business->instagram)
                                <a href="{{ $business->instagram }}" 
                                target="_blank" 
                                rel="noreferrer"
                                class="inline-flex items-center gap-2 text-marron-oscuro hover:text-marron-medio transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                                    </svg>
                                    Instagram
                                </a>
                            @endif

                            @if(!$business->facebook && !$business->instagram)
                                <p class="text-sm text-marron/60">No hay redes sociales cargadas</p>
                            @endif
                        </div>
                    </div>

                    @if($business->email_lugar)
                        <div>
                            <p class="text-sm font-semibold text-marron-oscuro uppercase tracking-wide">Correo electrónico</p>
                            <p class="mt-1 text-marron">{{ $business->email_lugar }}</p>
                        </div>
                    @endif

                    @if($business->website)
                        <div class="pt-2">
                            <a href="{{ $business->website }}" 
                               target="_blank" 
                               rel="noreferrer"
                               class="inline-flex items-center gap-2 text-marron-oscuro underline hover:text-marron-medio">
                                🌐 Visitar sitio web
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Publicado por -->
            <div class="rounded-3xl border border-marron-claro bg-piel p-6 shadow-sm shadow-marron-claro/80">
                <p class="text-sm text-marron">
                    <span class="font-semibold">Publicado por:</span><br>
                    {{ $business->user?->name ?? 'Usuario invitado' }}
                </p>
                <p class="text-xs text-marron/70 mt-2">
                    📅 {{ $business->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Botón Volver -->
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('home') }}" 
           class="rounded-2xl border border-marron-claro bg-white px-6 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-piel-oscuro">
            ← Volver al directorio
        </a>
    </div>
</div>

<script>
    function updateActiveThumbnail(clickedButton) {
        document.querySelectorAll('.thumbnail-btn').forEach(btn => {
            btn.classList.remove('border-marron-medio');
            btn.classList.add('border-marron-claro');
        });
        clickedButton.classList.remove('border-marron-claro');
        clickedButton.classList.add('border-marron-medio');
    }
</script>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@if($business->address)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map').setView([-34.8731, -57.8867], 13);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);
        
        var address = "{{ addslashes($business->address . ', Berisso, Argentina') }}";
        
        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address) + '&limit=1')
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    var lat = data[0].lat;
                    var lon = data[0].lon;
                    map.setView([lat, lon], 15);
                    L.marker([lat, lon])
                        .addTo(map)
                        .bindPopup('<strong>{{ addslashes($business->name) }}</strong><br>{{ addslashes($business->address) }}')
                        .openPopup();
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>
@endif
@endpush