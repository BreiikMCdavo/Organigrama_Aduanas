<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->string('discapacidad_tipo')->nullable()->after('discapacidad_grado')->comment('Tipo de discapacidad');
            $table->string('discapacidad_carnet')->nullable()->after('discapacidad_tipo')->comment('Número de carnet de discapacidad');
            $table->date('discapacidad_vence')->nullable()->after('discapacidad_carnet')->comment('Fecha de vencimiento del carnet');
        });
    }

    public function down(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->dropColumn(['discapacidad_tipo', 'discapacidad_carnet', 'discapacidad_vence']);
        });
    }
};
