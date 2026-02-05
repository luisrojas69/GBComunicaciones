<?php

// app/Http/Controllers/NominaController.php
namespace App\Http\Controllers;
use Carbon\Carbon;

use App\Services\NominaService;
use Illuminate\Http\Request;

class NominaController extends Controller
{
    protected $nominaService;

    public function __construct(NominaService $nominaService)
    {
        $this->nominaService = $nominaService;
    }

    /**
     * Muestra la lista de cumpleañeros de la semana.
     */
    public function mostrarCumpleaneros()
    {
        try {
            // 1. Llamar al servicio
            $cumpleaneros = $this->nominaService->getCumpleanerosSemana();
            
            // 2. Formatear la respuesta
            $respuesta = [
                'total_cumpleaneros' => $cumpleaneros->count(),
                'detalles' => $cumpleaneros->map(function ($item) {
                    return [
                        'empleado' => $item->nombre_completo,
                        // Formatear la fecha para mostrar solo Día y Mes
                        'fecha' => Carbon::parse($item->fecha_nac)->format('d/m'), 
                    ];
                })
            ];

            return response()->json($respuesta, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo obtener la lista de cumpleañeros.', 'mensaje' => $e->getMessage()], 500);
        }
    }


    /**
     * Muestra la lista de empleados actualmente de vacaciones.
     */
    public function mostrarPersonalVacaciones()
    {
        try {
            $vacaciones = $this->nominaService->getPersonalEnVacaciones();
            
            $respuesta = [
                'total_en_vacaciones' => $vacaciones->count(),
                'detalles' => $vacaciones->map(function ($item) {
                    return [
                        'empleado' => $item->nombre_completo,
                        'inicio' => Carbon::parse($item->desde)->format('d/m/Y'),
                        'fin' => Carbon::parse($item->hasta)->format('d/m/Y'),
                    ];
                })
            ];

            return response()->json($respuesta, 200);

        } catch (\Exception $e) {
            // Este error puede indicar que la tabla snvacaci no existe o no tiene el nombre correcto
            return response()->json([
                'error' => 'No se pudo obtener la lista de personal de vacaciones.', 
                'mensaje' => 'Verifique el nombre de la tabla (snvacaci) y sus campos de fecha.',
                'error_tecnico' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Muestra el costo total de la nómina para un mes dado.
     * Si no se especifica el mes, usa el mes actual.
     */
    public function mostrarCostoNomina(Request $request)
    {
        try {
            // 1. Determinar el rango de fechas (usando el mes actual como default)
            $fechaBase = Carbon::now();
            
            // Asume que el usuario puede pasar un mes y año opcional por la URL (query string)
            if ($request->has(['month', 'year'])) {
                 $fechaBase = Carbon::createFromDate($request->year, $request->month, 1);
            }

            $fechaInicio = $fechaBase->startOfMonth()->format('Y-m-d');
            $fechaFin = $fechaBase->endOfMonth()->format('Y-m-d');

            // 2. Llamar al servicio de Nómina
            $costoTotal = $this->nominaService->getCostoNomina($fechaInicio, $fechaFin);

            // 3. Devolver la respuesta
            return response()->json([
                'indicador' => 'Costo de Nómina (Asignación)',
                'periodo' => "{$fechaInicio} a {$fechaFin}",
                'monto' => $costoTotal,
                'formato' => number_format($costoTotal, 2, ',', '.') // Formato para presentación
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se pudo obtener el Costo de Nómina.', 
                'mensaje' => 'Error al ejecutar el SP RepNominaCerradaCont o al conectar a la DB.',
                'error_tecnico' => $e->getMessage()
            ], 500);
        }
    }

}