<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ControlVacacion extends Model
{
    // Conexión a la base de datos de Nómina (GB_N)
    protected $connection = 'sqlsrv_nomina'; 
    protected $table = 'snvacaci'; // Tabla asumida para el disfrute
    protected $primaryKey = 'co_vacaci'; // Asumimos una PK
    public $timestamps = false;
}