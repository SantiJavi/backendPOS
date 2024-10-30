<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SriAutorizacionController;
use App\Http\Controllers\XmlController;
use App\Models\Sri_Autorizacion;

class ReenvioDocumentos extends Command
{
    protected $signature = 'reenvio.documentos';
    protected $description = 'Reenvia los comprobantes que aun no se han autorizado';
    

    public function handle()
    {
        $sriAutorizaciones = new SriAutorizacionController();
        $autorizaciones = $sriAutorizaciones->allDocuments();

        foreach($autorizaciones as $autorizacion){
            if($autorizacion->estado == 'POR PROCESAR'){
                $numDocAutorizacion = $autorizacion->clave_acceso_sri;
                $ambiente = $autorizacion->factura->ambiente == 0 ? "1" : "2" ; //1 Pruebas, 2 Produccion
                $numFactura = $autorizacion->factura_id; 
                self::envioDocumento($numDocAutorizacion,$ambiente,$numFactura);
            }
        }
    }

    public static function envioDocumento($numAutorizacion,$ambiente,$numFactura){
        $respuestaAutorizacion = XmlController::responseSriAutorizaicon($numAutorizacion,$ambiente);            
        $estado=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;  
        $comprobante=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante;
        $numeroAutorizacionSri=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion ?? " ";
        $ambienteAutorizado=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
        $fechaAutorizacion=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;        
        
        if($estado=="POR PROCESAR"){       
            return $estado;                                     
        }else{
            //editar la autorizacion            
            XmlController::guardarXML($comprobante,$numeroAutorizacionSri,$estado,$fechaAutorizacion,$ambienteAutorizado);
            $sriAutorizaciones = new SriAutorizacionController();
            $sriAutorizaciones->actualizacionReenvio($numFactura,$estado,$fechaAutorizacion);
        }       
    }
};