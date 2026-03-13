<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taller_id')->constrained('talleres')->onDelete('cascade');
            $table->string('nombre');
            $table->string('telefono')->nullable(); // Vital para WhatsApp
            $table->string('email')->nullable();
            $table->text('direccion')->nullable();
            $table->timestamps();
        });

        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taller_id')->constrained('talleres')->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->enum('tipo', ['celular', 'tablet', 'laptop', 'otro']);
            $table->string('marca');
            $table->string('modelo');
            $table->string('imei_serie')->nullable()->index(); 
            $table->string('contrasena_desbloqueo')->nullable();
            $table->text('detalles_fisicos')->nullable();
            $table->timestamps();
        });

        Schema::create('inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taller_id')->constrained('talleres')->onDelete('cascade');
            $table->string('codigo_barras')->nullable()->index();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('costo_compra', 10, 2); 
            $table->decimal('precio_venta', 10, 2); 
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(2); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario');
        Schema::dropIfExists('equipos');
        Schema::dropIfExists('clientes');
    }
};