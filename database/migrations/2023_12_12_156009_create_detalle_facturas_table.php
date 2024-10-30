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
        Schema::create('detalle_facturas', function (Blueprint $table) {
            $table->id();
            $table->decimal('cantidad',10,2)->default(0);
            $table->decimal('descuento',10,4)->nullable()->default(0);
            $table->string('impuesto')->nullable();                    
            $table->decimal('subtotal',10,4)->nullable()->default(0);            
            $table->foreignId('factura_id')->constrained('facturas');                
            $table->foreignId('producto_id')->constrained('productos');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_facturas');
    }
};
