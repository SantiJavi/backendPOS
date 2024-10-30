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
        Schema::create('detalle_retencions', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_impuesto_retencion',45);            
            $table->decimal('base_imponible_retencion',10,4);            
            $table->decimal('valor_retencion',10,4);
            $table->foreignId('retenciones_id')->constrained('retencions');
            $table->foreignId('codigos_retenciones_id')->constrained('codigos_retencions');
            $table->timestamps();    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_retencions');
    }
};
