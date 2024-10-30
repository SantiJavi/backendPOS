<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\SecuencialController;
use App\Utils\Utils;
use Illuminate\Http\Request;


class VentaController extends Controller
{
    public function index()
    {
        return Venta::all();
    }

    public function store(Request $request)
    {
        $venta = new Venta();
        $venta->fecha_emision = $request->fecha_emision;
        $venta->tipo_pago = $request->tipo_pago;
        $venta->secuencial_sec = $request->secuencial_sec;
        $venta->total_grabado = $request->total_grabado;
        $venta->fecha_pago = $request->fecha_pago;
        $venta->secuencial_id = $request->secuencial_id;
        $venta->cliente_id = $request->cliente_id;                
        $venta->save();        
        $idVenta = $venta->id;
        //creacion del detalle de venta
        $detalleVenta = new DetalleVentaController();
        $detalleVenta->store($request,$idVenta);
        //actualizacion de secuencial        
        $valorSecuencialSiguiente = Utils::generarSecuencial($request->secuencial_sec);                
        $controlador = new SecuencialController();        
        $secuencialModificar=$controlador->findByValueNota($request->secuencial_sec);          
        $secuencialModificar->sec_sig_not_cred=$valorSecuencialSiguiente;
        $secuencialModificar->save();                
        return "Venta Guardada exitosamente";
    }

    public function show(String $idVenta)
    {        
        return Venta::with(['Cliente'])->where('id',$idVenta)->get();
    }
    public function update(Request $request, Venta $venta)
    {  
        try
        {        
            $venta->fecha_emision = $request->fecha_emision;        
            $venta->tipo_pago = $request->tipo_pago;
            $venta->secuencial_sec = $request->secuencial_sec;
            $venta->total_grabado = $request->total_grabado;
            if($request->tipo_pago == 'credito'){
                $venta->fecha_pago = null;
            }else{
                
                $venta->fecha_pago = $request->fecha_pago;
            }            
            $venta->secuencial_id = $request->secuencial_id;
            $venta->cliente_id = $request->cliente_id;
            $venta->cuenta_id = $request->cuenta_id;
            $venta->save();
            return response()->json([
                'message'=>'Dato Actualizado'
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'message'=> $e->getMessage()
            ],500);
        }
    }
    public function destroy(Venta $venta)
    {
        //
    } 
    public function ventasDiarias(Request $request){
        return Venta::with(['Cliente'])->where('fecha_emision',$request->fecha_emision)->get();        
    }
}
