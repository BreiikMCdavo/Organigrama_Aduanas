<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            // Eliminar columnas individuales que ya no se usan
            $table->dropColumn([
                'interinato_inicio',
                'interinato_fin',
                'comision_inicio',
                'comision_fin',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->date('interinato_inicio')->nullable();
            $table->date('interinato_fin')->nullable();
            $table->date('comision_inicio')->nullable();
            $table->date('comision_fin')->nullable();
        });
    }
};
