<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContableController;
use App\Http\Controllers\NominaController;
use App\Http\Controllers\AdministrativoController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// --- Rutas Protegidas ---
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/indicadores/articulos-criticos', [AdministrativoController::class, 'mostrarArticulosCriticos']);
    Route::get('/indicadores/estado-resultados', [ContableController::class, 'mostrarEstadoResultados']);
    Route::get('/indicadores/cumpleaneros', [NominaController::class, 'mostrarCumpleaneros']);
    Route::get('/indicadores/vacaciones', [NominaController::class, 'mostrarPersonalVacaciones']);
    Route::get('/indicadores/costo-nomina', [NominaController::class, 'mostrarCostoNomina']);
    // /indicadores/costo-nomina?month=11&year=2025
    Route::get('/indicadores/requisiciones', [AdministrativoController::class, 'mostrarEstadoRequisiciones']);
    Route::get('/indicadores/ventas-por-productos', [AdministrativoController::class, 'mostrarVentasPorProductos']);
    ///indicadores/ventas-por-productos?fecha_inicio=2010-06-01&fecha_fin=2010-06-30


//Ruta de logout para eliminar el token
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada exitosamente.'], 200);
    });

});


// Ruta pública para obtener el token
Route::post('/login', [AuthController::class, 'login'])->name('login');
