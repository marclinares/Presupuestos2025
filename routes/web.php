<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GastoPresupuestoController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página principal (VISIBLE SIN LOGIN)
Route::get('/', [GastoPresupuestoController::class, 'index'])
    ->name('gastos.index');


// Búsqueda AJAX (VISIBLE SIN LOGIN)
Route::get('/gastos/search', [GastoPresupuestoController::class, 'search']);


// Gráficos (VISIBLE SIN LOGIN)
Route::get('/gastos/resumen', [GastoPresupuestoController::class, 'resumenGlobal']);
Route::get('/gastos/chart-data/programa', [GastoPresupuestoController::class, 'chartDataPrograma']);
Route::get('/gastos/chart-data/economico', [GastoPresupuestoController::class, 'chartDataEconomico']);


// Generar PDF (VISIBLE SIN LOGIN)
Route::post('/gastos/pdf', [GastoPresupuestoController::class, 'exportarPDF'])
    ->name('gastos.pdf');


// CRUD SOLO PARA USUARIOS LOGEADOS
Route::middleware('auth')->group(function () {

    Route::post('/gastos', [GastoPresupuestoController::class, 'store']);
    Route::put('/gastos/{id}', [GastoPresupuestoController::class, 'update']);
    Route::delete('/gastos/{id}', [GastoPresupuestoController::class, 'destroy']);

});

// Solo usuarios con rol admin pueden gestionar usuarios
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/usuarios/nuevo', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/usuarios', [UserController::class, 'store'])->name('users.store');
});


// Rutas de autenticación Laravel Breeze
require __DIR__.'/auth.php';