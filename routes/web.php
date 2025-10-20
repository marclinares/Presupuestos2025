<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GastoPresupuestoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// Ruta principal
Route::get('/', [GastoPresupuestoController::class, 'index'])->name('gastos.index');

// Ruta AJAX para buscar
Route::get('/gastos/search', [GastoPresupuestoController::class, 'search']);
Route::put('/gastos/{id}', [GastoPresupuestoController::class, 'update']);
Route::post('/gastos', [GastoPresupuestoController::class, 'store']);
Route::delete('/gastos/{id}', [GastoPresupuestoController::class, 'destroy']);


//Generar PDF
Route::post('/gastos/pdf', [GastoPresupuestoController::class, 'exportarPDF'])->name('gastos.pdf');

Route::get('/gastos/resumen', [GastoPresupuestoController::class, 'resumenGlobal']);
Route::get('/gastos/chart-data', [App\Http\Controllers\GastoPresupuestoController::class, 'chartData'])->name('gastos.chartData');
Route::get('/gastos/chart-data/economico', [GastoPresupuestoController::class, 'chartDataEconomico']);
Route::get('/gastos/chart-data/programa', [GastoPresupuestoController::class, 'chartDataPrograma']);

