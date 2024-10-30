<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\RetencionController;
use App\Http\Controllers\DetalleFacturaController;
use App\Http\Controllers\SecuencialController;
use App\Models\Secuencial;
use App\Models\Sri_Autorizacion;
use DOMDocument;
use SoapClient;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Mail\Notificacion;

use SimpleXMLElement;

require(dirname(__FILE__,4) . '/app/FirmaElectronica/hacienda/firmador.php');
use App\FirmaElectronica\hacienda\Firmador;
class XmlRetencionController extends Controller
{
 
    public function store(Request $request){        
        $base64String = $request->input('base64EncodedString');
        $decodedString = base64_decode($base64String);
        $xmlString =$decodedString;    
        $cleanXmlString = mb_convert_encoding($xmlString, 'UTF-8', 'UTF-8');   
        $respuestasFacturas=array();
        $responseFinal="";
        $rutaArchivoFirmado=dirname(__FILE__,4).'/resources/views/retenciones_firmadas';
        try{
            $xml = new SimpleXMLElement($cleanXmlString); 
            $claveAcceso = (string) $xml->infoTributaria->claveAcceso;
            $ambiente = (string) $xml->infoTributaria->ambiente;
            $secuencial = (string) $xml->infoTributaria->secuencial;            
            $razonSocialSujetoRetenido= (string) $xml->infoCompRetencion->razonSocialSujetoRetenido;            
            $rutaCompletaArchivoFirmado=$rutaArchivoFirmado.'/'.$claveAcceso.".xml";
            $emailSujetoRetenido = '';
            foreach ($xml->infoAdicional->campoAdicional as $campo) {
                if ($campo['nombre'] == 'Email') {
                $emailSujetoRetenido = (string) $campo;
                break;
                }
            }
            $totalRetencion=0;
            foreach($xml->docsSustento->docSustento->retenciones->retencion as $rete){
                $totalRetencion += (float) $rete->valorRetenido;
            }            
            file_put_contents($rutaCompletaArchivoFirmado,$cleanXmlString);
            $respuestaRecibida=self::responseSri($rutaCompletaArchivoFirmado,$ambiente);
            $estadoRecepcion=$respuestaRecibida->RespuestaRecepcionComprobante->estado;
            $mensajeRecibido = $respuestaRecibida->RespuestaRecepcionComprobante->comprobantes->comprobante->mensajes->mensaje->mensaje ?? "Retencion Recibida";
             if($estadoRecepcion!="RECIBIDA"){
                $responseFinal="Error\n".$mensajeRecibido;   
                return $responseFinal;
                exit();
            }else{
            sleep(3);
                $respuestaAutorizacion=self::responseSriAutorizacion($claveAcceso,$ambiente);                                
                $estado=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;  
                $comprobante=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante;
                $numeroAutorizacionSri=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion ?? " ";
                $ambienteAutorizado=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
                $fechaAutorizacion=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;            
                if($estado!="AUTORIZADO"){                    
                    return $estado;
                    exit();                   
                }else{
                    self::guardarRetencionXML($comprobante,$numeroAutorizacionSri,$estado,$fechaAutorizacion,$ambienteAutorizado);
                    array_push($respuestasFacturas,$respuestaRecibida,$comprobante,$claveAcceso,$estado,$numeroAutorizacionSri);                                        
                    $numSecuencial = (String) $xml->infoTributaria->secuencial;                                        
                    $retencion = new RetencionController();                    
                    $idRetencion = $retencion->getIdRetencionBySecuencial($numSecuencial);                    
                    //correo del emisor
                    $correoEmisor = $retencion->showById($idRetencion);
                    $correoEmisor=$correoEmisor->Secuencial->DatosEmisores->email;
                    
                    //guardar en autorizaciones -> autorizacion dudosa                    
                    $sriAutorizacion=new Sri_Autorizacion();
                    $sriAutorizacion->num_autorizacion_sri=$numeroAutorizacionSri;
                    $sriAutorizacion->clave_acceso_sri=$numeroAutorizacionSri;
                    $sriAutorizacion->estado = $estado;
                    $sriAutorizacion->fecha_autorizacion = $fechaAutorizacion;
                    $sriAutorizacion->retencion_id=$idRetencion;
                    $sriAutorizacion->save();
                                    
                    //actualizar el valor de secuencial
                    $valorSecuencialSiguiente = self::generarSecuencial($numSecuencial);
                    $secuencialController = new SecuencialController();
                    $secuencialModificar = $secuencialController->findByValueRetencion($numSecuencial);
                    $secuencialModificar->sec_sig_com_ret = $valorSecuencialSiguiente;
                    $secuencialModificar->save();
                    //envio de email
                    $urlPDF=env('URL_DOC_MAIL').env('DIRECCION_UB_RETENCION').$idRetencion;                    
                    $tipoDocumento='Comprobante de Retención';
                    $direccionDocumento='../resources/views/retenciones_autorizadas/';
                    $reponseMail=Mail::to($emailSujetoRetenido)->cc($correoEmisor)->send(new Notificacion($razonSocialSujetoRetenido,$totalRetencion,$claveAcceso,$urlPDF,$tipoDocumento,$direccionDocumento));
                    return "Retencion Generada Correctamente";
                }
            }       
        } catch (\Exception $e) {                 
            return response($e);
        }        

    }
    
