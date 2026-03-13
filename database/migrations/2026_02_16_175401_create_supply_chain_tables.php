<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taller_id')->constrained('talleres')->onDelete('cascade');
            $table->string('nombre_empresa');
            $table->string('nombre_contacto')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('rfc_tax_id')->nullable();
            $table->timestamps();
        });

        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taller_id')->constrained('talleres')->onDelete('cascade');
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->foreignId('usuario_id')->constrained('users'); // Quién registró la compra
            
            $table->string('folio_factura_proveedor')->nullable(); // Ej: FAC-9988
            $table->date('fecha_compra');
            $table->decimal('total_pagado', 10, 2);
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('compra_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained('compras')->onDelete('cascade');
            $table->foreignId('inventario_id')->constrained('inventario');
            
            $table->integer('cantidad'); // Cuántas piezas entraron
            $table->decimal('costo_unitario', 10, 2); // A cuánto nos la vendieron esta vez
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compra_detalles');
        Schema::dropIfExists('compras');
        Schema::dropIfExists('proveedores');
    }
};