<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CotizacionProveedor extends Model
{
    // Conexión a la base de datos Administrativa (GB_A)
    protected $connection = 'sqlsrv_admin'; 
    protected $table = 'saCotizacionProveedor';
    protected $primaryKey = 'doc_num';
    public $timestamps = false; // Profit Plus no usa created_at/updated_at
}