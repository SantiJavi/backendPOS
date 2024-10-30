<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sri_Autorizacion extends Model
{
    use HasFactory;
    public function Factura(){
        return $this->belongsTo(Factura::class);
    }
    public function Retencion(){
        return $this->belongsTo(Retencion::class);
    }
}
