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
        Schema::table('gastos_presupuesto_2025', function (Blueprint $table) {

            $table->foreignId('titulo_programa_id')
                ->nullable()
                ->constrained('titulos_programa')
                ->onDelete('set null')
                ->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gastos', function (Blueprint $table) {
            //
        });
    }
};
