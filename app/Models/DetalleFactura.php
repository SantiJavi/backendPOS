<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    use HasFactory;
     //de esta manera completo la creacion de las relaciones
     public function Producto(){
        return $this->belongsTo(Producto::class);
    }
    public function Factura(){
        return $this->belongsTo(Factura::class);
    }
}
