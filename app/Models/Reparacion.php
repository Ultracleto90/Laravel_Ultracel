<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reparacion extends Model
{
    use HasFactory;

    protected $table = 'reparaciones';

    protected $fillable = [
        'taller_id',
        'equipo_id',
        'tecnico_id',
        'falla_reportada',
        'diagnostico_tecnico',
        'estado',
        'mano_obra_costo',
        'total_estimado',
        'fecha_promesa',
        'fecha_entrega_real',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function tecnico()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }

    public function insumos()
    {
        return $this->hasMany(ReparacionInsumo::class);
    }
}