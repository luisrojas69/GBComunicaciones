<?php

namespace App\Models\Produccion\Areas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Produccion\Pluviometria\RegistroPluviometrico;

class Sector extends Model
{
    protected $connection = 'sqlsrv_gbsuite'; 
    protected $table = 'sectores';

    public function pluviometrias(): HasMany
    {
        return $this->hasMany(RegistroPluviometrico::class, 'id_sector');
    }

    // Relación para obtener la última lluvia registrada
    public function ultimaLluvia()
    {
        return $this->hasOne(RegistroPluviometrico::class, 'id_sector')
                    ->where('cantidad_mm', '>', 0)
                    ->latest('fecha');
    }

}