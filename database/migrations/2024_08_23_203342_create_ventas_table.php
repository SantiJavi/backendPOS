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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_emision');
            $table->string('tipo_pago',9);
            $table->string('secuencial_sec',9);
            $table->decimal('total_grabado',10,2);
            $table->date('fecha_pago')->nullable();            
            $table->foreignId('secuencial_id')->constrained('secuencials');
            $table->foreignId('cliente_id')->constrained('clientes');       
            $table->foreignId('cuenta_id')->constrained('cuentas')->nullable();       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
