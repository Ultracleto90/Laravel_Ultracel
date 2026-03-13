<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'taller_id',
        'name',
        'email',
        'password',
        'rol',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean',
    ];

    public function taller()
    {
        return $this->belongsTo(Taller::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'vendedor_id');
    }

    public function reparaciones()
    {
        return $this->hasMany(Reparacion::class, 'tecnico_id');
    }
}