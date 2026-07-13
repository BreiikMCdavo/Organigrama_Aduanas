<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('servidores_publicos', 'persona_id')) {
            Schema::table('servidores_publicos', function (Blueprint $table) {
                $table->foreignId('persona_id')->nullable()->after('id')
                    ->constrained('personas')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('servidores_publicos', 'plaza_item_id')) {
            Schema::table('servidores_publicos', function (Blueprint $table) {
                $table->foreignId('plaza_item_id')->nullable()->after('persona_id')
                    ->constrained('plazas_items')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('servidores_publicos', 'asignacion_id')) {
            Schema::table('servidores_publicos', function (Blueprint $table) {
                $table->foreignId('asignacion_id')->nullable()->after('plaza_item_id')
                    ->constrained('asignaciones')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            if (Schema::hasColumn('servidores_publicos', 'asignacion_id')) {
                $table->dropForeign(['asignacion_id']);
                $table->dropColumn('asignacion_id');
            }

            if (Schema::hasColumn('servidores_publicos', 'plaza_item_id')) {
                $table->dropForeign(['plaza_item_id']);
                $table->dropColumn('plaza_item_id');
            }

            if (Schema::hasColumn('servidores_publicos', 'persona_id')) {
                $table->dropForeign(['persona_id']);
                $table->dropColumn('persona_id');
            }
        });
    }
};
