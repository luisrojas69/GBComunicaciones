<?php
// app/Services/AdministrativoService.php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\CotizacionProveedor;
use Carbon\Carbon;

class AdministrativoService
{
    protected $db;

    public function __construct()
    {
        $this->db = DB::connection('sqlsrv_admin');
    }

    // Ventas Mensuales
    public function getVentasMensuales(string $fechaInicio, string $fechaFin)
    {
        // Se asume saFacturaVenta para Ventas. co_cli es solo un ejemplo de JOIN.
        return $this->db->table('saFacturaVenta')
            ->select($this->db->raw('SUM(total_neto) as TotalVentas'))
            ->whereBetween('fec_emis', [$fechaInicio, $fechaFin])
            ->first();
    }

    // Flujo de Caja (Cuentas por Cobrar - Próximos 30 días)
    public function getProyeccionCxC()
    {
        $fechaProyeccion = Carbon::now()->addDays(30)->format('Y-m-d');
        
        return $this->db->table('saDocumentoVenta')
            ->select($this->db->raw('SUM(saldo) as MontoPendiente'))
            ->where('saldo', '>', 0)
            ->where('anulado', 0) // Asumido
            // Asumiendo que fec_venc es la columna clave
            ->whereBetween('fec_venc', [Carbon::now()->format('Y-m-d'), $fechaProyeccion])
            ->first();
    }
    
    // Órdenes de Compra (OC Abiertas)
    public function getOrdenesCompraStatus()
    {
        // status = 0 (Sin procesar), 1 (Parcial), 2 (Procesada)
        return $this->db->table('saOrdenCompra')
            ->select('status', $this->db->raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();
    }
    
    // Requisiciones Aprobadas (Usando el Modelo)
      public function getRequisicionesAprobadas()
    {
        return CotizacionProveedor::where('campo5', 'autorizada')
            ->select('status', $this->db->raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                // Mapear el status numérico a un estado legible
                $estado = match ((int)$item->status) {
                    0 => 'Sin Procesar',
                    1 => 'Parcialmente Procesada',
                    2 => 'Procesada',
                    default => 'Desconocido',
                };
                return [$estado => $item->total];
            });
    }


    public function getRequisicionesAprobadasMapeadas(): array // Aseguramos que devuelve un array
    {
        $resultadosMapeados = CotizacionProveedor::where('campo5', 'autorizada')
            ->select('status', $this->db->raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                // Mapear el status numérico a un estado legible
                $estado = match ((int)$item->status) {
                    0 => 'Sin Procesar',
                    1 => 'Parcialmente Procesada',
                    2 => 'Procesada',
                    default => 'Desconocido',
                };
                return [$estado => (int)$item->total];
            }); // Devuelve una Collection: ['Procesada' => 3, 'Sin Procesar' => 2, ...]

        // --- CÁLCULO DE LA ESTRUCTURA ESPERADA POR LA VISTA ---
        
        $procesadas = ($resultadosMapeados['Procesada'] ?? 0) + ($resultadosMapeados['Parcialmente Procesada'] ?? 0);
        $sinProcesar = $resultadosMapeados['Sin Procesar'] ?? 0;
        $parcialmenteProcesadas = $resultadosMapeados['Parcialmente Procesada'] ?? 0;
        $totalmenteprocesadas = $resultadosMapeados['Procesada'] ?? 0;
        
        $totalAprobadas = $procesadas + $sinProcesar;
        
        // El porcentaje de cumplimiento se basa en las procesadas/total
        $porcentajeCumplimiento = ($totalAprobadas > 0) 
            ? round((($totalmenteprocesadas + ($parcialmenteProcesadas*0.5)) / $totalAprobadas) * 100, 1) 
            : 0;

        // Retornamos la estructura que la vista espera (un array asociativo)
        return [
            'total_aprobadas' => $totalAprobadas,
            'cumplimiento' => [
                'porcentaje_ponderado' => $porcentajeCumplimiento,
                'procesadas' => $procesadas,
                // Opcional: puedes añadir el detalle de cada estado si lo necesitas
                'detalle' => $resultadosMapeados->toArray() 
            ],
        ];
    }

    // Artículos Críticos (Stock <= Mínimo)
    public function getArticulosConStockMenorAlMinimo()
    {
        return $this->db->table('saArticulo AS A')
            ->join('saStockAlmacen AS S', 'A.co_art', '=', 'S.co_art')
            ->select('A.co_art', 'A.art_des', 'A.stock_min', $this->db->raw('SUM(S.stock) as StockActual'))
            ->groupBy('A.co_art', 'A.art_des', 'A.stock_min')
            // Condición de stock crítico
            ->havingRaw('SUM(S.stock) <= A.stock_min')
            ->get();
    }


    /**
     * Obtiene los articulos marcados como criticos en el campo 5 que tienen stock menos al stock minimo.
     */
 

    /**
     * Obtiene artículos cuyo stock actual es menor o igual al stock mínimo.
     * Retorna una Collection para fácil manejo en la vista.
     */
    public function getArticulosCriticos()
    {
        return $this->db->table('saArticulo AS A')
            ->join('saStockAlmacen AS S', 'A.co_art', '=', 'S.co_art')
            ->select(
                'A.co_art', 
                'A.art_des', 
                'A.stock_min', 
                $this->db->raw('SUM(S.stock) as StockActual')
            )
            // 💡 FILTRO DE BANDERA: Solo artículos donde campo5 = 'critico'
            ->where('A.campo5', 'critico') 
            
            ->groupBy('A.co_art', 'A.art_des', 'A.stock_min')
            
            // FILTRO DE STOCK: Solo si el stock actual está por debajo del mínimo
            ->havingRaw('SUM(S.stock) <= A.stock_min')
            ->get();
    }
    
    
    /**
     * Obtiene las ventas agrupadas por producto en un rango de fechas.
     */
    public function getVentasPorProducto(string $fechaInicio, string $fechaFin)
    {
        // Alias FV (Factura Venta), FVR (Renglón), A (Artículo)
        return $this->db->table('saFacturaVentaReng AS FVR')
            // JOIN con la cabecera de la factura para filtrar por fecha
            ->join('saFacturaVenta AS FV', 'FVR.doc_num', '=', 'FV.doc_num')
            // JOIN con la tabla de artículos para la descripción
            ->join('saArticulo AS A', 'FVR.co_art', '=', 'A.co_art')
            
            // 1. FILTRO DE FECHA: Usar la fecha de la factura (fec_emis)
            //->whereBetween('FV.fec_emis', ['2025-01-01', '2026-02-28'])
            ->whereBetween('FV.fec_emis', [$fechaInicio, $fechaFin])
            
            // 2. FILTRO DE ANULADO: Asegurarse de que no sean facturas anuladas (status = '0')
            ->where('FV.anulado', 0) 
            
            // 3. SELECCIÓN Y AGRUPACIÓN
            ->select(
                'FVR.co_art',
                'A.art_des',
                $this->db->raw('SUM(FVR.total_art) as CantidadVendida'), // Volumen
                $this->db->raw('SUM(FVR.total_art) as MontoTotal')     // Monto (antes de impuestos/descuentos globales)
            )
            ->groupBy('FVR.co_art', 'A.art_des')
            ->orderByDesc('MontoTotal') // Ordenar por el monto de venta
            ->limit(5) // Traer solo los 5 productos principales (Top 10)
            ->get();
    }
}