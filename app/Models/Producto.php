<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    protected $table = "productos";
    protected $fillaq = [
        "clave_producto",
        "Nombre_producto",
        "costo",
        "cantidad",
        "stock_max",
        "stock_min",
        "activo",
    ];   
}
