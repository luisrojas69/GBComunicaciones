<?php

// app/Services/NominaService.php
namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado;
use Carbon\Carbon;
use App\Models\ControlVacacion;

class NominaService
{
    protected $db;

    public function __construct()
    {
        $this->db = DB::connection('sqlsrv_nomina');
    }

     /**
     * Calcula el Costo Total de Nómina (ASIGNACION) para un período específico.
     * Usa el SP consolidado RepNominaCerradaCont.
     */
    public function getCostoNomina(string $fechaInicio, string $fechaFin)
    {
        // El SP requiere 13 parámetros, la mayoría NULL.
        $query = "EXEC [dbo].[RepNominaCerradaCont] ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";

        $parametros = [
            $fechaInicio, $fechaFin, // @sdFec_Emis_d, @sdFec_Emis_h
            null, null, null, null, null, null, null, null, // Parámetros intermedios NULL
            'co_cont', 'ASC', // Orden
            0 // @bHeaderRep
        ];

        $resultados = $this->db->select($query, $parametros);

        $costoBruto = 0;
        foreach ($resultados as $nomina) {
            // Sumar la columna ASIGNACION
            $costoBruto += (float) $nomina->ASIGNACION;
        }

        return round($costoBruto, 2);
    }

    // Cumpleañeros de la Semana
   public function getCumpleanerosSemana()
    {
        // 1. Calcular el día y mes actual
        $hoy = Carbon::now();
        $diaActual = $hoy->day;
        $mesActual = $hoy->month;
        
        // 2. Determinar los días de la semana actual
        $diaInicioSemana = $hoy->startOfWeek(Carbon::MONDAY)->day;
        $diaFinSemana = $hoy->endOfWeek(Carbon::SUNDAY)->day;
        
        // La consulta debe traer solo los empleados que tengan fecha de nacimiento.
        return Empleado::select('nombre_completo', 'fecha_nac')
            ->whereNotNull('fecha_nac')
            // Filtrar por el MES actual
            ->where($this->db->raw('MONTH(fecha_nac)'), $mesActual)
            // Filtrar donde el DÍA esté entre el inicio y fin de la semana
            ->whereBetween($this->db->raw('DAY(fecha_nac)'), [$diaInicioSemana, $diaFinSemana])
            ->orderBy($this->db->raw('DAY(fecha_nac)'))
            ->get();
    }


    public function getCumpleanerosSemanaConSuDepartamento(): Collection
    {
        $hoy = Carbon::now();
    $mesActual = $hoy->month;
    
    // Obtener los límites de la semana
    $diaInicioSemana = $hoy->copy()->startOfWeek(Carbon::MONDAY)->day;
    $diaFinSemana = $hoy->copy()->endOfWeek(Carbon::SUNDAY)->day;
    
    // 1. Obtener la Ficha/Modelo Eloquent de Empleado (que representa la tabla snemple)
    // 2. Aplicar el JOIN a la tabla sndepart para obtener el nombre del departamento
    
    $cumpleaneros = Empleado::select(
            'snemple.nombre_completo', 
            'snemple.fecha_nac',
            'snemple.sexo',
            // ⭐ AÑADIR SELECT RAW PARA OBTENER EL DÍA DE LA SEMANA
            $this->db->raw('DATENAME(dw, snemple.fecha_nac) AS dia_semana'),
            
            // ⭐ AÑADIR LA COLUMNA DEL DEPARTAMENTO CON ALIAS
            'sndepart.des_depart AS departamento'
        )
        // ⭐ JOIN NECESARIO A LA TABLA DE DEPARTAMENTOS (asumo que Empleado model apunta a snemple)
        ->join('sndepart', 'snemple.co_depart', '=', 'sndepart.co_depart') 
        
        ->whereNotNull('snemple.fecha_nac')
        
        // Filtrar por el MES actual
        ->where($this->db->raw('MONTH(snemple.fecha_nac)'), $mesActual)

        ->where('status', 'A')
        
        // Filtrar donde el DÍA esté entre el inicio y fin de la semana
        ->whereBetween($this->db->raw('DAY(snemple.fecha_nac)'), [$diaInicioSemana, $diaFinSemana])
        
        ->orderBy($this->db->raw('DAY(snemple.fecha_nac)'))
        
        ->get();
        
    return $cumpleaneros;
    }


    public function getPersonalEnVacaciones()
    {
        $fechaHoy = Carbon::now()->format('Y-m-d');
        
        // Unir ControlVacacion con Empleado para obtener el nombre
        return ControlVacacion::on('sqlsrv_nomina')
            ->join('snemple', 'snvacaci.cod_emp', '=', 'snemple.cod_emp')
            ->select('snemple.nombre_completo', 'snemple.ci', 'snvacaci.desde', 'snvacaci.hasta', 'snvacaci.dias')
            // Filtrar donde la fecha de hoy está entre el inicio y el fin de la vacación
            ->where('snvacaci.desde', '<=', $fechaHoy)
            ->where('snvacaci.hasta', '>=', $fechaHoy)
            ->get();
    }


   
    
}