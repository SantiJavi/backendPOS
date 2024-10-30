<?php

namespace App\Http\Controllers;

use App\Models\Retencion;
use App\Models\DetalleRetencion;
use App\Http\Controllers\CodigosRetencionController;
use App\Http\Controllers\DetalleRetencionController;
use App\Http\Controllers\SriAutorizacionController;

use App\Models\Secuencial;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Utils\Utils;

class RetencionController extends Controller
{

    public function index()
    {
        return Retencion::with(['Vendedor','Secuencial'=>function($query){
            $query->with(['DatosEmisores'=>function($queryDatos){
                $queryDatos->with('Usuarios');
            }]);
        }])->get();        
    }

    public function store(Request $request)
    {
        $retencion = new Retencion();
        $retencion->secuencial_sig = $request['secuencial_sig'];
        $retencion->tipo_comprobante = $request->model['tipo_comprobante'];
        $retencion->periodo_year = $request->model['year_establecimiento'];
        $retencion->periodo_month = $request->model['month_establecimiento'];
        $retencion->fecha_emision = $request->model['fecha_emision'];
        $retencion->secuencial_id = $request->model['secuencial_id'];
        $retencion->vendedor_id = $request->sujeto_retenido['vendedor_id'];
        $retencion->telefono = $request->sujeto_retenido['telefono'];
        $retencion->direccion = Utils::replaceSpecialCharacters($request->sujeto_retenido['direccion']);
        $retencion->documento_obj_retencion = $request->documento_retencion['comprobante'];
        $retencion->emision_documento_retencion = $request->documento_retencion['emision'];
        $retencion->fecha_emision_documento_retencion = $request->documento_retencion['fecha_emision_dor'];
        
        $retencion->id_sustento_tributario = $request->documento_retencion['idSustentoTributario'];
        $retencion->tipo_doc_sustento = $request->documento_retencion['tipoDocSustento'];
        $retencion->forma_pago=$request->documento_retencion['forma_pago'];
        $retencion->tipo_pago=$request->documento_retencion['tipo_pago'];

        $codEstablecimiento = $request->documento_retencion['codigo_establecimiento'];
        $puntoEmision = $request->documento_retencion['punto_emision'];
        $secuencial = $request->documento_retencion['secuencial'];
        $codFinal = $codEstablecimiento.$puntoEmision.$secuencial;        
        $retencion->numero_comprobante_retencion = $codFinal;

        $retencion->nombre_retencion_adicional = 'test';
        $retencion->descripcion_retencion_adicional = 'test';            
        $retencion->save();
        $idRetencion=$retencion->id;
        //registro de detalles de retenciones
        if(isset($request->acumulado_retenciones)){            
            if(!empty($request->acumulado_retenciones)){                
                foreach($request->acumulado_retenciones as $item){                                        
                    $detallesRetenciones = new DetalleRetencion();
                    $codigosController = new CodigosRetencionController();  
                    $detallesRetenciones->tipo_impuesto_retencion = $item['selectedImpuesto'];                
                    $detallesRetenciones->base_imponible_retencion=$item['valorBaseImponible'];
                    $detallesRetenciones->valor_retencion=$item['valorTotal'];
                    $detallesRetenciones->retenciones_id=$idRetencion;                                                      
                    $detallesRetenciones->codigos_retenciones_id=$codigosController->show($item['selectedCodigo']);                    
                    $detallesRetenciones->save();                    
                }
            }
        }
        //Generacion XML
        $xmlRetencion = new XmlRetencionController();
        $respo = $xmlRetencion->generarRetencionXml($retencion);
        return $respo;
    }

    public function show(Retencion $retencion)
    {            
        $retencion->vendedor = $retencion->vendedor; 
        $retencion->secuencial=$retencion->secuencial;        
        $retencion->secuencial->datos_emisores=$retencion->secuencial->DatosEmisores;
        $retencion->secuencial->datos_emisores->usuarios=$retencion->secuencial->DatosEmisores->Usuarios;        
        return $retencion;        
    }

    public function update(Request $request, Retencion $retencion)
    {
        //
    }

    public function destroy(Retencion $retencion)
    {
        //
    }
    

    public function getIdRetencionBySecuencial(String $secuencial){
    $retencion = Retencion::where('secuencial_sig',$secuencial)->get()->first();
    return $retencion->id;
    }

    public function showById($id){
        $retencion = new Retencion();
        $retencion = $retencion::with(['Vendedor','Secuencial'=>function($query){
            $query->with(['DatosEmisores']);
        }])->where('id',$id)->get()->first();
        return $retencion;
    }
    
    public function retencionPdf(Retencion $retencion){
        $sriAutorizaciones=new SriAutorizacionController();
        $detallesRetencion=new DetalleRetencionController();
        $retencion = $this->show($retencion);
        $autorizaciones=$sriAutorizaciones->showRetenciones($retencion->id);        
        $detalles=$detallesRetencion->show($retencion->id);
        //periodoFiscal
        //$periodo=XmlRetencionController::getMesByMes($retencion->periodo_month);                 
        $periodo=$retencion->periodo_month.'/'.$retencion->periodo_year;
        $totalRetenciones=0;
        foreach($detalles as $detalle){
            $totalRetenciones += $detalle->valor_retencion;
        }
        $pdf = PDF::loadView('reports.retencion',["retencion"=>$retencion,"autorizacion"=>$autorizaciones,"detalles"=>$detalles,"periodo"=>$periodo,"totalRetenciones"=>$totalRetenciones]);
        return $pdf->stream();
    }
}
