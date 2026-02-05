<?php

// app/Services/ContabilidadService.php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class ContabilidadService
{
    protected $db;

    public function __construct()
    {
        $this->db = DB::connection('sqlsrv_contabilidad');
    }

    // Ingresos vs Gastos (Ejecución de Stored Procedure)
    public function getEstadoResultados(string $fechaInicio, string $fechaFin)
    {
        // Se llama al SP con los 21 parámetros que identificamos:
        $query = "EXEC [dbo].[RepEstadoGananciasPerdidas2KDoce] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";

        // El array debe tener 21 elementos (si tu SP tiene 21 placeholders)
        
        // Ajustemos el array a los 21 parámetros que usaste en la traza:
        $parametros = [
            null, null,                     
            $fechaInicio, $fechaFin,        
            null, null, null, null, null,   
            null, null, null, null,         
            1,                              // @bCuentaconMov (bit)
            null,                           // @iTipo_compro (int)
            null,                           // @sCo_mone (char)
            0,                              // @bExcluir_CtaOrden (bit) -> ¡USAMOS 0!
            null,                           // @iNivel (tinyint)
            0,                              // @bComprobanteCIE (bit) -> 0
            'co_cue', 'ASC'                 // @sCampOrderBy, @sDir
        ];

                // Pasamos los 21 parámetros, usando NULL para los opcionales:
        // Ejecución con el nuevo array de 21 elementos
        $resultadosCrudos = $this->db->select($query, $parametros);
        // ...

        $ingresos = 0;
        $gastos = 0;
        
        // La lógica de Profit Plus es: Ingreso = Saldo Negativo, Egreso = Saldo Positivo.
        foreach ($resultadosCrudos as $cuenta) {
            $saldo = (float) $cuenta->SaldoActual;
            if ($saldo < 0) {
                $ingresos += abs($saldo);
            } else {
                $gastos += abs($saldo);
            }
        }

        return ['ingresos' => $ingresos, 'gastos' => $gastos];
    }
}