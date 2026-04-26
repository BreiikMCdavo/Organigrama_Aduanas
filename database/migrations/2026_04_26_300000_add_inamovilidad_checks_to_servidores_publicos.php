<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->boolean('asignacion_familiar_check')->default(false)->after('asignacion_familiar_grado');
            $table->boolean('casos_especiales_check')->default(false)->after('casos_especiales_grado');
            $table->boolean('discapacidad_check')->default(false)->after('discapacidad_grado');
        });
    }

    public function down(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->dropColumn(['asignacion_familiar_check','casos_especiales_check','discapacidad_check']);
        });
    }
};
