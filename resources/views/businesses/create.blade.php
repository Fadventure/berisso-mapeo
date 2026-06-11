@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-marron-oscuro">Publicar un negocio</h1>
            <p class="mt-2 text-sm text-marron/80">Completa los datos para que tu comercio aparezca en el directorio.</p>
        </div>

        <form method="POST" action="{{ route('businesses.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

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

                <!-- Opción 1: Subir archivo -->
                <div class="mb-4">
                    <label for="image_file" class="block text-sm font-medium text-marron-oscuro mb-1">
                        📁 Subir imagen del local (archivo)
                    </label>
                    <input type="file" name="image_file" id="image_file" accept="image/jpeg,image/png,image/jpg,image/gif"
                        class="w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron file:mr-3 file:rounded-xl file:border-0 file:bg-marron-claro file:px-4 file:py-2 file:text-sm file:font-semibold file:text-marron-oscuro hover:file:bg-marron-medio hover:file:text-white">
                    <p class="text-xs text-marron/60 mt-1">Formatos: JPG, PNG, GIF (máx. 2MB)</p>
                </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <button type="submit" class="rounded-2xl bg-marron-claro px-6 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">Publicar negocio</button>
                <a href="{{ route('home') }}" class="text-sm font-medium text-marron-oscuro underline hover:text-marron-medio">Volver al directorio</a>
            </div>
            </div>

        </form>
    </div>
@endsection