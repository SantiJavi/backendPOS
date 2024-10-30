<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosEmisor extends Model
{
    use HasFactory;
    //de esta manera completo la creacion de las relaciones
    public function Usuarios(){
        return $this->belongsTo(Usuario::class);
    }
}
