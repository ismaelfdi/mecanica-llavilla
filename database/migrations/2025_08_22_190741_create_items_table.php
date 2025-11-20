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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 20)->comment('labor|part');
            $table->string('codigo', 60)->nullable()->comment('SKU/ref pieza o código interno');
            $table->string('descripcion', 255);
            $table->decimal('precio_unitario', 12, 2)->default(0);
            $table->decimal('tasa_impuesto', 5, 2)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
