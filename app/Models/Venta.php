<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    
    public function Cliente(){
        return $this->belongsTo(Cliente::class);
    }
    public function Secuencial(){
        return $this->belongsTo(Secuencial::class);
    }
    public function Cuenta(){
        return $this->belongsTo(Cuenta::class);
    }

}
