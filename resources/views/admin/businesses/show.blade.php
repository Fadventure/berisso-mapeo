@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-semibold text-marron-oscuro">Revisar negocio</h1>
                <p class="mt-2 text-sm text-marron/80">Verificá los datos antes de aprobar o rechazar.</p>
            </div>
            <span class="rounded-full px-3 py-1 text-xs font-semibold 
                @if($business->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($business->status === 'approved') bg-green-100 text-green-800
                @else bg-red-100 text-red-800 @endif">
                {{ ucfirst($business->status) }}
            </span>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Datos del negocio -->
        <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
            <h2 class="text-xl font-semibold text-marron-oscuro mb-4">📝 Datos del negocio</h2>
            
            <div class="space-y-3">
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Nombre</p>
                    <p class="text-marron">{{ $business->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Categoría</p>
                    <p class="text-marron">{{ $business->category?->name ?? 'Sin categoría' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Descripción</p>
                    <p class="text-marron">{{ $business->description ?? 'Sin descripción' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Dirección</p>
                    <p class="text-marron">{{ $business->address }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Teléfono</p>
                    <p class="text-marron">{{ $business->phone ?? 'No especificado' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Horario</p>
                    <p class="text-marron">{{ $business->hours ?? 'No especificado' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Email</p>
                    <p class="text-marron">{{ $business->email_lugar ?? 'No especificado' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Sitio web</p>
                    <p class="text-marron">{{ $business->website ?? 'No especificado' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Redes Sociales</p>
                    <div class="flex gap-3 mt-1">
                        @if($business->facebook)
                            <a href="{{ $business->facebook }}" target="_blank" class="text-blue-600 hover:underline text-sm">Facebook</a>
                        @endif
                        @if($business->instagram)
                            <a href="{{ $business->instagram }}" target="_blank" class="text-pink-600 hover:underline text-sm">Instagram</a>
                        @endif
                        @if(!$business->facebook && !$business->instagram)
                            <p class="text-marron/60 text-sm">No especificado</p>
                        @endif
                    </div>
                </div>
                @if($business->image)
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Imagen</p>
                    @php
                        $imageSrc = null;
                        if ($business->image) {
                            if (Str::startsWith($business->image, ['http://', 'https://'])) {
                                $imageSrc = $business->image;
                            } else {
                                $imageSrc = Storage::url($business->image);
                            }
                        }
                    @endphp
                    @if($imageSrc)
                        <img src="{{ $imageSrc }}" alt="{{ $business->name }}" class="mt-2 rounded-xl max-h-40 w-auto object-cover border border-marron-claro">
                    @endif
                </div>
                @endif
                @if($business->images && $business->images->count() > 0)
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Galería de imágenes</p>
                    <div class="flex gap-2 mt-2 flex-wrap">
                        @foreach($business->images as $img)
                            @php
                                $imgSrc = null;
                                if (Str::startsWith($img->image_url, ['http://', 'https://'])) {
                                    $imgSrc = $img->image_url;
                                } else {
                                    $imgSrc = Storage::url($img->image_url);
                                }
                            @endphp
                            @if($imgSrc)
                                <img src="{{ $imgSrc }}" alt="Imagen extra" class="rounded-lg max-h-20 w-auto object-cover border border-marron-claro">
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Datos del creador -->
        <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
            <h2 class="text-xl font-semibold text-marron-oscuro mb-4">👤 Datos del creador</h2>
            
            <div class="space-y-3">
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Nombre</p>
                    <p class="text-marron">{{ $business->user?->name ?? 'Usuario desconocido' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Email</p>
                    <p class="text-marron">{{ $business->user?->email ?? 'No disponible' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Fecha de registro</p>
                    <p class="text-marron">{{ $business->user?->created_at->format('d/m/Y H:i') ?? 'No disponible' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-marron-oscuro">Negocios publicados</p>
                    <p class="text-marron">{{ $business->user?->businesses()->count() ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones -->
    <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
        <h2 class="text-xl font-semibold text-marron-oscuro mb-4">⚡ Acciones</h2>
        
        <div class="flex flex-wrap gap-4">
            <form action="{{ route('admin.businesses.approve', $business) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" 
                        class="rounded-2xl bg-green-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-green-700"
                        onclick="return confirm('¿Estás seguro de aprobar este negocio?')">
                    ✅ Aprobar negocio
                </button>
            </form>

            <button type="button" 
                    onclick="showRejectModal()"
                    class="rounded-2xl bg-red-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                ❌ Rechazar negocio
            </button>

            <a href="{{ route('admin.dashboard') }}" 
               class="rounded-2xl border border-marron-claro bg-white px-6 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-piel-oscuro">
                ← Volver al panel
            </a>
        </div>
    </div>
</div>

<!-- Modal para rechazar -->
<div id="rejectModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full mx-4 shadow-2xl">
        <h3 class="text-xl font-semibold text-marron-oscuro mb-4">Rechazar negocio</h3>
        <p class="text-sm text-marron/80 mb-4">Indicá el motivo del rechazo (opcional):</p>
        
        <form action="{{ route('admin.businesses.reject', $business) }}" method="POST">
            @csrf
            @method('PATCH')
            <textarea name="rejection_reason" rows="3" 
                      class="w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30"
                      placeholder="Ej: Faltan datos de contacto, dirección incorrecta, información insuficiente..."></textarea>
            <div class="flex gap-3 mt-4">
                <button type="submit" class="rounded-2xl bg-red-600 px-6 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                    Confirmar rechazo
                </button>
                <button type="button" onclick="hideRejectModal()" 
                        class="rounded-2xl border border-marron-claro bg-white px-6 py-2 text-sm font-semibold text-marron-oscuro transition hover:bg-piel-oscuro">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }
    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }
</script>
@endsection