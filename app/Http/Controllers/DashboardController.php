<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Services\AdministrativoService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $adminService;

    public function __construct(AdministrativoService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Muestra el indicador de Artículos Críticos
     */
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



}