<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'taller_id',
        'cliente_id',
        'tipo',
        'marca',
        'modelo',
        'imei_serie',
        'contrasena_desbloqueo',
        'detalles_fisicos',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function reparaciones()
    {
        return $this->hasMany(Reparacion::class);
    }
}