<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastos_historial', function (Blueprint $table) {
            $table->id();

            // 👇 IMPORTANTE: igual que tu tabla original (int SIN unsigned)
            $table->integer('gasto_id');

            $table->decimal('importe_anterior', 15, 2);
            $table->decimal('importe_nuevo', 15, 2);
            $table->decimal('diferencia', 15, 2);

            $table->string('usuario')->nullable();
            $table->timestamp('fecha_cambio')->useCurrent();

            $table->timestamps();

            $table->foreign('gasto_id')
                ->references('id')
                ->on('gastos_presupuesto_2025')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos_historial');
    }
};