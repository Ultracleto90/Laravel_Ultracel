<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores'; 

    protected $fillable = [
        'taller_id',
        'nombre_empresa',
        'nombre_contacto',
        'telefono',
        'email',
        'rfc_tax_id',
    ];

    public function taller()
    {
        return $this->belongsTo(Taller::class);
    }

    public function compras()
    {
        return $this->hasMany(Compra::class);
    }
}