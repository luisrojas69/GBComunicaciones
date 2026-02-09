<?php

namespace App\Models\Produccion\Pozos;

use Illuminate\Database\Eloquent\Model;


class Pozo extends Model
{   
    protected $connection = 'sqlsrv_gbsuite'; 
    protected $table = 'pozos_y_estaciones';

    protected $casts = [
        'fecha_ultimo_cambio' => 'datetime',
    ];

}