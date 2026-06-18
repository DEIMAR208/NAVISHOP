<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Creador\CreadorDashboardController;
use App\Http\Controllers\Creador\DcreadorController;
use App\Http\Controllers\Creador\PcreadorController;
use App\Http\Controllers\Creador\TcreadorController;
use App\Http\Controllers\Creador\VcreadorController;
use App\Http\Controllers\Creador\MinegosioController;
use App\Http\Controllers\Creador\ProductoController;
use App\Http\Controllers\Creador\VistaNegocioController;
use App\Http\Controllers\Creador\CpromocionesController;

Route::get('/dashboard', [CreadorDashboardController::class, 'index'])->name('creador.dashboard');
Route::get('/perfil', [PcreadorController::class, 'index'])->name('creador.perfil');
Route::get('/videos/crear', [VcreadorController::class, 'create'])->name('creador.videos.crear');
Route::get('/videos', [VcreadorController::class, 'index'])->name('creador.videos');
Route::post('/videos', [VcreadorController::class, 'store'])->name('creador.videos.store');
Route::get('/promociones', [CpromocionesController::class, 'index'])->name('creador.promociones');
Route::post('/promociones', [DcreadorController::class, 'store'])->name('promociones.store');
Route::get('/catalogo', [TcreadorController::class, 'index'])->name('creador.catalogo');
Route::post('/catalogo', [TcreadorController::class, 'store'])->name('catalogo.store');
Route::post('/catalogo/{id}/update', [TcreadorController::class, 'update'])->name('catalogo.update');
Route::get('/catalogo/{id}', [TcreadorController::class, 'show'])->name('catalogo.show');
Route::delete('/catalogo/{id}', [TcreadorController::class, 'destroy'])->name('catalogo.destroy');
Route::get('/crear-promociones', [DcreadorController::class, 'index'])->name('creador.crear-promociones');
Route::get('/mi-negocio', [MinegosioController::class, 'index'])->name('creador.minegocio');

Route::post('/producto', [ProductoController::class, 'store'])->name('producto.store');
Route::post('/producto/{id}/update', [ProductoController::class, 'update'])->name('producto.update');
Route::delete('/producto/{id}', [ProductoController::class, 'destroy'])->name('producto.destroy');
Route::post('/promociones/{id}/update', [CpromocionesController::class, 'update'])->name('promociones.update');
Route::delete('/promociones/{id}', [CpromocionesController::class, 'destroy'])->name('promociones.destroy');
Route::get('/vista-negocio/{id}', [VistaNegocioController::class, 'show'])->name('creador.vista-negocio');


