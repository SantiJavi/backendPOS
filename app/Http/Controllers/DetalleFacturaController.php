<?php

namespace App\Http\Controllers;

use App\Models\DetalleFactura;
use Illuminate\Http\Request;


class DetalleFacturaController extends Controller
{
    public function index()
    {
        return DetalleFactura::with(['Producto'])->get();
    }
    public function store(Request $request)
    {
        $detallesFacturas=new DetalleFactura();
        $detallesFacturas->cantidad=$request->cantidad;
        $detallesFacturas->descuento=$request->descuento;
        $detallesFacturas->impuesto=$request->impuesto;        
        $detallesFacturas->subtotal=$request->subtotal;        
        $detallesFacturas->factura_id=$request->factura_id;
        $detallesFacturas->producto_id=$request->producto_id;
        $detallesFacturas->save();
        return $detallesFacturas;
    }

    public function show($id)
    {            
        $detalleFactura=new DetalleFactura();
        $detallesFiltrados = $detalleFactura::with(['Producto','Factura'])->where('factura_id', $id)->get();
        return $detallesFiltrados;    
    }
   

    public function update(Request $request, DetalleFactura $detallesFacturas)
    {
        $detallesFacturas->cantidad=$request->cantidad;
        $detallesFacturas->descuento=$request->descuento;
        $detallesFacturas->impuesto=$request->impuesto;
        $detallesFacturas->subtotal=$request->subtotal;        
        $detallesFacturas->factura_id=$request->factura_id;
        $detallesFacturas->producto_id=$request->producto_id;
        $detallesFacturas->save();
        return $detallesFacturas;
    }

    public function destroy(DetalleFactura $detalleFactura)
    {
        //
    }
}