    public function generarRetencionXml($retencion) 
    {                
        $response;        
        $retenciones = new RetencionController();
        $detallesComprobante=new DetalleRetencionController();               
        $response=json_decode($retenciones->show($retencion),true);        
        $detallesComprobante=json_decode($detallesComprobante->show($retencion->id));                        
        //Datos para facturas
        $codDoc='07'; //tipoComprobante(Retencion)        
        //varibales de remplazo
        $infoSecuencial=$response['secuencial'];
        $infoVendedor=$response['vendedor'];
        $tipoIdentificadorVendedor=self::identificadorComprador($infoVendedor['tipo_identificador']);                

        $xmlDoc = new \DOMDocument('1.0', 'UTF-8');
        $xmlDoc->formatOutput = true;        
        //elemento factura
        $retencionElement = $xmlDoc->createElement('comprobanteRetencion');
        $retencionElement->setAttribute('id', 'comprobante');
        $retencionElement->setAttribute('version', '2.0.0');        

        //info tributaria
        $infoTributaria = $xmlDoc->createElement('infoTributaria');
        $ambiente=$infoSecuencial['datos_emisores']['ambiente']==1?'2':'1';
        $nombreAmbiente=$xmlDoc->createElement('ambiente',$ambiente);
        $tipoEmision=$xmlDoc->createElement('tipoEmision','1');
        $razonSocial=$xmlDoc->createElement('razonSocial',$infoSecuencial['datos_emisores']['razon_social']);
        $nombreComercial=$xmlDoc->createElement('nombreComercial',$infoSecuencial['datos_emisores']['nombre_comercial']);
        $ruc=$xmlDoc->createElement('ruc',$infoSecuencial['datos_emisores']['usuarios']['ruc']);                         
        
        //fechaEmision
        $newFecha=date("d/m/Y",strtotime($response['fecha_emision']));        
        $digVerif=self::generarClaveAcceso($newFecha,$codDoc,$infoSecuencial['datos_emisores']['usuarios']['ruc'],$ambiente,$infoSecuencial['codigo_establecimiento'],$infoSecuencial['punto_emision'],$infoSecuencial['sec_sig_com_ret'],'1');
        $claveAcceso=$xmlDoc->createElement('claveAcceso',$digVerif);
        $codDoc=$xmlDoc->createElement('codDoc',$codDoc);
        $estab=$xmlDoc->createElement('estab',$infoSecuencial['codigo_establecimiento']);
        $ptoEmi=$xmlDoc->createElement('ptoEmi',$infoSecuencial['punto_emision']);
        $secuencial=$xmlDoc->createElement('secuencial',$infoSecuencial['sec_sig_com_ret']);
        $dirMatriz=$xmlDoc->createElement('dirMatriz',$infoSecuencial['datos_emisores']['direccion']);
        //$agenteRetencion=$xmlDoc->createElement('agenteRetencion','1'); // por verificar
        
        //info Comprobante de retencion
        $infoRetencion=$xmlDoc->createElement('infoCompRetencion');        
        $fechaEmision=$xmlDoc->createElement('fechaEmision',$newFecha);
        $dirEstablecimiento=$xmlDoc->createElement('dirEstablecimiento',$infoSecuencial['datos_emisores']['direccion']);        
        $obligadoContabilidad=$xmlDoc->createElement('obligadoContabilidad',$infoSecuencial['datos_emisores']['lleva_contabilidad']==1?"SI":"NO");        
        $tipoIdentificacionSujetoRetenido=$xmlDoc->createElement('tipoIdentificacionSujetoRetenido',$tipoIdentificadorVendedor);        
        $tipoSujetoRetenido=$xmlDoc->createElement('tipoSujetoRetenido','01');
        $parteRelacionada=$xmlDoc->createElement('parteRel','NO');
        $razonSocialSujetoRetenido=$xmlDoc->createElement('razonSocialSujetoRetenido',$infoVendedor['razon_social']);
        $identificacionSujetoRetenido=$xmlDoc->createElement('identificacionSujetoRetenido',$infoVendedor['numero_documento']);
        $periodo_fiscal= $xmlDoc->createElement('periodoFiscal',$response['periodo_month'].'/'.$response['periodo_year']);
        //docs Sustento
        $docsSustento = $xmlDoc->createElement('docsSustento');
        $docSustento = $xmlDoc->createElement('docSustento');
        $codSustento = $xmlDoc->createElement('codSustento',$response['id_sustento_tributario']);
        $codDocSustento = $xmlDoc->createElement('codDocSustento',$response['tipo_doc_sustento']);
        $numDocSustento=$xmlDoc->createElement('numDocSustento',$response['numero_comprobante_retencion']);
        $fechaEmisionDocSustento=$xmlDoc->createElement('fechaEmisionDocSustento',self::rotarFecha($response['fecha_emision_documento_retencion']));
        $pagoLocExt = $xmlDoc->createElement('pagoLocExt','01'); 
        $totalComprobantesReembolso = $xmlDoc->createElement('totalComprobantesReembolso','0.00');
        $totalBaseImponibleReembolso = $xmlDoc->createElement('totalBaseImponibleReembolso','0.00');
        $totalImpuestoReembolso = $xmlDoc->createElement('totalImpuestoReembolso','0.00');
        $totalSinImpuestos = $xmlDoc->createElement('totalSinImpuestos',self::getBaseImponible($detallesComprobante));
        $importeTotal = $xmlDoc->createElement('importeTotal', self::getBaseImponible($detallesComprobante) * 1.15); //desacoplar

          
        //impuesto Doc Sustento
        $impuestosDocSustento = $xmlDoc->createElement('impuestosDocSustento');
        $impuestoDocSustento = $xmlDoc->createElement('impuestoDocSustento');
        $codImpuestoDocSustento = $xmlDoc->createElement('codImpuestoDocSustento','2');
        $codigoPorcentaje = $xmlDoc->createElement('codigoPorcentaje','2'); //atencion
        $baseImponible = $xmlDoc->createElement('baseImponible',self::getBaseImponible($detallesComprobante));
        $tarifa = $xmlDoc->createElement('tarifa','15');
        $IVA = env('IVA');
        $valorImpuesto = $xmlDoc->createElement('valorImpuesto',self::getBaseImponible($detallesComprobante)*$IVA); //desacoplar
        //ingreso impuestos        
        $impuestoDocSustento->appendChild($codImpuestoDocSustento);
        $impuestoDocSustento->appendChild($codigoPorcentaje);
        $impuestoDocSustento->appendChild($baseImponible);
        $impuestoDocSustento->appendChild($tarifa);
        $impuestoDocSustento->appendChild($valorImpuesto);
        $impuestosDocSustento->appendChild($impuestoDocSustento);

        //retenciones
        $retenciones=$xmlDoc->createElement('retenciones');        
        foreach($detallesComprobante as $data) {                              
            $retencion=$xmlDoc->createElement('retencion');            
            $codigo=$xmlDoc->createElement('codigo',self::tipoCodImpuesto($data->tipo_impuesto_retencion));            
            $codigoRetencion=$xmlDoc->createElement('codigoRetencion',$data->codigos_retencion->codigo_retencion);            
            $baseImponible=$xmlDoc->createElement('baseImponible',$data->base_imponible_retencion);            
            $porcentajeRetencion=$xmlDoc->createElement('porcentajeRetener',$data->codigos_retencion->porcentaje_cod_retencion);
            $valorRetenido=$xmlDoc->createElement('valorRetenido',$data->valor_retencion);                        
            
            $retencion->appendChild($codigo);
            $retencion->appendChild($codigoRetencion);
            $retencion->appendChild($baseImponible);
            $retencion->appendChild($porcentajeRetencion);
            $retencion->appendChild($valorRetenido);
            $retenciones->appendChild($retencion);    
                                             
        }        
        //pagos
        $pagos = $xmlDoc->createElement('pagos');
        $pago = $xmlDoc->createElement('pago');
        $formaPago = $xmlDoc->createElement('formaPago',$response['forma_pago']); //implementar
        $total = $xmlDoc->createElement('total',self::totalValorPago($detallesComprobante));

        $pago->appendChild($formaPago);
        $pago->appendChild($total);
        $pagos->appendChild($pago);

        //ingresoDocSustento
        $docSustento->appendChild($codSustento);
        $docSustento->appendChild($codDocSustento);
        $docSustento->appendChild($numDocSustento);
        $docSustento->appendChild($fechaEmisionDocSustento);
        $docSustento->appendChild($pagoLocExt);
        $docSustento->appendChild($totalComprobantesReembolso);
        $docSustento->appendChild($totalBaseImponibleReembolso);
        $docSustento->appendChild($totalImpuestoReembolso);
        $docSustento->appendChild($totalSinImpuestos);
        $docSustento->appendChild($importeTotal);
        $docSustento->appendChild($impuestosDocSustento);    
        $docSustento->appendChild($retenciones);
        $docSustento->appendChild($pagos);
        $docsSustento->appendChild($docSustento);
        $infoAdicional=$xmlDoc->createElement('infoAdicional');
        //info1
        $campoAdicional1=$xmlDoc->createElement('campoAdicional',$response['direccion']);
        $campoAdicional1->setAttribute('nombre', 'Direccion');        
        //info2
        $campoAdicional2=$xmlDoc->createElement('campoAdicional',$infoVendedor['celular']);
        $campoAdicional2->setAttribute('nombre', 'Telefono');        
        //info3
        $campoAdicional3=$xmlDoc->createElement('campoAdicional',$infoVendedor['correo']);
        $campoAdicional3->setAttribute('nombre', 'Email');
        //adicion de elementos  
        //campos adicionales
        $infoAdicional->appendChild($campoAdicional1);
        $infoAdicional->appendChild($campoAdicional2);
        $infoAdicional->appendChild($campoAdicional3);
        //infoCompRetencion
        $infoRetencion->appendChild($fechaEmision);
        $infoRetencion->appendChild($dirEstablecimiento);
        $infoRetencion->appendChild($obligadoContabilidad);
        $infoRetencion->appendChild($tipoIdentificacionSujetoRetenido); 
        //$infoRetencion->appendChild($tipoSujetoRetenido);
        $infoRetencion->appendChild($parteRelacionada);
        $infoRetencion->appendChild($razonSocialSujetoRetenido);     
        $infoRetencion->appendChild($identificacionSujetoRetenido);
        $infoRetencion->appendChild($periodo_fiscal);
        //infoFactura
        $infoTributaria->appendChild($nombreAmbiente);
        $infoTributaria->appendChild($tipoEmision);
        $infoTributaria->appendChild($razonSocial);
        $infoTributaria->appendChild($nombreComercial);
        $infoTributaria->appendChild($ruc);
        $infoTributaria->appendChild($claveAcceso);
        $infoTributaria->appendChild($codDoc);
        $infoTributaria->appendChild($estab);
        $infoTributaria->appendChild($ptoEmi);
        $infoTributaria->appendChild($secuencial);
        $infoTributaria->appendChild($dirMatriz);
        //$infoTributaria->appendChild($agenteRetencion);
        //adicion de elementos                
        $retencionElement->appendChild($infoTributaria);
        $retencionElement->appendChild($infoRetencion);
        $retencionElement->appendChild($docsSustento);
        $retencionElement->appendChild($infoAdicional);    
        //adicion a etiqueta factura
        $xmlDoc->appendChild($retencionElement);
        // Guardar el XML en un archivo o devolverlo como respuesta HTTP
        $nombreArchivo=$claveAcceso->nodeValue;
        $rutaArchivo=dirname(__FILE__,4).'/resources/views/retenciones_generadas';
        $rutaFinal=$rutaArchivo.'/'.$nombreArchivo.".xml";    
        $xmlDoc->save($rutaFinal); 
        return $xmlDoc->saveXML();
        }        

