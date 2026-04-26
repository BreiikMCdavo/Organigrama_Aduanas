<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {

            $table->string('unidad', 150)
                ->nullable()
                ->after('designacion')
                ->comment('Unidad del servidor público');

            $table->string('sub_unidad', 150)
                ->nullable()
                ->after('unidad')
                ->comment('Sub unidad del servidor público');

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
