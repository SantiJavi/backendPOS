<?php

namespace App\Http\Controllers;

use App\Models\DetalleRetencion;
use Illuminate\Http\Request;

class DetalleRetencionController extends Controller
{
    public function index()
    {
        //return DetalleRetencion::all();
        return DetalleRetencion::with(['CodigosRetencion'])->get();
    }
    public function store(Request $request)
    {
        $detalleRetencion = new DetalleRetencion();
        $detalleRetencion->tipo_impuesto_retencion = $request->tipo_impuesto_retencion;
        $detalleRetencion->base_imponible_retencion = $request->base_imponible_retencion;
        $detalleRetencion->valor_retencion = $request->valor_retencion;
        $detalleRetencion->retenciones_id = $request->retenciones_id;
        $detalleRetencion->codigos_retenciones_id = $request->codigos_retenciones_id;
        $detalleRetencion->save();
        return $detalleRetencion;

    }
 
    public function show($id)
    {                    
        $detalle_retencion = DetalleRetencion::with(['CodigosRetencion'])->where('retenciones_id',$id)->get();                
        return $detalle_retencion;        
    }

    public function update(Request $request, DetalleRetencion $detalleRetencion)
    {
        //
    }

    public function destroy(DetalleRetencion $detalleRetencion)
    {
        //
    }
}
