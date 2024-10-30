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
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_pago');
            $table->decimal('total_cuenta',10,2);
            $table->decimal('total_pagado',10,2);
            $table->decimal('saldo',10,2);
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};
