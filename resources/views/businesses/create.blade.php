@extends('layouts.app')

@section('content')
    <div class="rounded-3xl border border-blue-100 bg-white p-6 shadow-sm shadow-blue-100/80">
        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-blue-900">Publicar un negocio</h1>
            <p class="mt-2 text-sm text-slate-600">Completa los datos para que tu comercio aparezca en el directorio.</p>
        </div>

        <form method="POST" action="{{ route('businesses.store') }}" class="space-y-6">
            @csrf

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Nombre del negocio</label>
                    <input id="name" name="name" value="{{ old('name') }}" required
                        class="mt-2 w-full rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-slate-700">Categoría</label>
                    <select id="category_id" name="category_id" required
                        class="mt-2 w-full rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <option value="">Selecciona una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-700">Descripción</label>
                <textarea id="description" name="description" rows="5"
                    class="mt-2 w-full rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">{{ old('description') }}</textarea>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="address" class="block text-sm font-medium text-slate-700">Dirección</label>
                    <input id="address" name="address" value="{{ old('address') }}" required
                        class="mt-2 w-full rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700">Teléfono</label>
                    <input id="phone" name="phone" value="{{ old('phone') }}"
                        class="mt-2 w-full rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="website" class="block text-sm font-medium text-slate-700">Página web</label>
                    <input id="website" name="website" type="url" value="{{ old('website') }}"
                        class="mt-2 w-full rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                </div>

                <div>
                    <label for="hours" class="block text-sm font-medium text-slate-700">Horario</label>
                    <input id="hours" name="hours" value="{{ old('hours') }}"
                        class="mt-2 w-full rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                </div>
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-slate-700">URL de la imagen</label>
                <input id="image" name="image" type="url" value="{{ old('image') }}"
                    class="mt-2 w-full rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                <p class="mt-2 text-sm text-slate-500">Agrega una imagen pública para que tu negocio se vea mejor en el listado.</p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <button type="submit" class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">Publicar negocio</button>
                <a href="{{ route('home') }}" class="text-sm font-medium text-blue-700 transition hover:text-blue-900">Volver al directorio</a>
            </div>
        </form>
    </div>
@endsection
