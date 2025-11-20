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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('documento', 50)->nullable()->index(); // DNI/RUC/NIF
            $table->string('telefono', 50)->nullable()->index();
            $table->string('email', 150)->nullable()->index();
            $table->string('direccion', 255)->nullable();

            // Para facturación futura
            $table->string('ciudad', 100)->nullable();
            $table->string('provincia', 100)->nullable();
            $table->string('pais', 100)->nullable();
            $table->string('codigo_postal', 20)->nullable();

            $table->json('meta')->nullable(); // extras (contactos secundarios, preferencias)
            $table->timestamps();
            $table->softDeletes(); // Columna para "eliminación suave"

            $table->index(['nombre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
