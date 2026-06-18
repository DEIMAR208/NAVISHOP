<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;

// Ya tiene el prefijo 'admin'. La URL real será: /admin/dashboard
Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/solicitudes', [AdminDashboardController::class, 'solicitudes'])->name('admin.solicitudes');
