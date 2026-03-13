<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reparaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taller_id')->constrained('talleres');
            $table->foreignId('equipo_id')->constrained('equipos');
            $table->foreignId('tecnico_id')->nullable()->constrained('users'); 
            
            $table->string('falla_reportada');
            $table->text('diagnostico_tecnico')->nullable();
            $table->enum('estado', ['recibido', 'diagnosticado', 'espera_pieza', 'listo', 'entregado', 'cancelado'])->default('recibido');
            
            $table->decimal('mano_obra_costo', 10, 2)->default(0);
            $table->decimal('total_estimado', 10, 2)->default(0);
            
            $table->timestamp('fecha_promesa')->nullable();
            $table->timestamp('fecha_entrega_real')->nullable();
            $table->timestamps();
        });

        Schema::create('reparacion_insumos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reparacion_id')->constrained('reparaciones')->onDelete('cascade');
            $table->foreignId('inventario_id')->constrained('inventario'); 
            $table->integer('cantidad');
            $table->decimal('precio_cobrado_historico', 10, 2); 
            $table->timestamps();
        });

        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taller_id')->constrained('talleres');
            $table->foreignId('vendedor_id')->constrained('users');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes'); 
            $table->decimal('total', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia']);
            $table->timestamps();
        });

        Schema::create('venta_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->foreignId('inventario_id')->constrained('inventario');
            
            $table->integer('cantidad');
            $table->decimal('precio_unitario_historico', 10, 2); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta_detalles');
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('reparacion_insumos');
        Schema::dropIfExists('reparaciones');
    }
};