<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReparacionInsumo extends Model
{
    use HasFactory;

    protected $table = 'reparacion_insumos';

    protected $fillable = [
        'reparacion_id',
        'inventario_id',
        'cantidad',
        'precio_cobrado_historico',
    ];

    public function reparacion()
    {
        return $this->belongsTo(Reparacion::class);
    }

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }
}