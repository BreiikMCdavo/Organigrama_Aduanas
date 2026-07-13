<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('asignaciones')) {
            return;
        }

        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas')->cascadeOnDelete();
            $table->foreignId('plaza_item_id')->constrained('plazas_items')->cascadeOnDelete();
            $table->foreignId('servidor_publico_id')->nullable()
                ->constrained('servidores_publicos')->nullOnDelete();
            $table->enum('tipo', ['titular', 'consultoria', 'comision', 'interinato', 'designacion'])->default('titular');
            $table->enum('estado', ['activa', 'finalizada'])->default('activa');
            $table->boolean('es_titular')->default(true);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->index(['persona_id', 'estado'], 'idx_asignaciones_persona_estado');
            $table->index(['plaza_item_id', 'estado'], 'idx_asignaciones_plaza_estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};
