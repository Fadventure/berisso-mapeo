@extends('layouts.app')

@section('content')
<div class="flex min-h-[70vh] items-center justify-center">
    <div class="w-full max-w-md rounded-3xl border border-marron-claro bg-white shadow-sm shadow-marron-claro/80 overflow-hidden">
        <div class="bg-marron-claro px-8 py-8">
            <h1 class="text-3xl font-semibold text-marron-oscuro">Crear cuenta</h1>
            <p class="mt-2 text-marron-oscuro/80">Regístrate con Nombre, Email y Contraseña.</p>
        </div>

        <div class="px-8 py-10">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-marron-oscuro">Nombre</label>
                    <input id="name" name="name" value="{{ old('name') }}" required autofocus
                        class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-marron-oscuro">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                        class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-marron-oscuro">Contraseña</label>
                    <input id="password" name="password" type="password" required
                        class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-marron-oscuro">Confirmar contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="mt-2 w-full rounded-2xl border border-marron-claro bg-piel px-4 py-3 text-sm text-marron outline-none transition focus:border-marron-medio focus:ring-2 focus:ring-marron-claro/30" />
                </div>

                <div class="flex items-center justify-between gap-4">
                    <span class="text-sm text-marron">¿Ya tienes cuenta?</span>
                    <a href="{{ route('login') }}" class="text-sm font-medium text-marron-oscuro underline hover:text-marron-medio">Iniciar sesión</a>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-marron-claro px-4 py-3 text-sm font-semibold text-marron-oscuro transition hover:bg-marron-medio hover:text-white">Registrarse</button>
            </form>
        </div>
    </div>
</div>
@endsection