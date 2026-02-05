<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $connection = 'sqlsrv_admin'; 
    protected $table = 'saArticulo'; 
    protected $primaryKey = 'co_art';
    public $timestamps = false; 
}