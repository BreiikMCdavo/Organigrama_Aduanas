<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('servidor_unidad', function (Blueprint $table) {
            $table->id();

            $table->foreignId('servidor_id')
                  ->constrained('servidores_publicos')
                  ->cascadeOnDelete();

            $table->foreignId('unidad_id')
                  ->constrained('unidades') // 🔥 AQUÍ ESTABA EL BUG
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servidor_unidad');
    }
};