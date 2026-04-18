<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_servidores_publicos_table.php
    public function up()
    {
        Schema::create('servidores_publicos', function (Blueprint $table) {
            $table->id();
            // Datos del Item
            $table->string('numero_item')->nullable();
            $table->string('cargo')->nullable();
            $table->string('nombre')->nullable();
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->date('fecha_ingreso_almacen')->nullable();
            $table->date('fecha_inicio_carga')->nullable();
            $table->string('inscripcion')->nullable();
            $table->string('m2')->nullable();
            $table->string('disponibilidad_ley_223')->nullable();

            // Unidad Administrativa
            $table->string('unidad_admin_numero_item')->nullable();
            $table->string('unidad_admin_cargo')->nullable();
            $table->string('area_administracion')->nullable();
            $table->string('unidad_admin_nombre')->nullable();
            $table->date('unidad_admin_fecha_ingreso_almacen')->nullable();
            $table->date('unidad_admin_fecha_ingreso')->nullable();

            // Unidad Jurídica
            $table->string('contrato')->nullable();
            $table->string('unidad_juridica_cargo')->nullable();
            $table->string('consultor_juridico')->nullable();
            $table->string('unidad_juridica_nombre')->nullable();
            $table->date('unidad_juridica_ingreso_almacen')->nullable();
            $table->date('unidad_juridica_fecha_ingreso')->nullable();
            $table->string('unidad_juridica_disponibilidad')->nullable();

            // Datos de Consultoría
            $table->string('correo')->nullable();
            $table->string('consultoria_nombre')->nullable();
            $table->string('consultoria_apellido_materno')->nullable();
            $table->date('consultoria_fecha_ingreso_almacen')->nullable();
            $table->date('consultoria_fecha_inicio_carga')->nullable();

            // Imagen (subir imágenes)
            $table->string('imagen_ruta')->nullable();

            $table->timestamps();
        });
    }
};
