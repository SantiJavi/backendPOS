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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();            
            $table->string('secuencial_sec',9);
            $table->boolean('ambiente')->default(false);
            $table->string('tipo_comprobante',2);
            $table->date('fecha_emision');
            $table->string('forma_pago',100);            
            $table->string('plazo',20)->nullable();
            $table->string('unidades_tiempo',45)->nullable();
            $table->string('detalle_factura')->nullable();
            $table->decimal('propina',10,4)->nullable()->default(0.00);
            $table->decimal('gastos_transportes',10,4)->nullable()->default(0.00);
            $table->decimal('subtotal_12',10,4);
            $table->decimal('subtotal_0',10,4)->nullable();
            $table->decimal('subtotal_no_objeto_iva',10,4)->nullable();
            $table->decimal('subtotal_sin_impuesto',10,4)->nullable();            
            $table->decimal('total_descuento',10,4)->nullable();            
            $table->decimal('total_grabado',10,4);
            $table->string('documento_modificaN',100)->nullable();
            $table->string('numero_documento_modificaN',50)->nullable();
            $table->string('motivoN',100)->nullable();
            $table->foreignId('cliente_id')->constrained('clientes');            
            $table->foreignId('secuencial_id')->constrained('secuencials');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
