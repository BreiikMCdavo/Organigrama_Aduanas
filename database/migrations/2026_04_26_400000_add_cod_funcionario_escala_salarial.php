<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->string('cod_funcionario')->nullable()->after('numero_item')->comment('Código de funcionario');
            $table->string('escala_salarial')->nullable()->after('cod_funcionario')->comment('Escala salarial');
        });
    }

    public function down(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->dropColumn(['cod_funcionario', 'escala_salarial']);
        });
    }
};
