<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ReporteController extends Controller
{
    /**
     * Muestra la plantilla gerencial en formato HTML con datos de prueba.
     * Útil para desarrollo y diseño CSS.
     */
    public function testGerencialView()
    {
        // 1. Prepara los datos MOCK (Simulados)
        $data = $this->getMockReportData();

        // 2. Retorna la vista Blade directamente
        // NOTA: La ruta asume que el archivo está en 'resources/views/pdfs/gerencial.blade.php'
        return view('pdfs.gerencialOLD', compact('data'));
    }

    /**
     * Función para generar datos de prueba que satisfagan todas las tarjetas.
     */
    private function getMockReportData()
    {
        return [
            // Header Data
            'periodo_start' => '2025-12-01',
            'periodo_end' => '2025-12-09',
            'periodo' => '2025-12-09 al 2025-12-20',
            
            // KPI Nómina
            'costo_nomina' => 1250000.75,
            
            // KPI Requisiciones
            'requisiciones' => [
                'total_aprobadas' => 100,
                'cumplimiento' => [
                    'procesadas' => 78, // Total Procesadas (Completadas + Parciales)
                    'porcentaje_ponderado' => 78.0, // Cumplimiento del 78%
                    'detalle' => [
                        'Parcialmente Procesada' => 10,
                        'Sin Procesar' => 22, // 100 - 78
                    ],
                ],
            ],

            // Tabla Inventario Crítico
            'criticos' => new Collection([
                (object) ['co_art' => 'AL-001', 'art_des' => 'Alimento Concentrado Bovino', 'StockActual' => 12, 'stock_min' => 50],
                (object) ['co_art' => 'VAC-003', 'art_des' => 'Vacuna Triple (100 dosis)', 'StockActual' => 5, 'stock_min' => 15],
            ]),

            // Tabla Vacaciones
            'personal_vacaciones' => new Collection([
                (object) ['ci' => '12345678', 'nombre_completo' => 'Ana M. Pérez', 'desde' => '15/12/2025','hasta' => '15/12/2025', 'dias' => 15],
                (object) ['ci' => '98765432', 'nombre_completo' => 'Carlos A. Gómez', 'desde' => '20/12/2025', 'hasta' => '15/12/2025', 'dias' => 7],
            ]),

            // Tabla Ventas Top
            'ventas_top' => new Collection([
                (object) ['co_art' => '30311025','art_des' => 'Ganado de Engorde 500Kg','CantidadVendida' => 550, 'MontoTotal' => 550000.00],
                (object) ['co_art' => '30311025','art_des' => 'Leche Pasteurizada','CantidadVendida' => 550, 'MontoTotal' => 320000.00],
                (object) ['co_art' => '30311025','art_des' => 'Queso Telita (KG)','CantidadVendida' => 550, 'MontoTotal' => 150000.00],
            ]),

            // Tabla Cumpleañeros (con campo sexo)
            'cumpleaneros_semana' => new Collection([
                (object) ['nombre_completo' => 'Gabriela Soteldo', 'fecha_nac' => '15/12/2025', 'departamento' => 'Ventas', 'dia_semana' => 'Lunes', 'sexo' => 'F'],
                (object) ['nombre_completo' => 'Pedro Ramírez', 'fecha_nac' => '15/12/2025', 'departamento' => 'Producción', 'dia_semana' => 'Viernes', 'sexo' => 'M'],
            ]),
        ];
    }
}