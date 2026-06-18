<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Usuario\PostulacionController;
use App\Http\Controllers\Usuario\QusuarioController;
use App\Http\Controllers\Usuario\FusuarioController;
use App\Http\Controllers\Usuario\VistaPublicaNegocioController;
use App\Http\Controllers\Usuario\PusuarioController;
use App\Http\Controllers\Usuario\VusuarioController;
use App\Http\Controllers\Usuario\EiusuarioController;

// Esta ruta es pública ahora
Route::get('/usuario', [QusuarioController::class, 'index'])->name('usuario');
Route::get('/promociones', [PusuarioController::class, 'index'])->name('usuario.promociones');
Route::get('/videos', [VusuarioController::class, 'index'])->name('usuario.videos');

// Estas rutas requieren que el usuario esté logueado
Route::middleware('auth')->group(function () {
    Route::get('/negocio/{id}', [VistaPublicaNegocioController::class, 'show'])->name('negocio.publico');
    Route::get('/perfil', [EiusuarioController::class, 'index'])->name('usuario.perfil');
    Route::get('/fusuario', [FusuarioController::class, 'index'])->name('fusuario');
    Route::post('/negocios', [FusuarioController::class, 'store'])->name('negocios.store');
    Route::get('/negocios', [FusuarioController::class, 'listado'])->name('negocios.listado');
    Route::get('/postular', [PostulacionController::class, 'create'])->name('usuario.postular');
    Route::post('/postular', [PostulacionController::class, 'store'])->name('usuario.guardar_postulacion');
});
