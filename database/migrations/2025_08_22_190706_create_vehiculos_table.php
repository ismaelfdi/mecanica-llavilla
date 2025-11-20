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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnUpdate()->restrictOnDelete();

            $table->string('matricula', 20)->unique();
            $table->string('marca', 100)->nullable();
            $table->string('modelo', 120)->nullable();
            $table->string('version', 120)->nullable();
            $table->string('numero_bastidor', 32)->nullable()->unique();
            $table->unsignedSmallInteger('anio')->nullable(); // Renombrado a 'anio'
            $table->string('color', 50)->nullable();
            $table->unsignedInteger('kilometraje')->nullable();
            $table->string('tipo_combustible', 30)->nullable();
            $table->string('transmision', 30)->nullable();
            $table->json('meta')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['cliente_id']);
            $table->index(['marca', 'modelo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
