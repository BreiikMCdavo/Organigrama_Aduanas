<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('movimientos_personal')) {
            return;
        }

        Schema::create('movimientos_personal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->nullable()->constrained('personas')->nullOnDelete();
            $table->foreignId('plaza_origen_id')->nullable()->constrained('plazas_items')->nullOnDelete();
            $table->foreignId('plaza_destino_id')->nullable()->constrained('plazas_items')->nullOnDelete();
            $table->foreignId('servidor_publico_id')->nullable()
                ->constrained('servidores_publicos')->nullOnDelete();
            $table->enum('tipo', ['comision', 'traslado', 'designacion', 'reemplazo', 'interinato', 'retorno'])->default('comision');
            $table->enum('estado', ['activo', 'finalizado'])->default('activo');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->text('detalle')->nullable();
            $table->timestamps();

            $table->index(['persona_id', 'estado'], 'idx_movimientos_persona_estado');
            $table->index(['tipo', 'estado'], 'idx_movimientos_tipo_estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_personal');
    }
};
