<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secuencial extends Model
{
    use HasFactory;
     //de esta manera completo la creacion de las relaciones
     public function DatosEmisores(){
        return $this->belongsTo(DatosEmisor::class);
    }
}
