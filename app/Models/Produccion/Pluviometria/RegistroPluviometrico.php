<?php

namespace App\Models\Produccion\Pluviometria;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produccion\Areas\Sector;

class RegistroPluviometrico extends Model
{
    protected $connection = 'sqlsrv_gbsuite'; 
    protected $table = 'registros_pluviometricos';
    
    protected $casts = [
        'fecha' => 'date',
        'cantidad_mm' => 'float'
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'id_sector');
    }

}