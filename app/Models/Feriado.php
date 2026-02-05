<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feriado extends Model
{
    // 1. Apuntar a la tabla correcta
    protected $connection = 'sqlsrv_nomina';
    protected $table = 'snferiado';
    
    // 2. Desactivar Timestamps
    public $timestamps = false;
    
    // 3. Cast the 'fecha' attribute to a Date object for easy comparison
    protected $casts = [
        'fecha' => 'date', 
    ];
}