    public static function identificadorComprador($tipoIdentificador){
        $response="";
        if($tipoIdentificador=="RUC"){
            $response="04";
        }else if($tipoIdentificador=="CEDULA"){
            $response="05";
        }else if($tipoIdentificador=="PASAPORTE"){
            $response="06";
        }else if($tipoIdentificador=="CONSUMIDOR_FINAL"){
            $response="07";
        }else{
            $response="08";
        }
        return $response;
    }
    public static function tipoCodImpuesto($tipoImpuesto){
        $response="";
        if($tipoImpuesto == 'renta' ){
            $response = 1;
        }else if($tipoImpuesto == 'iva'){
            $response = 2;
        }else{
            $response = 6;
        }        
        return $response;
    }
    public static function tipoCodigoPorcentaje($impuestoIva){
        $response="";
        if($impuestoIva=="12"){
            $response="2";
        }else if($impuestoIva=="0"){
            $response="0";
        }else{
            $response="7";
        }
        return $response;
    }
    public static function decimales($valorOriginal,$decimal){        
        return number_format($valorOriginal, $decimal, '.', '');
    }
    
    public static function generarClaveAcceso($fechaEmision,$tipoComprobante,$documentoIdentidad,$ambiente,$codEstable,$puntEmision,$secuencial,$tipoEmision){
        //fechaEmision+tipoComprobante+documentoIdentidad+ambiente+codEstablecimiento+puntoEmision+secuencial+codigoGenerado(8)+tipoEmision+digitoVerificador
        $dfechaEmision=str_replace("/",'',$fechaEmision);
        $numeroAleatorio = rand(10000000, 99999999);
        $clave=$dfechaEmision.$tipoComprobante.$documentoIdentidad.$ambiente.$codEstable.$puntEmision.$secuencial.$numeroAleatorio.$tipoEmision;
        $digitoVerificador=self::digitoVerificador($clave);
        return $clave.$digitoVerificador;
    }
    public static function digitoVerificador($clave){        
        $verificador=2;
        $cont=1;
        $resultado=0;
        $arrayElementos=str_split($clave);        
        for($i=count($arrayElementos)-1;$i!=-1;$i--){
            if($cont%7==0){
                $verificador=$verificador>7?2:$verificador;
                $resultado+=$arrayElementos[$i]*$verificador;                
                $verificador++;
                $cont++;
            }else{
                $verificador=$verificador>7?2:$verificador;
                $resultado+=$arrayElementos[$i]*$verificador;                
                $verificador++;
                $cont++;
            }        
        }
        $paso5=$resultado%11;
        $resultadoFinal=11-$paso5;
        if($resultadoFinal==11){
            $resultadoFinal=0;
        }else if($resultadoFinal==10){
            $resultadoFinal=1;
        }else{
            $resultadoFinal=$resultadoFinal;
        }
        return $resultadoFinal;
    }

