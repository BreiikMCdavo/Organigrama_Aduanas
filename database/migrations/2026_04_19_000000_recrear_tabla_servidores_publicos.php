<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Eliminar la tabla vieja con todas sus columnas desordenadas
        Schema::dropIfExists('servidores_publicos');

        Schema::create('servidores_publicos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['item', 'consultoria'])->default('item')->comment('Tipo de servidor');

            // Datos comunes
            $table->string('nombre', 100)->comment('Nombres');
            $table->string('apellido_paterno', 100)->comment('Apellido paterno');
            $table->string('apellido_materno', 100)->nullable()->comment('Apellido materno');
            $table->string('fotografia')->nullable()->comment('Ruta de la fotografía');
            $table->date('fecha_ingreso_aduana')->nullable()->comment('Fecha de ingreso a la Aduana');
            $table->string('designacion', 50)->nullable()->comment('Designación: Designación / Interinato / Comisión');

            // Datos exclusivos ÍTEM
            $table->string('numero_item', 50)->nullable()->comment('Número de ítem');
            $table->string('cite_memorandum', 100)->nullable()->comment('CITE Memorandum');
            $table->string('cargo', 150)->nullable()->comment('Nombre del cargo (ítem)');
            $table->date('fecha_inicio_cargo')->nullable()->comment('Fecha de inicio del cargo');

            // Datos exclusivos CONSULTORÍA
            $table->string('contrato_numero', 100)->nullable()->comment('Número de contrato');
            $table->string('cargo_consultoria', 150)->nullable()->comment('Descripción del cargo (consultoría)');
            $table->date('fecha_inicio_contrato')->nullable()->comment('Fecha de inicio del contrato');
            $table->date('fecha_fin_contrato')->nullable()->comment('Fecha de fin del contrato');

            // Inamovilidad
            $table->text('asignacion_familiar_desc')->nullable()->comment('Descripción asignación familiar');
            $table->string('asignacion_familiar_grado', 10)->nullable()->comment('Grado asignación familiar');
            $table->text('casos_especiales_desc')->nullable()->comment('Descripción casos especiales');
            $table->string('casos_especiales_grado', 10)->nullable()->comment('Grado casos especiales');
            $table->text('discapacidad_desc')->nullable()->comment('Descripción discapacidad Ley 223');
            $table->string('discapacidad_grado', 10)->nullable()->comment('Grado discapacidad');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servidores_publicos');
    }
};
