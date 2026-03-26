<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taller extends Model
{
    use HasFactory;

    protected $table = 'talleres';

    // ¡Aquí agregamos los nuevos campos para que Laravel nos deje guardarlos!
    protected $fillable = [
        'plan_id',
        'nombre_negocio',
        'rfc_tax_id',
        'configuracion',
        'fecha_vencimiento_licencia',
        'estado_licencia', // <-- NUEVO
        'token_licencia',  // <-- NUEVO
        'activo',
    ];

    protected $casts = [
        'configuracion' => 'array',
        'activo' => 'boolean',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function inventario()
    {
        return $this->hasMany(Inventario::class);
    }
}