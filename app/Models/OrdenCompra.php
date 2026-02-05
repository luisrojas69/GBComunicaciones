<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    // Usamos la conexión administrativa configurada para la nueva Profit Plus
    protected $connection = 'sqlsrv_admin'; 
    protected $table = 'saOrdenCompra'; // Nombre de la tabla
    protected $primaryKey = 'doc_num'; // Asumiendo que este es el código de documento
    public $timestamps = false; // Profit Plus usualmente no usa las columnas created_at/updated_at
}