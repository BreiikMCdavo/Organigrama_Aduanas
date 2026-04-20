<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {

            $indexes = collect(DB::select("SHOW INDEX FROM servidores_publicos"))
                ->pluck('Key_name')->toArray();

            if (!in_array('idx_unidad', $indexes))     $table->index('unidad', 'idx_unidad');
            if (!in_array('idx_sub_unidad', $indexes)) $table->index('sub_unidad', 'idx_sub_unidad');
            if (!in_array('idx_numero_item', $indexes)) $table->index('numero_item', 'idx_numero_item');

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