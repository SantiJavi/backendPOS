<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\DetalleFactura;
use App\Models\Secuencial;
use App\Models\Sri_Autorizacion;

use App\Http\Controllers\XmlController;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Utils\Utils;

class FacturaController extends Controller
{
    public function index()
    {
        return Factura::with(['Cliente','Secuencial'=>function($query){
            $query->with(['DatosEmisores'=>function($queryDatos){
                $queryDatos->with('Usuarios');
            }]);
        }])->get();        
    }

    public function store(Request $request)
    {
        $factura=new Factura();        
        $factura->secuencial_sec=$request->secuencial_sec;
        $factura->ambiente=$request->adicional_factura['ambiente'];
        $factura->tipo_comprobante=$request->adicional_factura['tipo_comprobante'];
        $factura->fecha_emision=$request->adicional_factura['fecha_emision'];
        $factura->forma_pago=$request->adicional_factura['forma_pago'];
        $factura->plazo=$request->adicional_factura['plazo'];        
        $factura->unidades_tiempo=$request->adicional_factura['unidades_tiempo'];
        $factura->detalle_factura=$request->adicional_factura['detalle_factura']==null ||$request->adicional_factura['detalle_factura']=="" ? "-" :Utils::replaceSpecialCharacters($request->adicional_factura['detalle_factura']);
        //$factura->propina=$request->propina;                
        //$factura->gastos_transportes=$request->gastos_transportes;
        $factura->subtotal_12=self::decimales($request->subtotal_12,2);
        $factura->subtotal_0=self::decimales($request->subtotal_0,2);
        $factura->subtotal_no_objeto_iva=self::decimales($request->subtotal_no_objeto_iva,2);
        $factura->subtotal_sin_impuesto=self::decimales($request->subtotal_sin_impuesto,2);
        $factura->total_descuento=self::decimales($request->total_descuento,2);
        $factura->total_grabado=self::decimales($request->total_grabado,2);
        $factura->cliente_id=$request->adicional_factura['cliente_id'];        
        $factura->secuencial_id=$request->adicional_factura['secuencial_id'];        
        $factura->save();
        $idFactura=$factura->id;
        $response="";
        //registro del detalle de la compra
        if(isset($request->carrito)){
            if(!empty($request->carrito)){
                foreach($request->carrito as $item){                    
                    $detallesFacturas=new DetalleFactura();
                    $detallesFacturas->cantidad=$item['cantidad'];
                    $detallesFacturas->descuento=$item['producto']['descuento'];
                    $detallesFacturas->impuesto=$item['producto']['impuesto_iva'];        
                    $detallesFacturas->subtotal=(float) $item['precio'] * (float) $item['cantidad'];        
                    $detallesFacturas->factura_id=$idFactura;
                    $detallesFacturas->producto_id=$item['producto']['id'];
                    $detallesFacturas->save();
                }
            }
        }                        
        //Generacion XML
        $xmlFactura=new XmlController();
        $respo=$xmlFactura->generarFacturaXml($factura);        
        return $respo;
    }

    
    public function show(Factura $factura)
    {            
    $factura->cliente=$factura->Cliente;
    $factura->secuencial=$factura->secuencial;                           
    $factura->secuencial->datos_emisores=$factura->secuencial->DatosEmisores;
    $factura->secuencial->datos_emisores->usuarios=$factura->secuencial->DatosEmisores->Usuarios;    
    return $factura;        
    }
    
    public function showById($id){
        $factura=new Factura();
        $factura = $factura::with(['Cliente','Secuencial'=>function($query){
                $query->with(['DatosEmisores']);
            }])->where('id', $id)->get()->first();
        return $factura;    
    }

    public function getIdFacturaBySecuencial(String $numSecuencial){
        $factura = Factura::where('secuencial_sec',$numSecuencial)->get()->first();
        return $factura->id;
    }