    public function firmarFacturaXml()
    {                    
        // Ruta al archivo XML
        $input = dirname(__FILE__,4).'\public\productos.xml';        
        // Ruta al archivo P12
        $p12File = dirname(__FILE__,4).'\public\firmaMV.p12';
        $p12Password='rodrigo1959';        
        $xml = new \DOMDocument();
        if (file_exists($input)){
            $input = file_get_contents($input);
        }
        try {
            $xml->loadXML($input);
        } catch (\Exception $ex){
            die($ex->getMessage());
        }
        $objSec = new XMLSecurityDSig();
        // Mantener el primer nodo secundario original XML en memoria
        $objSec->xmlFirstChild = $xml->firstChild;
        // MvS - Si el primer nodo es un xml stylesheet, usar el siguiente (el cual deberia ser la raiz)
        if ( $objSec->xmlFirstChild->nodeName == "xml-stylesheet" )
        {
                $objSec->xmlFirstChild = $objSec->xmlFirstChild->nextSibling;
        }
        $objSec->setSignPolicy();
    }
    
    
    public static function firmarElectronicamente(String $archivo,String $nombreArchivo){
        //claves para la firma electronica        
        $archivoFirmado=dirname(__FILE__,4).'/resources/views/facturas_firmadas/';
        $pfx = dirname(__FILE__,4).'/public/firmaMV.p12'; 
        $pin    = 'rodrigo1959';        
        $xml    = $archivo;
        $ruta   = $archivoFirmado.'/'.$nombreArchivo.'_signed.xml';
        $firmador = new Firmador();
        $base64 = $firmador->firmarXml($pfx, $pin, $xml, $firmador::TO_BASE64_STRING);
        $xml_string = $firmador->firmarXml($pfx, $pin, $xml, $firmador::TO_XML_STRING);
        $archivo = $firmador->firmarXml($pfx, $pin, $xml, $firmador::TO_XML_FILE, $ruta);
        return $ruta;
    }
    
