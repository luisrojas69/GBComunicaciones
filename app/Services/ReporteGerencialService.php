<?php 
namespace App\Services;

use App\Services\NominaService;
use App\Services\AdministrativoService;
use App\Services\ProduccionService;
use Carbon\Carbon; 

class ReporteGerencialService
{
    // 💡 PASO 1: Declarar las propiedades de los servicios
    protected $nominaService;
    protected $administrativoService;
    protected $produccionService;

    // 💡 PASO 2: Inyectar y asignar en el constructor
    public function __construct(NominaService $nominaService, AdministrativoService $administrativoService, ProduccionService $produccionService)
    {
        $this->nominaService = $nominaService;
        $this->administrativoService = $administrativoService;
        $this->produccionService = $produccionService;
    }

    public function generarDatosReporte(string $fechaInicio, string $fechaFin)
    {
        // 1. Recolección de Datos
        
        // Ejecutamos el método que ya tiene la lógica de filtro de rango
        $ventasTop = $this->administrativoService->getVentasPorProducto($fechaInicio, $fechaFin);

        // Los artículos críticos no usan rango de fechas, usan la lógica de bandera y stock actual
        $articulosCriticos = $this->administrativoService->getArticulosCriticos();
        $estadoPozos = $this->produccionService->obtenerEstadoPozos();
        // ...
        
        return [
            'periodo' => "{$fechaInicio} a {$fechaFin}",
            
            // Datos del Módulo Nómina
            // Asumiendo que getCostoNomina no tiene la lógica de formato en el servicio
            'costo_nomina' => $this->nominaService->getCostoNomina($fechaInicio, $fechaFin),
            'personal_vacaciones' => $this->nominaService->getPersonalEnVacaciones(),
            
            // Datos del Módulo Administrativo
            'requisiciones' => $this->administrativoService->getRequisicionesAprobadasMapeadas(),
            'criticos' => $articulosCriticos, // Usando la variable obtenida
            'ventas_top' => $ventasTop, // Usando la variable obtenida
            
            // Datos del Módulo Contabilidad
            'estado_resultados' => 'Pendiente por optimización de índices en DB.',
            
            // Datos de Cumpleañeros (usamos el método que ya tienes)
            'cumpleaneros_semana' => $this->nominaService->getCumpleanerosSemanaConSuDepartamento(),

            'pluviometria' => $this->produccionService->obtenerResumenPluviometrico(),
            
            'pozos'         => $estadoPozos['listado'],
            'resumen_pozos' => $estadoPozos['resumen'],
        ];
    }
}