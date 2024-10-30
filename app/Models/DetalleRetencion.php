<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleRetencion extends Model
{
    use HasFactory;

    public function Retencion(){
        return $this->belongsTo(Retencion::class);
    }
    public function CodigosRetencion(){
        return $this->belongsTo(CodigosRetencion::class,'codigos_retenciones_id');
    }

}
