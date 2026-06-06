@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-marron-oscuro">Directorio de negocios de Berisso</h1>
                    <p class="mt-2 text-sm text-marron/80">Busca por categoría, nombre o ubicación para encontrar el comercio ideal.</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <form method="GET" action="{{ route('home') }}" class="flex w-full max-w-md items-center gap-2">
                        <input
                            name="search"
                            value="{{ request('search') }}"
                            type="search"
                            placeholder="Buscar negocios, dirección o servicios"
                            class="flex-1 rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30"
                        />
                        <button type="submit" class="rounded-2xl bg-marron-claro px-4 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">Buscar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[280px_1fr]">
            <aside class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-marron-oscuro">Categorías</h2>
                    <p class="mt-1 text-sm text-marron/80">Filtra los negocios por sector.</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('home', ['search' => request('search')]) }}" class="rounded-full border px-4 py-2 text-sm transition {{ is_null($selectedCategory) ? 'border-marron-medio bg-marron-medio text-white' : 'border-marron-claro bg-white text-marron-oscuro hover:bg-piel-oscuro' }}">Todos</a>
                    @foreach($categories as $category)
                        <a
                            href="{{ route('home', ['category' => $category->slug, 'search' => request('search')]) }}"
                            class="rounded-full border px-4 py-2 text-sm transition {{ optional($selectedCategory)->id === $category->id ? 'border-marron-medio bg-marron-medio text-white' : 'border-marron-claro bg-white text-marron-oscuro hover:bg-piel-oscuro' }}"
                        >
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <div class="mt-8 rounded-3xl border border-marron-claro bg-piel p-5">
                    <h3 class="text-base font-semibold text-marron-oscuro">¿Querés sumar tu negocio?</h3>
                    <p class="mt-2 text-sm text-marron/80">Publicá tu comercio en el directorio para que los vecinos te encuentren.</p>

                    @auth
                        <a href="{{ route('businesses.create') }}" class="mt-4 inline-flex rounded-2xl bg-marron-claro px-4 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">Publicar negocio</a>
                    @else
                        <a href="{{ route('login') }}" class="mt-4 inline-flex rounded-2xl border border-marron-claro bg-white px-4 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-piel-oscuro">Iniciar sesión para publicar</a>
                    @endauth
                </div>
            </aside>

            <section class="space-y-6">
                <div class="rounded-3xl border border-marron-claro bg-white p-6 shadow-sm shadow-marron-claro/80">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-marron/80">Mostrando</p>
                            <h2 class="text-xl font-semibold text-marron-oscuro">{{ $businesses->total() }} negocios encontrados</h2>
                        </div>
                        <p class="text-sm text-marron/80">Filtrado por: <strong>{{ $selectedCategory ? $selectedCategory->name : 'Todos' }}</strong></p>
                    </div>
                </div>

                @if($businesses->isEmpty())
                    <div class="rounded-3xl border border-marron-claro bg-white p-6 text-center shadow-sm shadow-marron-claro/80">
                        <p class="text-lg font-medium text-marron">No encontramos negocios con esos criterios.</p>
                        <p class="mt-2 text-sm text-marron/80">Probá con otra búsqueda o elegí otra categoría.</p>
                    </div>
                @else
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($businesses as $business)
                            <article class="group overflow-hidden rounded-3xl border border-marron-claro bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                                <div class="h-44 w-full overflow-hidden bg-piel-oscuro">
                                    @if($business->image)
                                        <img src="{{ $business->image }}" alt="{{ $business->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
                                    @else
                                        <div class="flex h-full items-center justify-center bg-piel text-sm text-marron/80">Imagen no disponible</div>
                                    @endif
                                </div>
                                <div class="p-5">
                                    <span class="inline-flex rounded-full border border-marron-claro bg-piel px-3 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-marron-oscuro">{{ $business->category?->name ?? 'Sin categoría' }}</span>
                                    <h3 class="mt-4 text-xl font-semibold text-marron-oscuro">{{ $business->name }}</h3>
                                    <p class="mt-3 max-h-16 overflow-hidden text-sm leading-6 text-marron/80">{{ $business->description ?? 'Sin descripción disponible' }}</p>
                                    <div class="mt-5 space-y-2 text-sm text-marron/80">
                                        @if($business->address)
                                            <p><strong>Dirección:</strong> {{ $business->address }}</p>
                                        @endif
                                        @if($business->phone)
                                            <p><strong>Teléfono:</strong> {{ $business->phone }}</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('businesses.show', $business) }}" class="mt-5 inline-flex rounded-2xl bg-marron-claro px-4 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">Ver más</a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if($businesses->hasPages())
                        <nav class="flex flex-wrap items-center justify-center gap-3 rounded-3xl border border-marron-claro bg-white p-4 shadow-sm shadow-marron-claro/80">
                            @if($businesses->onFirstPage())
                                <span class="rounded-full border border-marron-claro bg-piel px-4 py-2 text-sm text-marron/80">Anterior</span>
                            @else
                                <a href="{{ $businesses->previousPageUrl() }}" class="rounded-full border border-marron-claro bg-white px-4 py-2 text-sm text-marron-oscuro hover:bg-piel-oscuro">Anterior</a>
                            @endif

                            <span class="text-sm text-marron/80">Página {{ $businesses->currentPage() }} de {{ $businesses->lastPage() }}</span>

                            @if($businesses->hasMorePages())
                                <a href="{{ $businesses->nextPageUrl() }}" class="rounded-full border border-marron-claro bg-white px-4 py-2 text-sm text-marron-oscuro hover:bg-piel-oscuro">Siguiente</a>
                            @else
                                <span class="rounded-full border border-marron-claro bg-piel px-4 py-2 text-sm text-marron/80">Siguiente</span>
                            @endif
                        </nav>
                    @endif
                @endif
            </section>
        </div>
    </div>
@endsection