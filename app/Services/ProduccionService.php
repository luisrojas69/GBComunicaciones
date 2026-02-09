<?php

namespace App\Services;

use App\Models\Produccion\Areas\Sector;
use App\Models\Produccion\Pozos\Pozo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProduccionService
{
    
    protected $db;

    public function __construct()
    {
        $this->db = DB::connection('sqlsrv_gbsuite');
    }

    /**
     * Obtiene el resumen pluviométrico por sector.
     */
    public function obtenerResumenPluviometrico()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $hoy = Carbon::now();

        return Sector::with(['ultimaLluvia'])
            ->get()
            ->map(function ($sector) use ($inicioMes, $hoy) {
                
                // 1. Acumulado del mes actual
                $acumuladoMes = $sector->pluviometrias()
                    ->whereBetween('fecha', [$inicioMes, $hoy])
                    ->sum('cantidad_mm');

                // 2. Días sin lluvia (Desde la última lluvia con cantidad > 0)
                $ultimaLluvia = $sector->ultimaLluvia;
                $diasSinLluvia = $ultimaLluvia 
                    ? (int) $hoy->diffInDays($ultimaLluvia->fecha) 
                    : null;

                return [
                    'nombre'          => $sector->nombre,
                    'codigo'          => $sector->codigo_sector,
                    'acumulado_mes'   => $acumuladoMes,
                    'ultima_fecha'    => $ultimaLluvia ? $ultimaLluvia->fecha->format('d/m/Y') : 'N/A',
                    'ultima_cantidad' => $ultimaLluvia ? $ultimaLluvia->cantidad_mm : 0,
                    'dias_sin_lluvia' => $diasSinLluvia,
                ];
            });
    }

    public function obtenerEstadoPozos()
    {
        // 1. Obtenemos la colección base
        $pozos = Pozo::orderBy('tipo_activo', 'asc')
            ->orderBy('nombre', 'asc')
            ->get();

        $total = $pozos->count();

        // 2. Mapeamos el listado detallado para la tabla
        $listado = $pozos->map(function ($pozo) {
            return [
                'nombre'          => $pozo->nombre,
                'tipo'            => $pozo->tipo_activo,
                'subtipo'         => $pozo->subtipo_pozo ?? 'N/A',
                'estatus'         => $pozo->estatus_actual,
                'fecha_cambio'    => $pozo->fecha_ultimo_cambio ? $pozo->fecha_ultimo_cambio->format('d/m/Y H:i') : 'Sin registro',
                'dias_en_estatus' => $pozo->fecha_ultimo_cambio ? ($pozo->fecha_ultimo_cambio->diffInDays(now()) * -1) : 0,
            ];
        });

        // 3. Calculamos el resumen para las barras de progreso
        $resumen = [
            'OPERATIVO' => [
                'count' => $pozos->where('estatus_actual', 'OPERATIVO')->count(),
                'porcentaje' => $total > 0 ? ($pozos->where('estatus_actual', 'OPERATIVO')->count() / $total) * 100 : 0,
            ],
            'PARADO' => [
                'count' => $pozos->where('estatus_actual', 'PARADO')->count(),
                'porcentaje' => $total > 0 ? ($pozos->where('estatus_actual', 'PARADO')->count() / $total) * 100 : 0,
            ],
            'EN_MANTENIMIENTO' => [
                'count' => $pozos->where('estatus_actual', 'EN_MANTENIMIENTO')->count(),
                'porcentaje' => $total > 0 ? ($pozos->where('estatus_actual', 'EN_MANTENIMIENTO')->count() / $total) * 100 : 0,
            ],
            'total_activos' => $total
        ];

        // Devolvemos ambos para que el Service principal los reparta
        return [
            'listado' => $listado,
            'resumen' => $resumen
        ];
    }
}