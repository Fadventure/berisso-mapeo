@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="rounded-3xl border border-blue-100 bg-white p-6 shadow-sm shadow-blue-100/80">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <span class="inline-flex rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-blue-700">{{ $business->category?->name ?? 'Sin categoría' }}</span>
                    <h1 class="mt-4 text-3xl font-semibold text-blue-900">{{ $business->name }}</h1>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $business->description ?? 'Descripción no disponible.' }}</p>
                </div>

                <div class="space-y-3 rounded-3xl border border-blue-100 bg-blue-50 p-5 text-sm text-slate-700">
                    <p><strong>Ingresado por:</strong> {{ $business->user?->name ?? 'Usuario invitado' }}</p>
                    @if($business->address)
                        <p><strong>Dirección:</strong> {{ $business->address }}</p>
                    @endif
                    @if($business->phone)
                        <p><strong>Teléfono:</strong> {{ $business->phone }}</p>
                    @endif
                    @if($business->hours)
                        <p><strong>Horario:</strong> {{ $business->hours }}</p>
                    @endif
                    @if($business->website)
                        <p><strong>Sitio:</strong> <a href="{{ $business->website }}" target="_blank" rel="noreferrer" class="text-blue-700 underline">Visitar sitio</a></p>
                    @endif
                </div>
            </div>
        </div>

        @if($business->image)
            <div class="overflow-hidden rounded-3xl border border-blue-100 bg-white shadow-sm shadow-blue-100/80">
                <img src="{{ $business->image }}" alt="{{ $business->name }}" class="h-80 w-full object-cover" />
            </div>
        @endif

        <div class="rounded-3xl border border-blue-100 bg-white p-6 shadow-sm shadow-blue-100/80">
            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <h2 class="text-xl font-semibold text-blue-900">Información del negocio</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $business->description ?? 'No hay información adicional para este negocio.' }}</p>
                </div>

                <div class="space-y-4 rounded-3xl border border-blue-100 bg-blue-50 p-5 text-sm text-slate-700">
                    @if($business->address)
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Dirección</p>
                            <p>{{ $business->address }}</p>
                        </div>
                    @endif

                    @if($business->phone)
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Teléfono</p>
                            <p>{{ $business->phone }}</p>
                        </div>
                    @endif

                    @if($business->website)
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Sitio web</p>
                            <p><a href="{{ $business->website }}" target="_blank" rel="noreferrer" class="text-blue-700 underline">{{ $business->website }}</a></p>
                        </div>
                    @endif

                    @if($business->hours)
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Horario</p>
                            <p>{{ $business->hours }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('home') }}" class="rounded-2xl border border-blue-200 bg-white px-4 py-3 text-sm text-blue-700 transition hover:bg-blue-50">Volver al directorio</a>
                @auth
                    <a href="{{ route('businesses.create') }}" class="rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">Publicar otro negocio</a>
                @endauth
            </div>
        </div>
    </div>
@endsection
