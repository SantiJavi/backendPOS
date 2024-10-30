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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('ruc')->unique();            
            $table->string('password');            
            $table->string('correo');                                    
            $table->string('logo')->nullable();
            $table->string('user')->nullable()->unique();
            //$table->string('cod_estab');
            //$table->string('punto_emision');
            $table->boolean('permite_transacciones');
            $table->boolean('estado');     
            $table->string('password_firma')->nullable();
            $table->boolean('estado_firma')->nullable()->dafault(1);
            $table->string('url_firma')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
