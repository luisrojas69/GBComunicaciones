<?php

// app/Http/Controllers/AdministrativoController.php
namespace App\Http\Controllers;

use App\Services\AdministrativoService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdministrativoController extends Controller
{
    protected $adminService;

    public function __construct(AdministrativoService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Muestra el estado de las requisiciones aprobadas.
     */
    public function mostrarEstadoRequisiciones()
    {
        // ... (llamada al servicio) ...
        $estados = $this->adminService->getRequisicionesAprobadas();
        $totalRequisiciones = $estados->sum();
        
        // 1. Obtener conteos (asegurándose de que son enteros)
        $procesadas = (int) $estados->get('Procesada', 0);
        $parciales = (int) $estados->get('Parcialmente Procesada', 0); 
        
        // 2. Aplicar la ponderación: Procesadas vale 1.0, Parciales vale 0.5
        $puntuacionTotal = ($procesadas * 1.0) + ($parciales * 0.5);
        
        // 3. Calcular el porcentaje
        $porcentajeCumplimiento = 0;
        if ($totalRequisiciones > 0) {
            $porcentajeCumplimiento = round(($puntuacionTotal / $totalRequisiciones) * 100, 2);
        }
        
        $respuesta = [
            'total_aprobadas' => $totalRequisiciones,
            'detalles' => $estados,
            'cumplimiento' => [
                // ... (otros campos) ...
                'parcialmente_cumplido' => $parciales, // Agregar este detalle
                'porcentaje_ponderado' => $porcentajeCumplimiento,
                'formato' => "{$porcentajeCumplimiento}%"
            ]
        ];
        
        return response()->json($respuesta, 200);
    }


    public function mostrarArticulosCriticos()
    {
        try {
            // 💡 1. Llamar al método del servicio
            $articulosCriticos = $this->adminService->getArticulosCriticos();
            
            $totalCriticos = $articulosCriticos->count();

            // 1. Lógica Condicional para el mensaje
            $mensaje = ($totalCriticos == 0) 
                       ? 'Todos los artículos críticos marcados en el sistema cuentan con stock suficiente.' 
                       : 'ATENCIÓN: Se requiere reposición urgente para los artículos listados.';
          
            // 💡 2. Formatear y preparar la respuesta
            $respuesta = [
                'total_criticos' => $totalCriticos,
                'mensaje_estado' => $mensaje,
                'detalles' => $articulosCriticos->map(function ($item) {
                    return [
                        'codigo' => $item->co_art,
                        'descripcion' => $item->art_des,
                        'stock_minimo' => (int) $item->stock_min,
                        'stock_actual' => (int) $item->StockActual,
                        'diferencia' => (int) $item->StockActual - (int) $item->stock_min,
                        
                    ];
                })
            ];

            // 💡 3. Devolver la respuesta (puedes devolver una vista o JSON)
            return response()->json($respuesta, 200);

        } catch (\Exception $e) {
            // Manejo de errores de la base de datos o conexión
            return response()->json(['error' => 'No se pudo obtener el reporte de críticos.', 'mensaje' => $e->getMessage()], 500);
        }
    }



    /**
     * Muestra el Top 10 de ventas por productos para un período.
     */

    public function mostrarVentasPorProductos(Request $request)
    {
        try {
            // 1. DETERMINAR RANGO DE FECHAS
            // 1. Determinar el rango de fechas (usando el mes actual como default)
            $fechaBase = Carbon::now();
            
            // Si el usuario envía 'fecha_inicio' y 'fecha_fin' por GET, úsalas.
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            
            if (!$fechaInicio || !$fechaFin) {
                // 💡 RANGO POR DEFECTO PARA DEMO: Cubre tu rango de datos de 2009 a 2012 (SOLO PARA PRUEBAS)
                //$fechaInicio = '2009-01-01';
                //$fechaFin = '2012-12-31';
                
                //Descomentar cuando pásemos a productivo
                $fechaInicio = $fechaBase->startOfMonth()->format('Y-m-d');
                $fechaFin = $fechaBase->endOfMonth()->format('Y-m-d');
            }
            
            // Opcional: Validar que las fechas tengan el formato correcto (Y-m-d)
            // Aquí asumimos que ya tienen el formato correcto, si no, es buena idea validarlo.

            // 2. Llamar al servicio
            $topVentas = $this->adminService->getVentasPorProducto($fechaInicio, $fechaFin);

            // 3. Formatear la respuesta
            $respuesta = [
                'periodo_solicitado' => "{$fechaInicio} a {$fechaFin}",
                'total_productos' => $topVentas->count(),
                'detalles' => $topVentas->map(function ($item) {
                    return [
                        'codigo' => trim($item->co_art),
                        'descripcion' => $item->art_des,
                        'cantidad_vendida' => (int) $item->CantidadVendida,
                        'monto_total' => round($item->MontoTotal, 2),
                    ];
                })
            ];

            return response()->json($respuesta, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se pudo obtener el reporte de ventas por productos.', 
                'mensaje' => 'Verifique el formato de fechas y la conexión a la base de datos.',
                'error_tecnico' => $e->getMessage()
            ], 500);
        }
    }

}