<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retencion extends Model
{
    use HasFactory;
    public function Secuencial(){
        return $this->belongsTo(Secuencial::class);
    }
    public function Vendedor(){
        return $this->belongsTo(Vendedor::class);
    }
    public function SriAutorzacion(){
        return $this->hasOne(Sri_Autorizacion::class);
    }

}
