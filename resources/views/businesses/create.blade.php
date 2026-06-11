@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-marron-oscuro">Publicar un negocio</h1>
            <p class="mt-2 text-sm text-marron/80">Completa los datos para que tu comercio aparezca en el directorio.</p>
        </div>

        <form method="POST" action="{{ route('businesses.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Campos básicos del negocio -->
            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-marron-oscuro">Nombre del negocio *</label>
                    <input id="name" name="name" value="{{ old('name') }}" required
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
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                    class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="address" class="block text-sm font-medium text-marron-oscuro">Dirección *</label>
                    <input id="address" name="address" value="{{ old('address') }}" required
                        class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                    @error('address')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-marron-oscuro">Teléfono</label>
                    <input id="phone" name="phone" value="{{ old('phone') }}"
                        class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="website" class="block text-sm font-medium text-marron-oscuro">Página web</label>
                    <input id="website" name="website" type="url" value="{{ old('website') }}"
                        class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                    @error('website')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email_lugar" class="block text-sm font-medium text-marron-oscuro">Correo electrónico del negocio</label>
                    <input id="email_lugar" name="email_lugar" type="email" value="{{ old('email_lugar') }}"
                        class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                    @error('email_lugar')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="instagram" class="block text-sm font-medium text-marron-oscuro">Instagram</label>
                    <input id="instagram" name="instagram" type="url" value="{{ old('instagram') }}"
                        class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                    @error('instagram')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="facebook" class="block text-sm font-medium text-marron-oscuro">Facebook</label>
                    <input id="facebook" name="facebook" type="url" value="{{ old('facebook') }}"
                        class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                    @error('facebook')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="hours" class="block text-sm font-medium text-marron-oscuro">Horario</label>
                    <input id="hours" name="hours" value="{{ old('hours') }}"
                        class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                    @error('hours')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                    <div>
                <!-- ========================================== -->
                <!-- SECCIÓN: IMAGEN PRINCIPAL -->
                <!-- ========================================== -->
                        <label class="block text-sm font-medium text-marron-oscuro mb-1">Subir imagen principal (archivo)</label>
                        <input type="file" name="main_image" accept="image/jpeg,image/png,image/jpg,image/gif"
                            class="w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron file:mr-3 file:rounded-xl file:border-0 file:bg-marron-claro file:px-4 file:py-2 file:text-sm file:font-semibold file:text-marron-oscuro hover:file:bg-marron-medio hover:file:text-white">
                        <p class="text-xs text-marron/60 mt-1">Formatos: JPG, PNG, GIF (máx. 2MB)</p>
                    </div>
                @error('main_image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                @error('main_image_url') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-4 lg:grid-cols-1">
                <!-- ========================================== -->
                <!-- SECCIÓN: GALERÍA DE IMÁGENES EXTRAS -->
                <!-- ========================================== -->
                <div id="gallery-container">
                    <div class="gallery-item mb-4 p-4 border border-marron-claro rounded-2xl bg-piel/30">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-semibold text-marron-oscuro">Imagen extra 1</span>
                            <button type="button" class="remove-gallery text-red-500 hover:text-red-700 text-sm hidden">Eliminar</button>
                        </div>
                        <div class="grid gap-3 md:grid-cols-1">
                            <div>
                                <label class="block text-xs font-medium text-marron-oscuro mb-1">Subir archivo</label>
                                <input type="file" name="gallery_images[]" accept="image/jpeg,image/png,image/jpg,image/gif"
                                    class="w-full rounded-xl border border-marron-claro bg-piel px-3 py-2 text-sm text-marron file:mr-2 file:rounded-lg file:border-0 file:bg-marron-claro file:px-3 file:py-1 file:text-xs file:font-semibold file:text-marron-oscuro hover:file:bg-marron-medio hover:file:text-white">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-more-images" 
                    class="mt-2 inline-flex items-center gap-2 text-marron-oscuro hover:text-marron-medio text-sm font-semibold">
                    + Agregar otra imagen
                </button>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center pt-4">
                <button type="submit" class="rounded-2xl bg-marron-claro px-6 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">
                    Publicar negocio
                </button>
                <a href="{{ route('home') }}" class="text-sm font-medium text-marron-oscuro underline hover:text-marron-medio">
                    Volver al directorio
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let galleryIndex = 1;
            const container = document.getElementById('gallery-container');
            const addButton = document.getElementById('add-more-images');

            addButton.addEventListener('click', function() {
                galleryIndex++;
                const newItem = document.createElement('div');
                newItem.className = 'gallery-item mb-4 p-4 border border-marron-claro rounded-2xl bg-piel/30';
                newItem.innerHTML = `
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-semibold text-marron-oscuro">Imagen extra ${galleryIndex}</span>
                        <button type="button" class="remove-gallery text-red-500 hover:text-red-700 text-sm">Eliminar</button>
                    </div>
                    <div class="grid gap-3 md:grid-cols-1">
                        <div>
                            <label class="block text-xs font-medium text-marron-oscuro mb-1">Subir archivo</label>
                            <input type="file" name="gallery_images[]" accept="image/jpeg,image/png,image/jpg,image/gif"
                                class="w-full rounded-xl border border-marron-claro bg-piel px-3 py-2 text-sm text-marron file:mr-2 file:rounded-lg file:border-0 file:bg-marron-claro file:px-3 file:py-1 file:text-xs file:font-semibold file:text-marron-oscuro hover:file:bg-marron-medio hover:file:text-white">
                        </div>
                    </div>
                `;
                container.appendChild(newItem);

                // Agregar evento de eliminar al nuevo botón
                const removeBtn = newItem.querySelector('.remove-gallery');
                removeBtn.addEventListener('click', function() {
                    newItem.remove();
                });
            });

            // Evento para eliminar el primer elemento (inicialmente oculto)
            const firstRemoveBtn = document.querySelector('.remove-gallery');
            if (firstRemoveBtn) {
                firstRemoveBtn.classList.add('hidden');
            }
        });
    </script>
@endsection