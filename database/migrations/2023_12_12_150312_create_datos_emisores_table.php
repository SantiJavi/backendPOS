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
        Schema::create('datos_emisors', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_comercial');
            $table->string('razon_social');
            $table->string('direccion');
            $table->string('email');
            $table->boolean('lleva_contabilidad')->default(false);
            $table->boolean('ambiente')->default(false);
            $table->boolean('contribuyente_retencion')->default(false);
            $table->boolean('agente_retencion')->default(false);
            $table->boolean('activar_regimen')->default(true);    
            $table->foreignId('usuarios_id')->constrained('usuarios');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_emisors');
    }
};
