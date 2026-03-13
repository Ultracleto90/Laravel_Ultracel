<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;

    protected $table = 'venta_detalles';

    protected $fillable = [
        'venta_id',
        'inventario_id',
        'cantidad',
        'precio_unitario_historico',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }
}