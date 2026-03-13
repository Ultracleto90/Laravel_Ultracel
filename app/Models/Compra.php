<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'taller_id',
        'proveedor_id',
        'usuario_id', 
        'folio_factura_proveedor',
        'fecha_compra',
        'total_pagado',
        'notas',
    ];

    public function detalles()
    {
        return $this->hasMany(CompraDetalle::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}