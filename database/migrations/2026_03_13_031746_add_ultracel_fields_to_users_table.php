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
        Schema::table('users', function (Blueprint $table) {
            // Verificamos si falta la columna especialidad y la agregamos
            if (!Schema::hasColumn('users', 'especialidad')) {
                $table->string('especialidad')->nullable()->after('password');
            }
            
            // Verificamos si falta la columna permitido y la agregamos
            if (!Schema::hasColumn('users', 'permitido')) {
                $table->boolean('permitido')->default(1)->after('especialidad');
            }

            // Por si acaso también nos falta el rol o el taller
            if (!Schema::hasColumn('users', 'rol')) {
                $table->string('rol')->default('Vendedor')->after('name');
            }
            if (!Schema::hasColumn('users', 'taller_id')) {
                $table->unsignedBigInteger('taller_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
