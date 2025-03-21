<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert( [
            'ruc'=>'1751568682001',
            'password'=>Hash::make('romulo1'),
            'correo'=>'santijavier0708@gmail.com',            
            'user'=>'1751568682001',
            'permite_transacciones'=>'1',
            'estado'=>'1',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
            ]);

        DB::table('users')->insert( [
            'ruc'=>'1751568682001',
            'password'=>Hash::make('romulo1'),
            'user'=>'1751568682001', 
            'correo'=>'santijavier0708@gmail.com',
            'fecha_registro'=>'2024-07-01',
            'fecha_expiracion'=>'9999-12-31',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
            ]);
    }
}
