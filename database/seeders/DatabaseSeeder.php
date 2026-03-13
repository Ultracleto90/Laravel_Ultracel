<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $planId = DB::table('planes')->insertGetId([
            'nombre' => 'Plan Emprendedor',
            'precio_mensual' => 299.00,
            'max_usuarios' => 3,
            'max_sucursales' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tallerId = DB::table('talleres')->insertGetId([
            'plan_id' => $planId,
            'nombre_negocio' => 'Ultracel Demo Lab',
            'rfc_tax_id' => 'XAXX010101000',
            'fecha_vencimiento_licencia' => now()->addYear(),
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'taller_id' => $tallerId,
            'name' => 'Admin Ultracel',
            'email' => 'admin@ultracel.com', //  USUARIO
            'password' => Hash::make('password123'), //  CONTRASEÑA
            'rol' => 'admin',
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $clienteId = DB::table('clientes')->insertGetId([
            'taller_id' => $tallerId,
            'nombre' => 'Juan Pérez (Cliente Prueba)',
            'telefono' => '555-123-4567',
            'email' => 'cliente@test.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('inventario')->insert([
            'taller_id' => $tallerId,
            'codigo_barras' => 'LCD-IPH-11',
            'nombre' => 'Pantalla iPhone 11 Original',
            'costo_compra' => 800.00,
            'precio_venta' => 1500.00,
            'stock_actual' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $this->command->info('¡Base de datos inicializada con Taller Demo y Usuario Admin!');
    }
}