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
        Schema::create('retencions', function (Blueprint $table) {
            $table->id();
            $table->string('secuencial_sig',9);
            $table->string('tipo_comprobante',2);
            $table->string('periodo_year',5);
            $table->string('periodo_month',25);
            $table->string('fecha_emision');
            $table->string('direccion')->nullable();
            $table->string('telefono',20)->nullable();
            $table->string('documento_obj_retencion',25)->nullable();
            $table->string('emision_documento_retencion',25)->nullable();
            $table->string('id_sustento_tributario');
            $table->string('tipo_doc_sustento');
            $table->date('fecha_emision_documento_retencion');
            $table->string('numero_comprobante_retencion',25);
            $table->string('nombre_retencion_adicional',45)->nullable();
            $table->string('descripcion_retencion_adicional',45)->nullable();
            $table->string('forma_pago',3);
            $table->string('tipo_pago',4);
            $table->foreignId('secuencial_id')->constrained('secuencials');
            $table->foreignId('vendedor_id')->constrained('vendedors');
            $table->timestamps();          
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retencions');
    }
};
