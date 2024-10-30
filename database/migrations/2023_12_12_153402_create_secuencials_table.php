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
        Schema::create('secuencials', function (Blueprint $table) {
            $table->id();
            $table->string('direccion_sucursal',155);
            $table->string('punto_emision',45);
            $table->string('codigo_establecimiento',45);            
            $table->string('sec_ini_fact',25)->default("000000001");
            $table->string('sec_sig_fact',25)->default("000000001");
            $table->string('sec_ini_com_ret',25)->default("000000001");
            $table->string('sec_sig_com_ret',25)->default("000000001");
            $table->string('sec_ini_not_cred',25)->default("000000001");
            $table->string('sec_sig_not_cred',25)->default("000000001");
            $table->string('sec_ini_guia_rem',25)->default("000000001");
            $table->string('sec_sig_guia_rem',25)->default("000000001");
            $table->string('sec_ini_not_deb',25)->default("000000001");
            $table->string('sec_sig_not_deb',25)->default("000000001");
            $table->string('sec_ini_liq_comp',25)->default("000000001");
            $table->string('sec_sig_liq_comp',25)->default("000000001");
            $table->boolean('estado')->default(false);
            $table->foreignId('datos_emisores_id')->constrained('datos_emisors');
            //$table->unique(['id','punto_emision','codigo_establecimiento','datos_emisores_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secuencials');
    }
};
