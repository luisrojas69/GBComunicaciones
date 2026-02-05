<?php

// app/Http/Controllers/ContableController.php
namespace App\Http\Controllers;

use App\Services\ContabilidadService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ContableController extends Controller
{
    protected $contabilidadService;

    // 💡 Inyección de Dependencias
    public function __construct(ContabilidadService $contabilidadService)
    {
        $this->contabilidadService = $contabilidadService;
    }

    /**
     * Muestra el Estado de Resultados (Ingresos vs Gastos) para el último mes.
     */
    public function mostrarEstadoResultados(Request $request)
    {
        set_time_limit(180);
        
        try {
            // Definir el rango de fechas (ejemplo: el mes anterior)
            $fechaFin = '2025-11-30';
            $fechaInicio = '2025-11-30';
            
            // 💡 Llamar al Servicio
            $resultados = $this->contabilidadService->getEstadoResultados($fechaInicio, $fechaFin);

            $utilidadNeta = $resultados['ingresos'] - $resultados['gastos'];

            $respuesta = [
                'periodo' => "{$fechaInicio} a {$fechaFin}",
                'ingresos_totales' => $resultados['ingresos'],
                'gastos_totales' => $resultados['gastos'],
                'utilidad_neta' => $utilidadNeta,
                'margen_operacional' => $utilidadNeta > 0 ? round(($utilidadNeta / $resultados['ingresos']) * 100, 2) . '%' : '0%'
            ];

            return response()->json($respuesta, 200);

        } catch (\Exception $e) {
            // Manejo de errores (ej. la base de datos contable no está accesible)
            return response()->json(['error' => 'No se pudo generar el Estado de Resultados.', 'mensaje' => $e->getMessage()], 500);
        }
    }
}