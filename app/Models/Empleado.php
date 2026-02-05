<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{   
    protected $connection = 'sqlsrv_nomina';
    protected $table = 'snemple';
    protected $primaryKey = 'cod_emp';
    public $timestamps = false;

    // ********** SOLUCIÓN **********
    protected $casts = [
        'fecha_nac' => 'date', // Convierte esta cadena a un objeto Carbon (Fecha)
        'fecha_ing' => 'date', // Convierte esta cadena a un objeto Carbon (Fecha)
    ];
    // ******************************
}