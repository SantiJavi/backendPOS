<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DetalleVentaController extends Controller
{

    public function index()
    {
        return DetalleVenta::with(['Venta','Producto'])->get();
    }


    public function store(Request $request,$idVenta)
    {                
        foreach($request->carrito as $item){
            $detalleVenta = new DetalleVenta();
            $detalleVenta->cantidad = $item['cantidad'];
            $detalleVenta->impuesto=$item['producto']['impuesto_iva'];   
            $detalleVenta->subtotal=(float) $item['precio'] * (float) $item['cantidad'];
            $detalleVenta->venta_id=$idVenta;         
            $detalleVenta->producto_id=$item['producto']['id'];
            $detalleVenta->save();            
        }
    }

  
    public function show(String $idVenta)
    {
        return DetalleVenta::with('venta')->where('venta_id',$idVenta)->get(); 
    }    

   
    public function update(Request $request, DetalleVenta $detalleVenta)
    {
        //
    }

    public function destroy(DetalleVenta $detalleVenta)
    {
        //
    }

    public function consultaVentasByDate(Request $request)
    {                
        $formattedDate = Carbon::createFromFormat('Y-m-d', $request->fecha_emision)->format('Y-m-d');    
        return DetalleVenta::with(['Producto', 'Venta.Cliente'])
            ->whereHas('Venta', function ($query) use ($formattedDate) {
                $query->whereDate('fecha_emision', $formattedDate);
            })            
            ->get();                        
    }

    public function consultaCuenta(String $id){        
        $detalles = DB::select('SELECT v.fecha_emision,v.cuenta_id,d.cantidad,p.nombre_producto,d.subtotal  FROM ventas v,productos p,detalle_ventas d WHERE v.id = d.venta_id AND p.id = d.producto_id AND v.cuenta_id = ?', [$id]);
         return $detalles;        
    }
    public function consultaDetalleCuentaActual($idCliente){
        return DB::select('SELECT d.cantidad,d.subtotal,p.nombre_producto,v.fecha_emision FROM detalle_ventas d, productos p, ventas v WHERE venta_id IN (SELECT id FROM ventas WHERE fecha_pago IS NULL AND tipo_pago = "credito" ) AND d.producto_id = p.id AND d.venta_id = v.id and v.tipo_pago="credito" and v.cliente_id= ?',[$idCliente]);
    }

}
