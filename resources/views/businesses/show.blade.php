@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <span class="inline-flex rounded-full border border-marron-claro bg-piel px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-marron-oscuro">{{ $business->category?->name ?? 'Sin categoría' }}</span>
                    <h1 class="mt-4 text-3xl font-semibold text-marron-oscuro">{{ $business->name }}</h1>
                    <p class="mt-3 text-sm leading-6 text-marron/80">{{ $business->description ?? 'Descripción no disponible.' }}</p>
                </div>

                <div class="space-y-3 rounded-3xl border border-marron-claro bg-piel p-5 text-sm text-marron">
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
                        <p><strong>Sitio:</strong> <a href="{{ $business->website }}" target="_blank" rel="noreferrer" class="text-marron-oscuro underline hover:text-marron-medio">Visitar sitio</a></p>
                    @endif
                </div>
            </div>
        </div>

        @if($business->image)
            <div class="overflow-hidden rounded-3xl border border-marron-claro bg-white shadow-sm shadow-marron-claro/80">
                <img src="{{ $business->image }}" alt="{{ $business->name }}" class="h-80 w-full object-cover" />
            </div>
        @endif

        <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <h2 class="text-xl font-semibold text-marron-oscuro">Información del negocio</h2>
                    <p class="mt-3 text-sm leading-6 text-marron/80">{{ $business->description ?? 'No hay información adicional para este negocio.' }}</p>
                </div>

                <div class="space-y-4 rounded-3xl border border-marron-claro bg-piel p-5 text-sm text-marron">
                    @if($business->address)
                        <div>
                            <p class="text-sm font-semibold text-marron-oscuro">Dirección</p>
                            <p>{{ $business->address }}</p>
                        </div>
                    @endif

                    @if($business->phone)
                        <div>
                            <p class="text-sm font-semibold text-marron-oscuro">Teléfono</p>
                            <p>{{ $business->phone }}</p>
                        </div>
                    @endif

                    @if($business->website)
                        <div>
                            <p class="text-sm font-semibold text-marron-oscuro">Sitio web</p>
                            <p><a href="{{ $business->website }}" target="_blank" rel="noreferrer" class="text-marron-oscuro underline">{{ $business->website }}</a></p>
                        </div>
                    @endif

                    @if($business->hours)
                        <div>
                            <p class="text-sm font-semibold text-marron-oscuro">Horario</p>
                            <p>{{ $business->hours }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('home') }}" class="rounded-2xl border border-marron-claro bg-white px-4 py-3 text-sm text-marron-oscuro transition hover:bg-piel-oscuro">Volver al directorio</a>
                @auth
                    <a href="{{ route('businesses.create') }}" class="rounded-2xl bg-marron-claro px-4 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">Publicar otro negocio</a>
                @endauth
            </div>
        </div>
    </div>
@endsection