<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;
Route::get('/', function () {
    return view('welcome');
});


// Rutas de desarrollo - Deshabilitar en producción
Route::get('dev/reporte-gerencial-html', [App\Http\Controllers\ReporteController::class, 'testGerencialView'])->name('dev.reporte.html');