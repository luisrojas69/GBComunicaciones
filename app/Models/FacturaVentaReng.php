<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaVentaReng extends Model
{
    protected $connection = 'sqlsrv_admin'; 
    protected $table = 'saFacturaVentaReng'; // Renglones de la factura
    public $timestamps = false; 
}