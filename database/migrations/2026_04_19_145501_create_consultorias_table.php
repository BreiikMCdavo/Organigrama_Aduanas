<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consultorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servidor_id')->constrained('servidores_publicos')->cascadeOnDelete();

            $table->string('contrato_numero')->nullable();
            $table->string('cargo')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultorias');
    }
};
