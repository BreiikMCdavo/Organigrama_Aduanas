<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->date('designacion_inicio')->nullable()->after('designacion')->comment('Fecha inicio Designación');
            $table->date('designacion_fin')->nullable()->after('designacion_inicio')->comment('Fecha fin Designación');
            $table->date('interinato_inicio')->nullable()->after('designacion_fin')->comment('Fecha inicio Interinato');
            $table->date('interinato_fin')->nullable()->after('interinato_inicio')->comment('Fecha fin Interinato');
            $table->date('comision_inicio')->nullable()->after('interinato_fin')->comment('Fecha inicio Comisión');
            $table->date('comision_fin')->nullable()->after('comision_inicio')->comment('Fecha fin Comisión');
        });
    }

    public function down(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->dropColumn([
                'designacion_inicio', 'designacion_fin',
                'interinato_inicio', 'interinato_fin',
                'comision_inicio', 'comision_fin',
            ]);
        });
    }
};