    public static function responseSri(String $archivoFirmado,String $ambiente){ 
        if($ambiente=='1'){
        //pruebas
            $wsdlUrl = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';
        }else{
            //produccion
            $wsdlUrl = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl';
        }        
        try{
            $clienteSOAP = new SoapClient($wsdlUrl, array('trace' => 1));
            $contenidoXML = file_get_contents($archivoFirmado);
            $respuesta = $clienteSOAP->validarComprobante(array('xml' => $contenidoXML));
            return $respuesta;
        }catch(SoapFault $e){
            echo "Error con el SRI. Intentelo mas tarde";
            return $e->getMessage();
        }catch(Exception $e){
            echo "Error: " . $e->getMessage();
            return $e->getMessage();
        }
        
    }
    public static function responseSriAutorizacion(String $claveAcceso,String $ambiente){
        if($ambiente=='1'){
            //pruebas
            $wsdlUrl = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';        
            }else{
                //produccion
            $wsdlUrl = 'https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';
        }          
        try{
            $clienteSOAP = new SoapClient($wsdlUrl, array('trace' => 1));            
            $respuesta = $clienteSOAP->autorizacionComprobante(array('claveAccesoComprobante' => $claveAcceso));
            return $respuesta;
        }catch(SoapFault $e){
            echo "Error con el SRI. Intentelo mas tarde";
            return $e->getMessage();
        }catch(Exception $e){
            echo "Error: " . $e->getMessage();
            return $e->getMessage();
        }

    }
    public function descargarXML(String $claveAcceso){
        $wsdlUrl = 'https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl';        
        try{
            $clienteSOAP = new SoapClient($wsdlUrl, array('trace' => 1));            
            $respuesta = $clienteSOAP->autorizacionComprobante(array('claveAccesoComprobante' => $claveAcceso));
            $estado=$respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;
            $comprobante=$respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante;        
            $numeroAutorizacionSri=$respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
            $ambienteAutorizado=$respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $fechaAutorizacion=$respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;

            $xmlContent = "<autorizacion>";
            $xmlContent .="<estado>$estado</estado>";
            $xmlContent .="<numeroAutorizacion>$numeroAutorizacionSri</numeroAutorizacion>";
            $xmlContent .="<fechaAutorizacion>$fechaAutorizacion</fechaAutorizacion>";
            $xmlContent .="<ambiente>$ambienteAutorizado</ambiente>";
            $xmlContent .="<comprobante><![CDATA[$comprobante]]></comprobante>";
            $xmlContent .="</autorizacion>";
            
            $response = new Response($xmlContent);            
            // Establecer el tipo MIME del archivo
            $response->header('Content-Type', 'application/xml');
            // Establecer el encabezado Content-Disposition para forzar la descarga del archivo con el nombre "archivo.xml"
            $response->header('Content-Disposition', 'attachment; filename="'.$numeroAutorizacionSri.'".xml"');    
            return $response;
            //return $xmlContent;
            }catch(SoapFault $e){
            echo "Error con el SRI. Intentelo mas tarde";
            return $e->getMessage();
        }catch(Exception $e){
            echo "Error: " . $e->getMessage();
            return $e->getMessage();
        }

    }
    public static function guardarRetencionXML($dataXml,$numeroAutorizacion,$estado,$fechaAutorizacion,$ambiente){        
          // Aquí puedes usar los datos formateados como desees, por ejemplo, imprimirlos
        $xmlContent = "<autorizacion>";
        $xmlContent .="<estado>$estado</estado>";
        $xmlContent .="<numeroAutorizacion>$numeroAutorizacion</numeroAutorizacion>";
        $xmlContent .="<fechaAutorizacion>$fechaAutorizacion</fechaAutorizacion>";
        $xmlContent .="<ambiente>$ambiente</ambiente>";
        $xmlContent .="<comprobante><![CDATA[$dataXml]]></comprobante>";
        $xmlContent .="</autorizacion>";
        $path=dirname(__FILE__,4).'/resources/views/retenciones_autorizadas/'.$numeroAutorizacion.'.xml';
        file_put_contents($path,$xmlContent);
    }
        
    public static function saveXMLFirmado(Request $request){
        $base64EncodedString = $request;
        $decodedString = base64_decode($base64EncodedString);
        return $decodedString;
    }
    public static function generarSecuencial($valor){
        $valorReal=intval($valor)+1;
        return str_pad($valorReal, 9, '0', STR_PAD_LEFT);
    }
    public static function getMesByMes(String $mes){
        $meses = array(
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        );
    return array_search($mes,$meses);
    }

    public static function rotarFecha(String $fecha){        
        $fechaInicial= str_replace("-","/",$fecha);
        $elementosFecha = explode('/',$fechaInicial); 
        $fechaRotada = implode('/', array_reverse($elementosFecha));
        return $fechaRotada;
    }

    public static function getBaseImponible($data){
        foreach($data as $ret){
            if($ret->tipo_impuesto_retencion == 'renta'){
                return $ret->base_imponible_retencion;
            }
        }
    }

    public static function totalValorPago($data){
        $totalBaseImponible = 0;
        $totalRetencion = 0;
        foreach($data as $ret){
            $totalBaseImponible += $ret->base_imponible_retencion;
            $totalRetencion += $ret->valor_retencion;
        }
        return $totalBaseImponible - $totalRetencion;
    }
}
