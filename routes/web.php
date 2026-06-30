<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BusinessController::class, 'index'])->name('home');

Route::get('/businesses/create', [BusinessController::class, 'create'])
    ->middleware('auth')
    ->name('businesses.create');

Route::post('/businesses', [BusinessController::class, 'store'])
    ->middleware('auth')
    ->name('businesses.store');

Route::get('/businesses/{business}', [BusinessController::class, 'show'])
    ->name('businesses.show');

// ==========================================
// RUTAS PARA EDITAR NEGOCIOS
// ==========================================
Route::get('/businesses/{business}/edit', [BusinessController::class, 'edit'])
    ->middleware('auth')
    ->name('businesses.edit');

Route::put('/businesses/{business}', [BusinessController::class, 'update'])
    ->middleware('auth')
    ->name('businesses.update');

// ==========================================
// RUTA PARA ELIMINAR NEGOCIOS (AGREGAR ESTO)
// ==========================================
Route::delete('/businesses/{business}', [BusinessController::class, 'destroy'])
    ->middleware('auth')
    ->name('businesses.destroy');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AuthController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

// ==========================================
// RUTAS DE ADMINISTRACIÓN
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/businesses/{business}', [App\Http\Controllers\AdminController::class, 'show'])->name('businesses.show');
    Route::patch('/businesses/{business}/approve', [App\Http\Controllers\AdminController::class, 'approve'])->name('businesses.approve');
    Route::patch('/businesses/{business}/reject', [App\Http\Controllers\AdminController::class, 'reject'])->name('businesses.reject');
});