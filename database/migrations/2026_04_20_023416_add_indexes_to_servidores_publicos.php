<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {

            $table->index('unidad', 'idx_unidad');
            $table->index('sub_unidad', 'idx_sub_unidad');
            $table->index('numero_item', 'idx_numero_item');

        });
    }

    public function down(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {

            $table->dropIndex('idx_unidad');
            $table->dropIndex('idx_sub_unidad');
            $table->dropIndex('idx_numero_item');

        });
    }

};