<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventario';

    protected $fillable = [
        'taller_id',
        'codigo_barras',
        'nombre',
        'descripcion',
        'costo_compra',
        'precio_venta',
        'stock_actual',
        'stock_minimo',
    ];

    public function taller()
    {
        return $this->belongsTo(Taller::class);
    }
}