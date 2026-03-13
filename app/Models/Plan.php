<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'planes';

    protected $fillable = [
        'nombre',
        'precio_mensual',
        'max_usuarios',
        'max_sucursales',
    ];

    public function talleres()
    {
        return $this->hasMany(Taller::class);
    }
}