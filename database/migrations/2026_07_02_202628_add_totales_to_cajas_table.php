<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cajas', function (Blueprint $table) {
            $table->decimal('total_ventas', 10, 2)->default(0)->after('monto_inicial');
            $table->decimal('total_cobros_financiacion', 10, 2)->default(0)->after('total_ventas');
        });
    }

    public function down(): void
    {
        Schema::table('cajas', function (Blueprint $table) {
            $table->dropColumn(['total_ventas', 'total_cobros_financiacion']);
        });
    }
};
