<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'compra_id',
        'inventario_id',
        'cantidad',
        'costo_unitario',
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }
}