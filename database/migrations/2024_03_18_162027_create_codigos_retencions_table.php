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
        Schema::create('codigos_retencions', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_retencion',20);
            $table->string('descripcion_retencion',255);
            $table->decimal('porcentaje_cod_retencion',10,4)->nullable();
            $table->string('tipo_cod_impuesto',25);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigos_retencions');
    }
};
