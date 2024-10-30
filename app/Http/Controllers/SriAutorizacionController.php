<?php

namespace App\Http\Controllers;

use App\Models\Sri_Autorizacion;
use Illuminate\Http\Request;

class SriAutorizacionController extends Controller
{

    public function index()
    {               
        return Sri_Autorizacion::with(['Factura'=>function($query){            
            $query->with(['Cliente','Secuencial'=>function($queryDatos){
                $queryDatos->with(['DatosEmisores'=>function($query){
                    $query->with(['Usuarios']);
                }]);                
            }]);
        }])->whereNotNull('factura_id')->get();
    }

    public function store(Request $request)
    {
        $autorizaciones=new Sri_Autorizacion();
        $autorizaciones->num_autorizacion_sri=$request->numAutorizacion;
        $autorizaciones->clave_acceso_sri=$request->claveAcceso;
        $autorizaciones->estado = $request->estado;
        $autorizaciones->fecha_autorizacion = $request->fecha_autorizacion;
        $autorizaciones->factura_id=$request->factura_id;
        $autorizaciones->retencion_id=$request->retencion_id;
        $autorizaciones->save();
        return $autorizaciones;
    }

    /*
    public function show(Sri_Autorizacion $sri_Autorizacion)
    {
        //$sri_autorizacion=new Sri_Autorizacion();
        //$autorizacionesFiltrados = $sri_autorizacion::with(['Factura'])->where('factura_id', $id)->get();
        return $sri_Autorizacion;    
    }
    */    
    public function show($id){
        $sri_autorizacion = new Sri_Autorizacion();
        $autorizacionesFiltrados = $sri_autorizacion::with(['Factura'])->where('factura_id', $id)->first();
        $autorizacionesFiltrados->factura->secuencial=$autorizacionesFiltrados->factura->Secuencial;
        return $autorizacionesFiltrados;
    }

    public function showRetenciones($id){
        $sri_autorizacion = new Sri_Autorizacion();
        $autorizacionesFiltrados = $sri_autorizacion::with(['Retencion'])->where('retencion_id', $id)->first();
        $autorizacionesFiltrados->retencion->secuencial=$autorizacionesFiltrados->retencion->Secuencial;        
        return $autorizacionesFiltrados;
    }

    public function showAllRetenciones(){
        return Sri_Autorizacion::with(['Retencion'=>function($query){            
            $query->with(['Vendedor','Secuencial'=>function($queryDatos){
                $queryDatos->with(['DatosEmisores'=>function($query){
                    $query->with(['Usuarios']);
                }]);                
            }]);
        }])->whereNotNull('retencion_id')->get();
    }        
    
    public function allDocuments()
    {               
        return Sri_Autorizacion::with(['Factura'=>function($query){            
            $query->with(['Cliente','Secuencial'=>function($queryDatos){
                $queryDatos->with(['DatosEmisores'=>function($query){
                    $query->with(['Usuarios']);
                }]);                
            }]);
        }])->whereNotNull('factura_id')->get();
    }

    public function showDocumentsPerUser(String $id){
        return Sri_Autorizacion::with(['Factura'=>function($query){            
            $query->with(['Cliente','Secuencial'=>function($queryDatos){
                $queryDatos->with(['DatosEmisores'=>function($query){
                    $query->with(['Usuarios']);
                }]);                
            }]);
        }])
        ->whereHas('Factura.Secuencial.DatosEmisores.Usuarios',function($queryUsuarios) use ($id){
            $queryUsuarios->where('id', $id);
        })
        ->whereNotNull('factura_id')->get();
    }

    public function showDocumentsRetentionPerUser(String $id){
        return Sri_Autorizacion::with(['Retencion'=>function($query){            
            $query->with(['Vendedor','Secuencial'=>function($queryDatos){
                $queryDatos->with(['DatosEmisores'=>function($query){
                    $query->with(['Usuarios']);
                }]);                
            }]);
        }])
        ->whereHas('Retencion.Secuencial.DatosEmisores.Usuarios',function($queryUsuarios) use ($id){
            $queryUsuarios->where('id', $id);
        })
        ->whereNotNull('retencion_id')->get();
    }


    public function update(Request $request, Sri_Autorizacion $sri_Autorizacion)
    {
        $sri_Autorizacion->num_autorizacion_sri = $request->num_autorizacion_sri;
        $sri_Autorizacion->clave_accesso_sri = $request->clave_accesso_sri;
        $sri_Autorizacion->estado = $request->estado;
        $sri_Autorizacion->fecha_autorizacion = $request->fecha_autorizacion;        
        $sri_Autorizacion->factura_id = $request->factura_id;
        $sri_Autorizacion->retencion_id = $request->retencion_id;                
        $sri_Autorizacion->save();
        return $sri_Autorizacion;        
    }

    public function actualizacionReenvio($id,$estado,$fecha,$tipoDocumento){
        if($tipoDocumento == "Factura"){
            $documento = $this->show($id);
        }else{
            $documento = $this->showRetenciones($id);
        }        
        $documento->estado = $estado;
        $documento->fecha_autorizacion = $fecha;
        $documento->save();
    }

    public function reenvioFacturaElectronica(){
        $tipoDocumento = "Factura";
        $autorizaciones = $this->allDocuments();
        foreach($autorizaciones as $autorizacion){
            if($autorizacion->estado == 'POR PROCESAR'){
                $numDocAutorizacion = $autorizacion->clave_acceso_sri;
                $ambiente = $autorizacion->factura->ambiente == 0 ? "1" : "2" ; //1 Pruebas, 2 Produccion
                $numFactura = $autorizacion->factura_id; 
                $this->envioDocumento($numDocAutorizacion,$ambiente,$numFactura,$tipoDocumento);
            }
        }
    }
    public function reenvioRetencionElectronica(){
        $tipoDocumento = "Retencion";
        $autorizaciones = $this->showAllRetenciones();
        foreach($autorizaciones as $autorizacion){
            if($autorizacion->estado == 'POR PROCESAR'){
                $numDocAutorizacion = $autorizacion->clave_acceso_sri;
                $ambiente = $autorizacion->retencion->secuencial->DatosEmisores->ambiente == 0 ? "1" : "2" ;                
                $numRetenciion = $autorizacion->retencion_id; 
                $this->envioDocumento($numDocAutorizacion,$ambiente,$numRetenciion,$tipoDocumento);
            }
        }
    }
    public function envioDocumento($numAutorizacion,$ambiente,$numFactura,$tipoDocumento){
        $respuestaAutorizacion = XmlController::responseSriAutorizaicon($numAutorizacion,$ambiente);            
        $estado=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;  
        $comprobante=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante;
        $numeroAutorizacionSri=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion ?? " ";
        $ambienteAutorizado=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
        $fechaAutorizacion=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;        
        
        if($estado=="POR PROCESAR"){       
            return $estado;                                     
        }else{                      
            XmlController::guardarXML($comprobante,$numeroAutorizacionSri,$estado,$fechaAutorizacion,$ambienteAutorizado);            
            $this->actualizacionReenvio($numFactura,$estado,$fechaAutorizacion,$tipoDocumento);
        }       
    }

    public function destroy(Sri_Autorizacion $sri_Autorizacion)
    {    
    }


}
