<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Agregamos los precios semestral y anual a los planes
        Schema::table('planes', function (Blueprint $table) {
            $table->decimal('precio_semestral', 10, 2)->after('precio_mensual')->default(0);
            $table->decimal('precio_anual', 10, 2)->after('precio_semestral')->default(0);
        });

        // 2. Agregamos el token y el estado a los talleres
        Schema::table('talleres', function (Blueprint $table) {
            $table->string('token_licencia')->unique()->nullable()->after('rfc_tax_id');
            $table->enum('estado_licencia', ['prueba', 'activa', 'vencida', 'cancelada'])->default('prueba')->after('fecha_vencimiento_licencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planes', function (Blueprint $table) {
            $table->dropColumn(['precio_semestral', 'precio_anual']);
        });

        Schema::table('talleres', function (Blueprint $table) {
            $table->dropColumn(['token_licencia', 'estado_licencia']);
        });
    }
};