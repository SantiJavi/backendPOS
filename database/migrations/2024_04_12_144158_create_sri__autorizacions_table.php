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
        Schema::create('sri__autorizacions', function (Blueprint $table) {
            $table->id();
            $table->string('num_autorizacion_sri');
            $table->string('clave_acceso_sri');
            $table->string('estado')->nullable();
            $table->string('fecha_autorizacion');                                    
            $table->foreignId('factura_id')->nullable()->references('id')->on('facturas');
            $table->foreignId('retencion_id')->nullable()->references('id')->on('retencions');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('sri__autorizacions');
    }
};
