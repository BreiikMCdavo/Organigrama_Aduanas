<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->enum('tipo', ['item', 'consultoria'])->default('item');
        });
    }

    public function down(): void
    {
        Schema::table('servidores_publicos', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};
