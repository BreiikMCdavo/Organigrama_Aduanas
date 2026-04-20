<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->boolean('acefalia')->default(false)->after('sub_unidad')
                ->comment('true = ítem sin datos personales (acefalía)');
        });
    }

    public function down(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->dropColumn('acefalia');
        });
    }
};
