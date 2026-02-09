<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    // Usamos la conexión administrativa configurada para la nueva Profit Plus
    protected $connection = 'sqlsrv_admin'; 
    protected $table = 'saOrdenCompra'; // Nombre de la tabla
    protected $primaryKey = 'doc_num';
    public $timestamps = false; // Profit Plus no usa las columnas created_at/updated_at
}