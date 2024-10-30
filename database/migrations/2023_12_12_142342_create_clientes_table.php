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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_identificador',55);
            $table->string('numero_documento',25);
            $table->string('nombre');
            $table->string('correo',100)->nullable();
            $table->string('telefono',45)->nullable();
            $table->string('direccion')->nullable();
            $table->foreignId('user_id')->constrained('usuarios');
            $table->unique(['id','numero_documento', 'user_id']);
            $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
