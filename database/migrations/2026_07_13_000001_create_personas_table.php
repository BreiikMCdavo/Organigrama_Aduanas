<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('personas')) {
            return;
        }

        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_funcionario', 50)->nullable()->index();
            $table->string('nombre', 100)->nullable();
            $table->string('apellido_paterno', 100)->nullable();
            $table->string('apellido_materno', 100)->nullable();
            $table->string('nombre_normalizado', 320)->nullable()->index();
            $table->string('fotografia')->nullable();
            $table->date('fecha_ingreso_aduana')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['apellido_paterno', 'apellido_materno', 'nombre'], 'idx_personas_nombre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
