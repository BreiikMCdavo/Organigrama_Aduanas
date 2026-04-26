<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {

            if (!Schema::hasColumn('servidores_publicos', 'unidad')) {
                $table->string('unidad', 150)
                    ->nullable()
                    ->after('designacion')
                    ->comment('Unidad del servidor público');
            }

            if (!Schema::hasColumn('servidores_publicos', 'sub_unidad')) {
                $table->string('sub_unidad', 150)
                    ->nullable()
                    ->after('unidad')
                    ->comment('Sub unidad del servidor público');
            }

        });
    }

    public function down(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {

            $table->dropColumn([
                'unidad',
                'sub_unidad'
            ]);

        });
    }
};
