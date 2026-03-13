<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'taller_id',
        'nombre',
        'telefono',
        'email',
        'direccion',
    ];

    public function taller()
    {
        return $this->belongsTo(Taller::class);
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}