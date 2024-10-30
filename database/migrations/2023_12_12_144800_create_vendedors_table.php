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
        Schema::create('vendedors', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_identificador',25);
            $table->string('numero_documento',25)->nullable();
            $table->string('razon_social');
            $table->string('celular',20)->nullable();
            $table->string('correo',100)->nullable();
            $table->string('codigo_vendedor',45);
            $table->string('empresa')->nullable();
            $table->foreignId('user_id')->constrained('usuarios');
            $table->unique(['id','codigo_vendedor','user_id']);
            $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendedors');
    }
};
