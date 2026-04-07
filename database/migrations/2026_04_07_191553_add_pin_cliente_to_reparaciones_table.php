<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('reparaciones', function (Blueprint $table) {
            // Un string para que los PINs que empiecen con "0" (ej. 0492) no pierdan el cero
            $table->string('pin_cliente', 10)->nullable()->after('estado');
        });
    }

    public function down(): void {
        Schema::table('reparaciones', function (Blueprint $table) {
            $table->dropColumn('pin_cliente');
        });
    }
};