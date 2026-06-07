@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- GALERÍA DE IMÁGENES -->
    <div class="rounded-3xl border border-marron-claro bg-white shadow-sm shadow-marron-claro/80 overflow-hidden">
        @php
            $allImages = collect();
            if($business->image) {
                $allImages->push($business->image);
            }
            foreach($business->images as $img) {
                $allImages->push($img->image_url);
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
                                onclick="document.getElementById('mainImage').src = '{{ $imgUrl }}'"
                                class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition-all {{ $index === 0 ? 'border-marron-medio' : 'border-marron-claro hover:border-marron-medio' }}">
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
    function changeImage(src) {
        document.getElementById('mainImage').src = src;
        // Actualizar borde activo en miniaturas
        document.querySelectorAll('.thumbnail-btn').forEach(btn => {
            btn.classList.remove('border-marron-medio');
            btn.classList.add('border-marron-claro');
        });
        event.currentTarget.classList.remove('border-marron-claro');
        event.currentTarget.classList.add('border-marron-medio');
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