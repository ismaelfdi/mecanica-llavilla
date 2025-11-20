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
        Schema::create('ordenes_trabajo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnUpdate()->restrictOnDelete();

            // Estados
            $table->string('estado', 30)->default('abierta'); // abierta|en_proceso|completada|cancelada
            $table->string('estado_pago', 30)->default('no_pagada'); // no_pagada|parcial|pagada

            // Fechas de ciclo de vida
            $table->timestamp('abierta_en')->nullable()->index();
            $table->timestamp('cerrada_en')->nullable()->index();

            // Totales (desnormalizados para rendimiento y reportes)
            $table->decimal('total_mano_obra', 12, 2)->default(0);
            $table->decimal('total_repuestos', 12, 2)->default(0);
            $table->decimal('total_descuento', 12, 2)->default(0);
            $table->decimal('total_impuestos', 12, 2)->default(0);
            $table->decimal('total_general', 12, 2)->default(0);

            // Impuestos y divisa
            $table->string('moneda', 3)->default('EUR');
            $table->decimal('tasa_impuesto_default', 5, 2)->nullable();

            // Enlace a facturación futura
            $table->unsignedBigInteger('factura_id')->nullable()->index();

            $table->text('queja_cliente')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['vehiculo_id', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_trabajo');
    }
};
