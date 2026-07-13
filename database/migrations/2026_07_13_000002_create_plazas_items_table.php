<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('plazas_items')) {
            return;
        }

        Schema::create('plazas_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servidor_publico_id')->nullable()->unique()
                ->constrained('servidores_publicos')->nullOnDelete();
            $table->enum('tipo', ['item', 'consultoria'])->default('item');
            $table->string('codigo_plaza', 100)->nullable();
            $table->string('numero_item', 50)->nullable();
            $table->string('contrato_numero', 100)->nullable();
            $table->string('unidad', 150)->nullable()->index();
            $table->string('sub_unidad', 150)->nullable()->index();
            $table->string('cargo', 150)->nullable();
            $table->string('cargo_consultoria', 150)->nullable();
            $table->enum('estado', ['ocupada', 'acefalia', 'reservada', 'cubierta_temporal'])->default('ocupada');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();

            $table->index(['tipo', 'codigo_plaza'], 'idx_plazas_tipo_codigo');
            $table->index(['unidad', 'sub_unidad'], 'idx_plazas_area');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plazas_items');
    }
};
