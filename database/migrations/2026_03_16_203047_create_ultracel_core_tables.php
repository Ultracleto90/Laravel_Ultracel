<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. CLIENTES
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->unsignedBigInteger('taller_id'); // El candado SaaS
            $table->string('nombre', 100);
            $table->string('apellidos', 100);
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamps();

            $table->foreign('taller_id')->references('id')->on('talleres')->onDelete('cascade');
        });

        // 2. EQUIPOS
        Schema::create('equipos', function (Blueprint $table) {
            $table->id('id_equipo');
            $table->unsignedBigInteger('id_cliente');
            $table->string('tipo_equipo', 50);
            $table->string('marca', 50);
            $table->string('modelo', 100);
            $table->string('imei_o_serie', 50)->nullable();
            $table->string('clave_acceso', 100)->nullable();
            $table->timestamps();

            $table->foreign('id_cliente')->references('id_cliente')->on('clientes')->onDelete('cascade');
        });

        // 3. INVENTARIO
        Schema::create('inventario', function (Blueprint $table) {
            $table->id('id_producto');
            $table->unsignedBigInteger('taller_id'); // El candado SaaS
            $table->string('sku', 50);
            $table->string('nombre_producto', 150);
            $table->text('descripcion')->nullable();
            $table->enum('tipo_producto', ['Refacción', 'Venta Directa'])->default('Refacción');
            $table->string('marca_compatible', 50)->nullable();
            $table->string('modelo_compatible', 100)->nullable();
            $table->integer('stock')->unsigned()->default(0);
            $table->decimal('precio_compra', 10, 2)->nullable();
            $table->decimal('precio_venta', 10, 2);
            $table->string('ubicacion_almacen', 100)->nullable();
            $table->timestamps();

            $table->foreign('taller_id')->references('id')->on('talleres')->onDelete('cascade');
        });

        // 4. REPARACIONES
        Schema::create('reparaciones', function (Blueprint $table) {
            $table->id('id_reparacion');
            $table->unsignedBigInteger('id_equipo');
            $table->unsignedBigInteger('id_tecnico_asignado')->nullable(); // Ahora apunta a users
            $table->unsignedBigInteger('taller_id'); // El candado SaaS
            $table->dateTime('fecha_recepcion')->useCurrent();
            $table->text('problema_reportado');
            $table->text('diagnostico_tecnico')->nullable();
            $table->decimal('presupuesto', 10, 2)->nullable();
            $table->enum('estado', ['Recibido','En Diagnóstico','Esperando Aprobación','En Reparación','Reparado','No Reparado','Entregado'])->default('Recibido');
            $table->date('fecha_entrega_estimada')->nullable();
            $table->dateTime('fecha_entrega_real')->nullable();
            $table->timestamps();
            
            $table->foreign('id_equipo')->references('id_equipo')->on('equipos')->onDelete('cascade');
            $table->foreign('id_tecnico_asignado')->references('id')->on('users')->onDelete('set null');
            $table->foreign('taller_id')->references('id')->on('talleres')->onDelete('cascade');
        });

        // 5. REPARACIÓN_PIEZAS
        Schema::create('reparacion_piezas', function (Blueprint $table) {
            $table->id('id_reparacion_pieza');
            $table->unsignedBigInteger('id_reparacion');
            $table->unsignedBigInteger('id_producto');
            $table->integer('cantidad_usada')->default(1);
            $table->decimal('precio_en_reparacion', 10, 2);

            $table->foreign('id_reparacion')->references('id_reparacion')->on('reparaciones')->onDelete('cascade');
            $table->foreign('id_producto')->references('id_producto')->on('inventario')->onDelete('cascade');
        });

        // 6. SOLICITUDES_MATERIAL
        Schema::create('solicitudes_material', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->unsignedBigInteger('id_tecnico_solicitante'); // Ahora apunta a users
            $table->unsignedBigInteger('taller_id'); // El candado SaaS
            $table->string('nombre_producto', 255);
            $table->text('descripcion')->nullable();
            $table->integer('cantidad_solicitada')->unsigned()->default(1);
            $table->enum('estado_solicitud', ['Pendiente','Aprobada','Comprado','Rechazada'])->default('Pendiente');
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->text('notas_admin')->nullable();

            $table->foreign('id_tecnico_solicitante')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('taller_id')->references('id')->on('talleres')->onDelete('cascade');
        });

        // 7. VENTAS
        Schema::create('ventas', function (Blueprint $table) {
            $table->id('id_venta');
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->unsignedBigInteger('id_vendedor'); // Ahora apunta a users
            $table->unsignedBigInteger('taller_id'); // El candado SaaS
            $table->timestamp('fecha_venta')->useCurrent();
            $table->decimal('monto_total', 10, 2);
            $table->string('metodo_pago', 50)->nullable();

            $table->foreign('id_cliente')->references('id_cliente')->on('clientes')->onDelete('set null');
            $table->foreign('id_vendedor')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('taller_id')->references('id')->on('talleres')->onDelete('cascade');
        });

        // 8. VENTA_DETALLES
        Schema::create('venta_detalles', function (Blueprint $table) {
            $table->id('id_detalle');
            $table->unsignedBigInteger('id_venta');
            $table->unsignedBigInteger('id_producto')->nullable();
            $table->unsignedBigInteger('id_reparacion')->nullable();
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->string('descripcion_linea', 255);

            $table->foreign('id_venta')->references('id_venta')->on('ventas')->onDelete('cascade');
            $table->foreign('id_producto')->references('id_producto')->on('inventario')->onDelete('set null');
            $table->foreign('id_reparacion')->references('id_reparacion')->on('reparaciones')->onDelete('set null');
        });
    }

    public function down(): void
    {
        // El orden inverso es vital para no romper las llaves foráneas al revertir
        Schema::dropIfExists('venta_detalles');
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('solicitudes_material');
        Schema::dropIfExists('reparacion_piezas');
        Schema::dropIfExists('reparaciones');
        Schema::dropIfExists('inventario');
        Schema::dropIfExists('equipos');
        Schema::dropIfExists('clientes');
    }
};