    public function facturaPdf(Factura $factura)
    {            
    $sriAutorizaciones=new SriAutorizacionController();
    $detallesFactura=new DetalleFacturaController();
    $factura=$this->show($factura);
    $autorizaciones=$sriAutorizaciones->show($factura->id);
    $detalles=$detallesFactura->show($factura->id);    
    $fPago=$this->formaPago($factura->forma_pago);
    $tiempoPago=$this->plazoPago($factura->unidades_tiempo);
    $pdf = PDF::loadView('reports.factura',["factura"=>$factura,"autorizacion"=>$autorizaciones,"detalles"=>$detalles,"formaPago"=>$fPago,"tiempoPago"=>$tiempoPago]);
    return $pdf->stream();
    }
    public function update(Request $request, Factura $factura)
    {                
        $factura->secuencial=$request->secuencial;        
        $factura->ambiente=$request->ambiente;
        $factura->tipo_comprobante=$request->tipo_comprobante;
        $factura->fecha_emision=$request->fecha_emision;
        $factura->forma_pago=$request->forma_pago;
        $factura->tipo_factura=$request->tipo_factura;
        $factura->plazo=$request->plazo;
        $factura->unidades_tiempo=$request->unidades_tiempo;
        $factura->cliente_id=$request->cliente_id;
        $factura->datos_emisor_id=$request->datos_emisor_id;
        $factura->secuencial_id=$request->secuencial_id;
        $factura->save();
        return $factura;
    }

    public function destroy(Factura $factura)
    {
        $factura->delete();
        return response()->json(['message' => 'Factura eliminado correctamente'], 200);
    }

    public function formaPago(String $formaPago){
        $response="";
        if($formaPago=='c_u_s_f'){
            $response="OTROS CON UTILIZACION DEL SISTEMA FINANCIERO";
        }else if($formaPago=='s_u_s_f'){
            $response="SIN UTILIZACION DEL SISTEMA FINANCIERO";
        }else if($formaPago=='comp_deudas'){
            $response="COMPENSACION DE DEUDAS";
        }else if($formaPago=='t_debito'){
            $response="TARJETA DE DEBITO";
        }else if($formaPago=='dinero_electronico'){
            $response="DINERO ELECTRONICO";
        }else if($formaPago=='t_credito'){
            $response="TARJETA DE CREDITO";
        }else if($formaPago=='t_prepago'){
            $response="TARJETA PREPAGO";
        }else{
            $response="ENDOSO DE TITULOS";
        }
        return $response;
    }
    public function plazoPago(String $plazoPago){
        $response="";
        if($plazoPago=='days'){
            $response="días";
        }else if($plazoPago=='months'){
            $response="meses";
        }else {
            $response="años";
        }
        return $response;
    }
    public static function decimales($valorOriginal,$decimal){        
        return number_format($valorOriginal, $decimal, '.', '');
    }
    
    public function crearTablaTemporal(Request $request){            
        
        DB::statement('CREATE TEMPORARY TABLE temp_table_documentos
        (id INT AUTO_INCREMENT PRIMARY KEY,
        cantidad DECIMAL(10,2),
        descuento DECIMAL(10,2),
        impuesto VARCHAR(255),
        subtotal DECIMAL(10,4),
        fecha_emision DATE,
        forma_pago VARCHAR(100),
        subtotal_12 DECIMAL(10,4),
        subtotal_0 DECIMAL(10,4),
        subtotal_no_objeto_iva DECIMAL(10,4),
        subtotal_sin_impuesto DECIMAL(10,4),
        total_descuento DECIMAL(10,4),
        total_grabado DECIMAL(10,4)
        )');
        
        $fecha_emision = $request->adicional_factura['fecha_emision'];
        $formaPago = $request->adicional_factura['forma_pago'];
        $subtotal0= $request->subtotal_0;
        $subtotal12= $request->subtotal_12;
        $subtotalNoObjeto = $request->subtotal_no_objeto_iva;
        $subtotalSImpuesto= $request->subtotal_sin_impuesto;
        $totalDescuento = $request->total_descuento;
        $totalGrabado= $request->total_grabado;

        foreach($request->carrito as $detalle){        
            DB::table('temp_table_documentos')->insert([                
                ['cantidad' => $detalle['cantidad'],
                'descuento' => $detalle['producto']['descuento'],
                'fecha_emision' => $fecha_emision,
                'forma_pago'=>$formaPago,                
                'impuesto' => $detalle['impuesto'],
                'subtotal' => $detalle['precio'] * $detalle['cantidad'],
                'subtotal_0' =>$subtotal0,
                'subtotal_12' =>$subtotal12,
                'subtotal_no_objeto_iva'=>$subtotalNoObjeto,
                'subtotal_sin_impuesto'=>$subtotalSImpuesto,
                'total_descuento'=>$totalDescuento,
                'total_grabado'=>$totalGrabado            
                ]
                // Puedes insertar más registros según sea necesario
            ]);
        }
        $datosTabla = DB::table('temp_table_documentos')->get();
        $pdf = PDF::loadView('reports.previsualizacionFactura',["prevFactura"=>$datosTabla]);        
        return response()->json(['message' => 'Tabla temporal creada exitosamente', 'data' => $datosTabla], 200);
    }
}
