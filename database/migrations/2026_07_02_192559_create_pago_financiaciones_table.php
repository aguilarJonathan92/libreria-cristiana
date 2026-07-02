<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago_financiaciones', function (Blueprint $table) {
            $table->id();
            $table->decimal('monto_pagado', 10, 2);
            $table->decimal('saldo_anterior', 10, 2);
            $table->decimal('saldo_posterior', 10, 2);
            $table->dateTime('fecha_pago');
            $table->text('notas')->nullable();
            $table->foreignId('venta_id')->constrained('ventas')->restrictOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('caja_id')->constrained('cajas')->restrictOnDelete();
            $table->timestamps();
        });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_financiaciones');
    }
};
