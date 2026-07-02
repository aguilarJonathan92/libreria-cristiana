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
        Schema::table('pago_financiaciones', function (Blueprint $table) {
            $table->string('metodo_pago')->default('efectivo')->after('fecha_pago');
        });
    }

    public function down(): void
    {
        Schema::table('pago_financiaciones', function (Blueprint $table) {
            $table->dropColumn('metodo_pago');
        });
    }
};
