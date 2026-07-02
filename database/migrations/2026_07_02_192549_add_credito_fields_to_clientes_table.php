<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->decimal('saldo_actual', 10, 2)->default(0)->after('email');
            $table->decimal('limite_credito', 10, 2)->default(0)->after('saldo_actual');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['saldo_actual', 'limite_credito']);
        });
    }
};
