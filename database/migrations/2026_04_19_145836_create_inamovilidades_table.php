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
        Schema::create('inamovilidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servidor_id')->constrained('servidores_publicos')->cascadeOnDelete();

            $table->text('descripcion')->nullable();
            $table->string('grado')->nullable();
            $table->string('tipo'); // discapacidad, familiar, etc
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inamovilidades');
    }
};
