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
        Schema::create('productos', function (Blueprint $table) {
            $table->id(); 
            $table->string('codigo_producto');
            $table->string('nombre_producto');
            $table->string('codigo_aux')->nullable();
            $table->decimal('precio_producto',10,4)->default(0);
            $table->boolean('impuesto_ice')->dafault(false);
            $table->string('impuesto_iva')->nullable();
            $table->decimal('descuento',10,4)->nullable();
            $table->foreignId('user_id')->constrained('usuarios');
            $table->unique(['id','codigo_producto','user_id']);
            $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
