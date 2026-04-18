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
        Schema::table('servidores_publicos', function (Blueprint $table) {
            // Campos comunes
            $table->string('fotografia')->nullable()->after('tipo');
            
            // Campos para ITEM
            $table->string('cite_memorandum')->nullable();
            $table->string('designacion')->nullable();
            $table->date('fecha_ingreso_aduana')->nullable();
            $table->date('fecha_inicio_cargo')->nullable();
            
            // Campos para CONSULTORIA
            $table->string('contrato_numero')->nullable();
            $table->string('cargo_consultoria')->nullable();
            $table->date('fecha_inicio_contrato')->nullable();
            $table->date('fecha_fin_contrato')->nullable();
            
            // Campos de Inamovilidad
            $table->text('asignacion_familiar_desc')->nullable();
            $table->string('asignacion_familiar_grado')->nullable();
            $table->text('casos_especiales_desc')->nullable();
            $table->string('casos_especiales_grado')->nullable();
            $table->text('discapacidad_desc')->nullable();
            $table->string('discapacidad_grado')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->dropColumn([
                'fotografia', 'cite_memorandum', 'designacion', 'fecha_ingreso_aduana',
                'fecha_inicio_cargo', 'contrato_numero', 'cargo_consultoria',
                'fecha_inicio_contrato', 'fecha_fin_contrato', 'asignacion_familiar_desc',
                'asignacion_familiar_grado', 'casos_especiales_desc', 'casos_especiales_grado',
                'discapacidad_desc', 'discapacidad_grado'
            ]);
        });
    }
};
