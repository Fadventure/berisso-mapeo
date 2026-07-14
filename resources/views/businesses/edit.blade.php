@extends('layouts.app')

@section('content')
<div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-marron-oscuro">Modificá los datos de tu comercio.</h1>
        @if($business->status === 'pending')
            <p class="mt-2 text-sm text-yellow-600">⏳ Este negocio está pendiente de revisión.</p>
        @elseif($business->status === 'rejected')
            <p class="mt-2 text-sm text-red-600">❌ Este negocio fue rechazado.</p>
            @if($business->rejection_reason)
                <p class="mt-1 text-sm text-red-500">Motivo: {{ $business->rejection_reason }}</p>
            @endif
        @elseif($business->status === 'approved')
            <p class="mt-2 text-sm text-green-600">✅ Este negocio está aprobado y visible al público.</p>
        @endif
    </div>

    <form method="POST" action="{{ route('businesses.update', $business) }}" enctype="multipart/form-data" class="space-y-6" id="editForm">
        @csrf
        @method('PUT')

        <!-- Campos básicos -->
        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-marron-oscuro">Nombre del negocio *</label>
                <input id="name" name="name" value="{{ old('name', $business->name) }}" required
                    class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-marron-oscuro">Categoría *</label>
                <select id="category_id" name="category_id" required
                    class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30">
                    <option value="">Selecciona una categoría</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $business->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-marron-oscuro">Descripción</label>
            <textarea id="description" name="description" rows="5"
                class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30">{{ old('description', $business->description) }}</textarea>
            @error('description')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <label for="address" class="block text-sm font-medium text-marron-oscuro">Dirección *</label>
                <input id="address" name="address" value="{{ old('address', $business->address) }}" required
                    placeholder="Ej: Calle 7 y 22, Berisso"
                    class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                @error('address')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-marron-oscuro">Teléfono</label>
                <input id="phone" name="phone" value="{{ old('phone', $business->phone) }}"
                    class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- MAPA -->
        <div class="border border-marron-claro rounded-2xl p-4 bg-piel/30">
            <div class="flex flex-wrap gap-3 mb-3">
                <button type="button" id="search-address" 
                    class="rounded-2xl bg-marron-claro px-4 py-2 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">
                    🔍 Buscar dirección en el mapa
                </button>
                <button type="button" id="get-current-location" 
                    class="rounded-2xl border border-marron-claro bg-white px-4 py-2 text-sm font-semibold text-marron-oscuro transition hover:bg-piel-oscuro">
                    📍 Usar mi ubicación
                </button>
            </div>

            <div id="location-map" class="rounded-2xl overflow-hidden border border-marron-claro shadow-sm" style="height: 350px; width: 100%; background-color: #e9e0d1;"></div>
            
            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $business->latitude) }}">
            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $business->longitude) }}">
            
            <p class="text-xs text-marron/60 mt-2">
                💡 Podés arrastrar el marcador para ajustar la ubicación exacta.
            </p>
        </div>

        <!-- ========================================== -->
        <!-- SELECTOR DE HORARIOS -->
        <!-- ========================================== -->
        @php
            // Función para parsear el horario guardado
            function parseHorario($horarioString) {
                if (empty($horarioString) || $horarioString === 'Abierto 24 horas') {
                    return [];
                }
                
                // Formato: "Lun–Sáb 7:30–13:00 y 16:00–19:30"
                if (preg_match('/([A-Za-záéíóúñÑ]+)\s*[–\-]\s*([A-Za-záéíóúñÑ]+)\s*([\d:]+)\s*[–\-]\s*([\d:]+)(?:\s*y\s*([\d:]+)\s*[–\-]\s*([\d:]+))?/', $horarioString, $matches)) {
                    $dias = [
                        'Lun' => 'Lunes', 'Mar' => 'Martes', 'Mié' => 'Miércoles',
                        'Jue' => 'Jueves', 'Vie' => 'Viernes', 'Sáb' => 'Sábado', 'Dom' => 'Domingo'
                    ];
                    
                    $diaInicio = $dias[$matches[1]] ?? $matches[1];
                    $diaFin = $dias[$matches[2]] ?? $matches[2];
                    $hora1 = $matches[3];
                    $hora2 = $matches[4];
                    
                    $turnos = ["{$hora1} - {$hora2}"];
                    if (isset($matches[5]) && isset($matches[6])) {
                        $turnos[] = "{$matches[5]} - {$matches[6]}";
                    }
                    
                    return [
                        $diaInicio => $turnos,
                        $diaFin => $turnos
                    ];
                }
                
                // Formato: "Lunes: 09:00 - 13:00, 16:00 - 20:00"
                $resultado = [];
                $partes = explode(' | ', $horarioString);
                
                foreach ($partes as $parte) {
                    if (strpos($parte, ':') !== false) {
                        @list($dia, $horarios) = explode(': ', $parte, 2);
                        if ($horarios) {
                            $turnos = explode(', ', $horarios);
                            $resultado[$dia] = $turnos;
                        }
                    }
                }
                
                return $resultado;
            }
            
            $horarioParseado = parseHorario($business->hours);
            $diasSeleccionados = array_keys($horarioParseado);
            $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        @endphp

        <div class="border-t border-marron-claro pt-6">
            <h3 class="text-lg font-semibold text-marron-oscuro mb-4">🕐 Horario de atención</h3>
            <p class="text-sm text-marron/80 mb-4">Seleccioná los días y horarios de atención.</p>

            <div class="space-y-4">
                <!-- Días de la semana -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                    @foreach($diasSemana as $dia)
                        <label class="flex items-center gap-2 text-sm text-marron-oscuro bg-piel/50 px-3 py-2 rounded-xl border border-marron-claro cursor-pointer hover:bg-piel">
                            <input type="checkbox" name="dias_seleccionados[]" value="{{ $dia }}" 
                                class="rounded border-marron-claro text-marron-medio focus:ring-marron-claro/30"
                                {{ in_array($dia, old('dias_seleccionados', $diasSeleccionados)) ? 'checked' : '' }}
                                onchange="toggleDia(this, '{{ $dia }}')">
                            <span>{{ $dia }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Contenedor de horarios por día -->
                <div id="horarios-container" class="space-y-4 mt-4">
                    @foreach($diasSemana as $dia)
                        @php
                            $diaSlug = Str::slug($dia);
                            $turnosDelDia = $horarioParseado[$dia] ?? ['09:00 - 13:00'];
                            if (empty($turnosDelDia)) {
                                $turnosDelDia = ['09:00 - 13:00'];
                            }
                            $diaSeleccionado = in_array($dia, old('dias_seleccionados', $diasSeleccionados));
                        @endphp
                        <div id="dia-{{ $diaSlug }}" class="dia-container {{ $diaSeleccionado ? '' : 'hidden' }} border border-marron-claro rounded-2xl p-4 bg-piel/20">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-semibold text-marron-oscuro">{{ $dia }}</h4>
                                <button type="button" class="text-sm text-red-500 hover:text-red-700" onclick="eliminarDia('{{ $diaSlug }}')">
                                    Eliminar día
                                </button>
                            </div>
                            
                            <div class="turnos-container space-y-3" id="turnos-{{ $diaSlug }}">
                                @foreach($turnosDelDia as $turnoIndex => $turnoStr)
                                    @php
                                        $partesTurno = explode(' - ', $turnoStr);
                                        $desde = $partesTurno[0] ?? '09:00';
                                        $hasta = $partesTurno[1] ?? '13:00';
                                    @endphp
                                    <div class="turno-item grid grid-cols-2 gap-3 items-end relative">
                                        <div>
                                            <label class="block text-xs font-medium text-marron-oscuro mb-1">Desde</label>
                                            <input type="time" name="horarios[{{ $dia }}][turnos][{{ $turnoIndex }}][desde]" 
                                                value="{{ old("horarios.{$dia}.turnos.{$turnoIndex}.desde", $desde) }}"
                                                class="w-full rounded-xl border border-marron-claro bg-white px-3 py-2 text-sm text-marron outline-none focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-marron-oscuro mb-1">Hasta</label>
                                            <input type="time" name="horarios[{{ $dia }}][turnos][{{ $turnoIndex }}][hasta]" 
                                                value="{{ old("horarios.{$dia}.turnos.{$turnoIndex}.hasta", $hasta) }}"
                                                class="w-full rounded-xl border border-marron-claro bg-white px-3 py-2 text-sm text-marron outline-none focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30">
                                        </div>
                                        @if($turnoIndex > 0)
                                            <button type="button" class="absolute -top-2 -right-2 text-red-500 hover:text-red-700 text-sm bg-white rounded-full w-6 h-6 flex items-center justify-center border border-marron-claro" onclick="eliminarTurno(this)">
                                                ×
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="mt-3 text-sm text-marron-oscuro hover:text-marron-medio underline" 
                                onclick="agregarTurno('{{ $diaSlug }}')">
                                + Agregar turno
                            </button>
                        </div>
                    @endforeach
                </div>

                <!-- Opción 24hs -->
                <div class="mt-4">
                    <label class="flex items-center gap-2 text-sm text-marron-oscuro">
                        <input type="checkbox" name="abierto_24hs" value="1" 
                            class="rounded border-marron-claro text-marron-medio focus:ring-marron-claro/30"
                            {{ old('abierto_24hs', $business->hours === 'Abierto 24 horas' ? true : false) ? 'checked' : '' }}
                            onchange="toggle24hs(this)">
                        <span>🕛 Abierto 24 horas (ej: Farmacia de turno)</span>
                    </label>
                </div>

                <input type="hidden" name="hours" id="hours-input" value="{{ old('hours', $business->hours) }}">
            </div>
        </div>

        <!-- Redes sociales y contacto -->
        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <label for="website" class="block text-sm font-medium text-marron-oscuro">Página web</label>
                <input id="website" name="website" type="url" value="{{ old('website', $business->website) }}"
                    class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                @error('website')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email_lugar" class="block text-sm font-medium text-marron-oscuro">Correo electrónico</label>
                <input id="email_lugar" name="email_lugar" type="email" value="{{ old('email_lugar', $business->email_lugar) }}"
                    class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                @error('email_lugar')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <label for="instagram" class="block text-sm font-medium text-marron-oscuro">Instagram</label>
                <input id="instagram" name="instagram" type="url" value="{{ old('instagram', $business->instagram) }}"
                    class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                @error('instagram')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="facebook" class="block text-sm font-medium text-marron-oscuro">Facebook</label>
                <input id="facebook" name="facebook" type="url" value="{{ old('facebook', $business->facebook) }}"
                    class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                @error('facebook')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- IMAGEN PRINCIPAL -->
        <div class="border-t border-marron-claro pt-6">
            <h3 class="text-lg font-semibold text-marron-oscuro mb-4">Imagen principal del negocio</h3>
            
            @php
                $currentImage = null;
                if ($business->image) {
                    if (Str::startsWith($business->image, ['http://', 'https://'])) {
                        $currentImage = $business->image;
                    } else {
                        $currentImage = Storage::url($business->image);
                    }
                }
            @endphp
            
            @if($currentImage)
                <div class="mb-4">
                    <p class="text-sm text-marron/80 mb-2">Imagen actual:</p>
                    <img src="{{ $currentImage }}" alt="{{ $business->name }}" class="w-40 h-40 object-cover rounded-2xl border border-marron-claro">
                </div>
            @endif
            
            <div>
                <label class="block text-sm font-medium text-marron-oscuro mb-1">Subir nueva imagen (opcional)</label>
                <input type="file" name="main_image" accept="image/jpeg,image/png,image/jpg,image/gif"
                    class="w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron file:mr-3 file:rounded-xl file:border-0 file:bg-marron-claro file:px-4 file:py-2 file:text-sm file:font-semibold file:text-marron-oscuro hover:file:bg-marron-medio hover:file:text-white">
                <p class="text-xs text-marron/60 mt-1">Formatos: JPG, PNG, GIF (máx. 2MB). Dejar vacío para mantener la imagen actual.</p>
                @error('main_image')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- GALERÍA DE IMÁGENES -->
        <div class="border-t border-marron-claro pt-6">
            <h3 class="text-lg font-semibold text-marron-oscuro mb-4">Imágenes extras</h3>
            
            @if($business->images->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    @foreach($business->images as $image)
                        @php
                            $imgSrc = null;
                            if (Str::startsWith($image->image_url, ['http://', 'https://'])) {
                                $imgSrc = $image->image_url;
                            } else {
                                $imgSrc = Storage::url($image->image_url);
                            }
                        @endphp
                        <div class="relative border border-marron-claro rounded-xl overflow-hidden group">
                            <img src="{{ $imgSrc }}" alt="Imagen extra" class="w-full h-32 object-cover">
                            <label class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full cursor-pointer hover:bg-red-600 opacity-70 hover:opacity-100 transition">
                                <input type="checkbox" name="delete_gallery[]" value="{{ $image->id }}" class="hidden">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </label>
                        </div>
                    @endforeach
                </div>
                <p class="text-m text-marron/60 mb-4">⚠️ Marcá la ✕ en las imágenes que querés eliminar.</p>
            @else
                <p class="text-sm text-marron/60 mb-4">No hay imágenes en la galería.</p>
            @endif

            <div id="gallery-container">
                <div class="gallery-item mb-4 p-4 border border-marron-claro rounded-2xl bg-piel/30">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-semibold text-marron-oscuro">Nueva imagen extra</span>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-marron-oscuro mb-1">Subir archivo</label>
                        <input type="file" name="gallery_images[]" accept="image/jpeg,image/png,image/jpg,image/gif"
                            class="w-full rounded-xl border border-marron-claro bg-piel px-3 py-2 text-sm text-marron file:mr-2 file:rounded-lg file:border-0 file:bg-marron-claro file:px-3 file:py-1 file:text-xs file:font-semibold file:text-marron-oscuro hover:file:bg-marron-medio hover:file:text-white">
                    </div>
                </div>
            </div>

            <button type="button" id="add-more-images" 
                class="mt-2 inline-flex items-center gap-2 text-marron-oscuro hover:text-marron-medio text-sm font-semibold">
                + Agregar otra imagen
            </button>
            @error('gallery_images.*')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botones -->
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center pt-4">
            <button type="submit" id="submitBtn" class="rounded-2xl bg-marron-claro px-6 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">
                Guardar cambios
            </button>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-marron-oscuro underline hover:text-marron-medio">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
    // ==========================================
    // FUNCIONES DEL MAPA
    // ==========================================
    let map;
    let marker;
    const BERISSO_CENTER = [-34.8731, -57.8867];
    
    function initMap() {
        if (typeof L === 'undefined') {
            setTimeout(initMap, 100);
            return;
        }

        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        let initialLat = BERISSO_CENTER[0];
        let initialLng = BERISSO_CENTER[1];
        
        if (latInput.value && lngInput.value) {
            initialLat = parseFloat(latInput.value);
            initialLng = parseFloat(lngInput.value);
        }

        map = L.map('location-map').setView([initialLat, initialLng], 13);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        marker = L.marker([initialLat, initialLng], {
            draggable: true,
            title: 'Arrastrame para ajustar la ubicación'
        }).addTo(map);
        
        marker.on('dragend', function(event) {
            const position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
        });
        
        if (latInput.value && lngInput.value) {
            map.setView([initialLat, initialLng], 16);
        }
    }

    function searchAddress() {
        const address = document.getElementById('address').value;
        if (!address.trim()) {
            alert('Escribí una dirección primero');
            return;
        }

        const searchBtn = document.getElementById('search-address');
        const originalText = searchBtn.innerHTML;
        searchBtn.innerHTML = '⏳ Buscando...';
        searchBtn.disabled = true;

        const searchUrl = 'https://nominatim.openstreetmap.org/search?format=json&q=' + 
            encodeURIComponent(address + ', Berisso, Argentina') + '&limit=1';
        
        fetch(searchUrl)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    
                    map.setView([lat, lon], 16);
                    marker.setLatLng([lat, lon]);
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lon;
                } else {
                    alert('No se encontró la ubicación. Probá con una dirección más específica.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al buscar la dirección');
            })
            .finally(() => {
                searchBtn.innerHTML = originalText;
                searchBtn.disabled = false;
            });
    }
    
    function getCurrentLocation() {
        if (!navigator.geolocation) {
            alert('Tu navegador no soporta geolocalización');
            return;
        }

        const locationBtn = document.getElementById('get-current-location');
        const originalText = locationBtn.innerHTML;
        locationBtn.innerHTML = '⏳ Obteniendo...';
        locationBtn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                map.setView([lat, lng], 16);
                marker.setLatLng([lat, lng]);
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                
                locationBtn.innerHTML = '✅ Ubicación actual';
                setTimeout(() => {
                    locationBtn.innerHTML = originalText;
                    locationBtn.disabled = false;
                }, 2000);
            },
            function(error) {
                alert('No se pudo obtener tu ubicación. Permití el acceso a la ubicación.');
                locationBtn.innerHTML = originalText;
                locationBtn.disabled = false;
            }
        );
    }

    // ==========================================
    // FUNCIONES DEL SELECTOR DE HORARIOS
    // ==========================================
    
    // Función auxiliar para convertir texto a slug
    function slugify(text) {
        const mapa = {
            'á': 'a', 'é': 'e', 'í': 'i', 'ó': 'o', 'ú': 'u',
            'Á': 'a', 'É': 'e', 'Í': 'i', 'Ó': 'o', 'Ú': 'u',
            'ñ': 'n', 'Ñ': 'n'
        };
        
        let result = text.toString().toLowerCase();
        for (let key in mapa) {
            result = result.replaceAll(key, mapa[key]);
        }
        
        result = result.replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
        
        return result;
    }

    // Toggle para mostrar/ocultar el día seleccionado
    function toggleDia(checkbox, dia) {
        const container = document.getElementById('dia-' + slugify(dia));
        if (checkbox.checked) {
            container.classList.remove('hidden');
            container.style.opacity = '0';
            setTimeout(() => {
                container.style.opacity = '1';
                container.style.transition = 'opacity 0.3s ease';
            }, 10);
        } else {
            container.classList.add('hidden');
        }
        actualizarHorarioFinal();
    }

    // Función para eliminar un día
    function eliminarDia(slug) {
        const dia = slug.replace(/-/g, ' ');
        const checkboxes = document.querySelectorAll('input[name="dias_seleccionados[]"]');
        checkboxes.forEach(cb => {
            if (cb.value === dia) {
                cb.checked = false;
                toggleDia(cb, dia);
            }
        });
    }

    // Agregar turno a un día específico
    function agregarTurno(diaSlug) {
        const container = document.getElementById('turnos-' + diaSlug);
        const turnoCount = container.querySelectorAll('.turno-item').length;
        
        const nuevoTurno = document.createElement('div');
        nuevoTurno.className = 'turno-item grid grid-cols-2 gap-3 items-end relative';
        nuevoTurno.innerHTML = `
            <div>
                <label class="block text-xs font-medium text-marron-oscuro mb-1">Desde</label>
                <input type="time" name="horarios[${diaSlug}][turnos][${turnoCount}][desde]" 
                    value="09:00"
                    class="w-full rounded-xl border border-marron-claro bg-white px-3 py-2 text-sm text-marron outline-none focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30">
            </div>
            <div>
                <label class="block text-xs font-medium text-marron-oscuro mb-1">Hasta</label>
                <input type="time" name="horarios[${diaSlug}][turnos][${turnoCount}][hasta]" 
                    value="17:00"
                    class="w-full rounded-xl border border-marron-claro bg-white px-3 py-2 text-sm text-marron outline-none focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30">
            </div>
            <button type="button" class="absolute -top-2 -right-2 text-red-500 hover:text-red-700 text-sm bg-white rounded-full w-6 h-6 flex items-center justify-center border border-marron-claro" onclick="eliminarTurno(this)">
                ×
            </button>
        `;
        container.appendChild(nuevoTurno);
        actualizarHorarioFinal();
    }

    // Eliminar un turno específico
    function eliminarTurno(btn) {
        const turnoItem = btn.closest('.turno-item');
        const container = turnoItem.parentElement;
        if (container.querySelectorAll('.turno-item').length > 1) {
            turnoItem.remove();
            actualizarHorarioFinal();
        } else {
            alert('Debe haber al menos un turno por día');
        }
    }

    // Toggle para 24hs
    function toggle24hs(checkbox) {
        const contenedorDias = document.getElementById('horarios-container');
        const checkboxes = document.querySelectorAll('input[name="dias_seleccionados[]"]');
        
        if (checkbox.checked) {
            checkboxes.forEach(cb => {
                cb.disabled = true;
                cb.checked = false;
                toggleDia(cb, cb.value);
            });
            contenedorDias.style.opacity = '0.5';
            contenedorDias.style.pointerEvents = 'none';
            document.querySelectorAll('.dia-container').forEach(el => {
                el.classList.add('hidden');
            });
        } else {
            checkboxes.forEach(cb => {
                cb.disabled = false;
            });
            contenedorDias.style.opacity = '1';
            contenedorDias.style.pointerEvents = 'auto';
        }
        actualizarHorarioFinal();
    }

    // ==========================================
    // ACTUALIZAR CAMPO OCULTO HOURS
    // ==========================================
    function actualizarHorarioFinal() {
        const checkboxes = document.querySelectorAll('input[name="dias_seleccionados[]"]:checked');
        const abierto24hs = document.querySelector('input[name="abierto_24hs"]')?.checked;
        const hoursInput = document.getElementById('hours-input');
        
        if (!hoursInput) return;
        
        if (abierto24hs) {
            hoursInput.value = 'Abierto 24 horas';
            return;
        }
        
        if (checkboxes.length === 0) {
            hoursInput.value = '';
            return;
        }
        
        let horarioTexto = [];
        
        checkboxes.forEach(cb => {
            const dia = cb.value;
            const diaSlug = slugify(dia);
            const turnos = document.querySelectorAll(`#turnos-${diaSlug} .turno-item`);
            let turnosTexto = [];
            
            turnos.forEach(turno => {
                const desde = turno.querySelector('input[name*="[desde]"]')?.value;
                const hasta = turno.querySelector('input[name*="[hasta]"]')?.value;
                if (desde && hasta) {
                    turnosTexto.push(`${desde} - ${hasta}`);
                }
            });
            
            if (turnosTexto.length > 0) {
                horarioTexto.push(`${dia}: ${turnosTexto.join(', ')}`);
            }
        });
        
        hoursInput.value = horarioTexto.join(' | ');
    }

    // ==========================================
    // FUNCIONES DE LA GALERÍA DE IMÁGENES
    // ==========================================
    function setupGallery() {
        let galleryIndex = 1;
        const container = document.getElementById('gallery-container');
        const addButton = document.getElementById('add-more-images');

        if (!container || !addButton) return;

        addButton.addEventListener('click', function() {
            galleryIndex++;
            const newItem = document.createElement('div');
            newItem.className = 'gallery-item mb-4 p-4 border border-marron-claro rounded-2xl bg-piel/30';
            newItem.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-semibold text-marron-oscuro">Nueva imagen extra ${galleryIndex}</span>
                    <button type="button" class="remove-gallery text-red-500 hover:text-red-700 text-sm">Eliminar</button>
                </div>
                <div>
                    <label class="block text-xs font-medium text-marron-oscuro mb-1">Subir archivo</label>
                    <input type="file" name="gallery_images[]" accept="image/jpeg,image/png,image/jpg,image/gif"
                        class="w-full rounded-xl border border-marron-claro bg-piel px-3 py-2 text-sm text-marron file:mr-2 file:rounded-lg file:border-0 file:bg-marron-claro file:px-3 file:py-1 file:text-xs file:font-semibold file:text-marron-oscuro hover:file:bg-marron-medio hover:file:text-white">
                </div>
            `;
            container.appendChild(newItem);

            const removeBtn = newItem.querySelector('.remove-gallery');
            removeBtn.addEventListener('click', function() {
                newItem.remove();
            });
        });
    }

    // ==========================================
    // INICIALIZAR
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        // Mapa
        if (typeof L === 'undefined') {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            document.head.appendChild(link);
            
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            script.onload = initMap;
            document.head.appendChild(script);
        } else {
            initMap();
        }
        
        const searchBtn = document.getElementById('search-address');
        const locationBtn = document.getElementById('get-current-location');
        
        if (searchBtn) searchBtn.addEventListener('click', searchAddress);
        if (locationBtn) locationBtn.addEventListener('click', getCurrentLocation);
        
        setupGallery();
        
        // Actualizar horario final cuando cambie cualquier input
        document.addEventListener('change', function(e) {
            if (e.target.name && e.target.name.includes('horarios')) {
                setTimeout(actualizarHorarioFinal, 100);
            }
        });
        document.addEventListener('input', function(e) {
            if (e.target.name && e.target.name.includes('horarios')) {
                setTimeout(actualizarHorarioFinal, 100);
            }
        });
        
        // Inicializar horarios si hay días seleccionados
        setTimeout(actualizarHorarioFinal, 500);
    });
</script>
@endsection