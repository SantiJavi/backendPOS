<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\DetalleFacturaController;
use App\Models\Sri_Autorizacion;
use SoapClient;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\Notificacion;

use SimpleXMLElement;

require(dirname(__FILE__,4) . '/app/FirmaElectronica/hacienda/firmador.php');
use App\FirmaElectronica\hacienda\Firmador;


class XmlController extends Controller
{
 
    public function store(Request $request){
        $base64String = $request->input('base64EncodedString');
        $decodedString = base64_decode($base64String);        
        $respuestasFacturas=array();
        $responseFinal="";
        $rutaArchivoFirmado=dirname(__FILE__,4).'/resources/views/facturas_firmadas';
        $xmlString =$decodedString;        
        try {                             
            $xml = new SimpleXMLElement($xmlString);
            $claveAcceso = (string) $xml->infoTributaria->claveAcceso;
            $ambiente = (string) $xml->infoTributaria->ambiente;
            $secuencial = (string) $xml->infoTributaria->secuencial;
            $rutaCompletaArchivoFirmado=$rutaArchivoFirmado.'/'.$claveAcceso.".xml";             
            $razonSocialComprador= (string) $xml->infoFactura->razonSocialComprador;
            $valor = (string)$xml->infoFactura->pagos->pago->total;            
            $emailCliente = '';
            foreach ($xml->infoAdicional->campoAdicional as $campo) {
                if ($campo['nombre'] == 'Email Cliente') {
                $emailCliente = (string) $campo;
                break;
                }
            }
            file_put_contents($rutaCompletaArchivoFirmado,$xmlString);            
            $respuestaRecibida=self::responseSri($rutaCompletaArchivoFirmado,$ambiente);            
            $estadoRecepcion=$respuestaRecibida->RespuestaRecepcionComprobante->estado;                                                            
            $mensajeRecibido = $respuestaRecibida->RespuestaRecepcionComprobante->comprobantes->comprobante->mensajes->mensaje->mensaje ?? "Factura Recibida";
            $mensajeTest = $respuestaRecibida->RespuestaRecepcionComprobante->comprobantes;
            if($estadoRecepcion!="RECIBIDA"){
                $responseFinal="Error\n".$mensajeRecibido;   
                return $responseFinal;
                exit();
            }else{
                sleep(3);
                $respuestaAutorizacion=self::responseSriAutorizaicon($claveAcceso,$ambiente);                                              
                $estado=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;  
                $comprobante=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante;
                $numeroAutorizacionSri=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion ?? " ";
                $ambienteAutorizado=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
                $fechaAutorizacion=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;            
                if($estado=="NO AUTORIZADO"){                    
                    return $estado;                    
                    exit();                    
                }else{
                    self::guardarXML($comprobante,$numeroAutorizacionSri,$estado,$fechaAutorizacion,$ambienteAutorizado);
                    array_push($respuestasFacturas,$respuestaRecibida,$comprobante,$claveAcceso,$estado,$numeroAutorizacionSri);
                    //obtencion del ID de factura para guardar en la Tabla de Autorizaciones
                    $valorReal=(string) $xml->infoTributaria->secuencial;
                    $factura=new FacturaController();
                    $idFact=$factura->getIdFacturaBySecuencial($valorReal);
                    //recuperar correo del emisor
                    $correoEmisorFactura = $factura->showById($idFact);                    
                    $correoEmisorFactura=$correoEmisorFactura->Secuencial->DatosEmisores->email;
                    
                    //guardar en Autorizacion
                    $sriAutorizacion=new Sri_Autorizacion();
                    $sriAutorizacion->num_autorizacion_sri=$numeroAutorizacionSri;
                    $sriAutorizacion->clave_acceso_sri=$numeroAutorizacionSri;
                    $sriAutorizacion->estado=$estado;
                    $sriAutorizacion->fecha_autorizacion = $fechaAutorizacion;
                    $sriAutorizacion->factura_id=$idFact;
                    $sriAutorizacion->save();
                    //actualizacion del valor del secuencial                                  
                    $valorSecuencialSiguiente = self::generarSecuencial($valorReal);
                    $controlador=new SecuencialController();
                    $secuencialModificar=$controlador->findByValueFactura($valorReal); 
                    $secuencialModificar->sec_sig_fact=$valorSecuencialSiguiente;
                    $secuencialModificar->save();                    
                    //envio de email (ingresar copia de correo)
                    //$cifrado = Crypt::encryptString($idFact);
                    $urlPDF=env('URL_DOC_MAIL').env('DIRECCION_UB_FACTURA').$idFact;
                    $tipoDocumento='Factura';
                    $direccionDocumento='../resources/views/facturas_autorizadas/';                    
                    $reponseMail=Mail::to($emailCliente)->cc($correoEmisorFactura)->send(new Notificacion($razonSocialComprador,$valor,$claveAcceso,$urlPDF,$tipoDocumento,$direccionDocumento));
                    return "Factura Generada Correctamente";
                }                
            }                                                                      
        } catch (\Exception $e) {                 
            return response($e);
        }
        
    }
    
    public function generarFacturaXml($factura) 
    {                        
        $facturas=new FacturaController();
        $detallesComprobante=new DetalleFacturaController();
        $sriAutorizaciones=new SriAutorizacionController();

        $response=json_decode($facturas->show($factura),true);
        $detallesComprobante=json_decode($detallesComprobante->show($factura->id));
        //Datos para facturas
        $codDoc='01'; //tipoComprobante
        $tipoIdentificadorComprador=self::identificadorComprador($response['cliente']['tipo_identificador']);        

        //varibales de remplazo
        $infoSecuencial=$response['secuencial'];
        $infoCliente=$response['cliente'];
    

        $xmlDoc = new \DOMDocument('1.0', 'utf-8');
        $xmlDoc->formatOutput = true;        
        //elemento factura
        $facturaElement = $xmlDoc->createElement('factura');
        $facturaElement->setAttribute('id', 'comprobante');
        $facturaElement->setAttribute('version', '1.1.0');        

        //info tributaria
        $infoTributaria = $xmlDoc->createElement('infoTributaria');
        $ambiente=$response['ambiente']==1?'2':'1';
        $nombreAmbiente=$xmlDoc->createElement('ambiente',$ambiente);
        $tipoEmision=$xmlDoc->createElement('tipoEmision','1');
        $razonSocial=$xmlDoc->createElement('razonSocial',$infoSecuencial['datos_emisores']['razon_social']);
        $nombreComercial=$xmlDoc->createElement('nombreComercial',$infoSecuencial['datos_emisores']['nombre_comercial']);
        $ruc=$xmlDoc->createElement('ruc',$infoSecuencial['datos_emisores']['usuarios']['ruc']);        
         
        //fechaEmision
        $newFecha=date("d/m/Y",strtotime($response['fecha_emision']));
        //clave de acceso
        $digVerif=self::generarClaveAcceso($newFecha,$codDoc,$infoSecuencial['datos_emisores']['usuarios']['ruc'],$ambiente,$infoSecuencial['codigo_establecimiento'],$infoSecuencial['punto_emision'],$response['secuencial_sec'],'1');
        $claveAcceso=$xmlDoc->createElement('claveAcceso',$digVerif);

        $codDoc=$xmlDoc->createElement('codDoc',$codDoc);
        $estab=$xmlDoc->createElement('estab',$infoSecuencial['codigo_establecimiento']);
        $ptoEmi=$xmlDoc->createElement('ptoEmi',$infoSecuencial['punto_emision']);
        $secuencial=$xmlDoc->createElement('secuencial',$response['secuencial_sec']);
        $dirMatriz=$xmlDoc->createElement('dirMatriz',$infoSecuencial['datos_emisores']['direccion']);
        
        //info factura cliente
        $infoFactura=$xmlDoc->createElement('infoFactura');        
        $fechaEmision=$xmlDoc->createElement('fechaEmision',$newFecha);
        $dirEstablecimiento=$xmlDoc->createElement('dirEstablecimiento',$infoSecuencial['datos_emisores']['direccion']);        
        $obligadoContabilidad=$xmlDoc->createElement('obligadoContabilidad',$infoSecuencial['datos_emisores']['lleva_contabilidad']==1?"SI":"NO");
        $tipoIdentificacionComprador=$xmlDoc->createElement('tipoIdentificacionComprador',$tipoIdentificadorComprador);
        $razonSocialComprador=$xmlDoc->createElement('razonSocialComprador',$infoCliente['nombre']);
        $identificacionComprador=$xmlDoc->createElement('identificacionComprador',$infoCliente['numero_documento']);
        $direccionComprador=$xmlDoc->createElement('direccionComprador',$infoCliente['direccion']);
        $totalSinImpuestos=$xmlDoc->createElement('totalSinImpuestos',self::decimales($response['subtotal_12'],4));
        $totalDescuento=$xmlDoc->createElement('totalDescuento',$response['total_descuento']);        
        
        //total con impuestos
        $totalConImpuestos=$xmlDoc->createElement('totalConImpuestos');
        $totalImpuesto=$xmlDoc->createElement('totalImpuesto');
        
        $codigo=$xmlDoc->createElement('codigo','2'); //porque es Iva de la factura
        $codigoPorcentaje=$xmlDoc->createElement('codigoPorcentaje','4'); //porque es iva y aplica el 12%
        $baseImponible=$xmlDoc->createElement('baseImponible',self::decimales($response['subtotal_12'],4));        
        $valorIva=floatval($response['total_grabado']) - floatval($response['subtotal_12']);        
        $valor=$xmlDoc->createElement('valor',self::decimales($valorIva,2));
        
        //adicionales pago
        $propina=$xmlDoc->createElement('propina',isset($response['propina'])?$response['propina']:0);
        $importeTotal=$xmlDoc->createElement('importeTotal',self::decimales($response['total_grabado'],4));
        $moneda=$xmlDoc->createElement('moneda','DOLAR');

        //forma de pago
        $codFormaPago=self::codigoformaPago($response['forma_pago']);

        //pagos
        $pagos=$xmlDoc->createElement('pagos');
        $pago=$xmlDoc->createElement('pago');
        $formaPago=$xmlDoc->createElement('formaPago',$codFormaPago);
        $total=$xmlDoc->createElement('total',self::decimales($response['total_grabado'],2));
        $plazo=$xmlDoc->createElement('plazo',$response['plazo']);
        $unidadTiempo=$xmlDoc->createElement('unidadTiempo',$response['unidades_tiempo']=="days"?"dias":$response['unidades_tiempo']);
        //$detalles
        $detalles=$xmlDoc->createElement('detalles');                    
        foreach($detallesComprobante as $data) {
            //tipoImpuesto
            $tipoCodigoImpuesto=self::tipoCodImpuesto($data->producto->impuesto_iva,$data->producto->impuesto_ice);
            //tipoCodigoPorcentaje            
            $tipoCodigoPorcetaje=self::tipoCodigoPorcentaje($data->producto->impuesto_iva);        
            $detalle=$xmlDoc->createElement('detalle');
            $codigoPrincipal=$xmlDoc->createElement('codigoPrincipal',$data->producto->codigo_producto);
            $descripcion=$xmlDoc->createElement('descripcion',$data->producto->nombre_producto);
            $cantidad=$xmlDoc->createElement('cantidad',$data->cantidad);
            $precioUnitario=$xmlDoc->createElement('precioUnitario',$data->producto->precio_producto);
            $descuento=$xmlDoc->createElement('descuento',self::decimales($data->descuento,2));
            $precioTotalSinImpuesto=$xmlDoc->createElement('precioTotalSinImpuesto',self::decimales(($data->producto->precio_producto * $data->cantidad)-$data->descuento,2));
            $impuestos=$xmlDoc->createElement('impuestos');
            $impuesto=$xmlDoc->createElement('impuesto');
            $impuestoCodigo=$xmlDoc->createElement('codigo',$tipoCodigoImpuesto);            
            $impuestoCodPorcentaje=$xmlDoc->createElement('codigoPorcentaje','4'); //modificar
            $impuestoTarifa=$xmlDoc->createElement('tarifa',$data->producto->impuesto_iva);
            $impuestoBaseImpo=$xmlDoc->createElement('baseImponible',self::decimales(floatval($data->producto->precio_producto * $data->cantidad)-$data->descuento,2));            
            $IVA=env('IVA');
            $impuestoValor=$xmlDoc->createElement('valor',self::decimales(floatval($data->producto->precio_producto * $data->cantidad) * $IVA,2));
            
            $detalle->appendChild($codigoPrincipal);
            $detalle->appendChild($descripcion);
            $detalle->appendChild($cantidad);
            $detalle->appendChild($precioUnitario);
            $detalle->appendChild($descuento);
            $detalle->appendChild($precioTotalSinImpuesto);
            
            $impuesto->appendChild($impuestoCodigo);
            $impuesto->appendChild($impuestoCodPorcentaje);
            $impuesto->appendChild($impuestoTarifa);
            $impuesto->appendChild($impuestoBaseImpo);
            $impuesto->appendChild($impuestoValor);

            $impuestos->appendChild($impuesto);
                    
            $detalle->appendChild($impuestos);
            $detalles->appendChild($detalle);                        

        }

        $infoAdicional=$xmlDoc->createElement('infoAdicional');
        //info1
        $campoAdicional1=$xmlDoc->createElement('campoAdicional',$response['cliente']['correo']);
        $campoAdicional1->setAttribute('nombre', 'Email Cliente');        
        //info2
        $campoAdicional2=$xmlDoc->createElement('campoAdicional',$response['cliente']['telefono']);
        $campoAdicional2->setAttribute('nombre', 'Telef. Cliente');        
        //info3
        $campoAdicional3=$xmlDoc->createElement('campoAdicional',$response['detalle_factura']);
        $campoAdicional3->setAttribute('nombre', 'Detalle Adicional');        

        $infoAdicional->appendChild($campoAdicional1);
        $infoAdicional->appendChild($campoAdicional2);
        $infoAdicional->appendChild($campoAdicional3);

        $pago->appendChild($formaPago);
        $pago->appendChild($total);
        $pago->appendChild($plazo);
        $pago->appendChild($unidadTiempo);        
        $pagos->appendChild($pago);

        $totalImpuesto->appendChild($codigo);
        $totalImpuesto->appendChild($codigoPorcentaje);
        $totalImpuesto->appendChild($baseImponible);
        $totalImpuesto->appendChild($valor);
        
        $totalConImpuestos->appendChild($totalImpuesto);

        $infoFactura->appendChild($fechaEmision);
        $infoFactura->appendChild($dirEstablecimiento);
        $infoFactura->appendChild($obligadoContabilidad);
        $infoFactura->appendChild($tipoIdentificacionComprador);
        $infoFactura->appendChild($razonSocialComprador);
        $infoFactura->appendChild($identificacionComprador);
        $infoFactura->appendChild($direccionComprador);
        $infoFactura->appendChild($totalSinImpuestos);
        $infoFactura->appendChild($totalDescuento);
        $infoFactura->appendChild($totalConImpuestos);
        $infoFactura->appendChild($propina);
        $infoFactura->appendChild($importeTotal);
        $infoFactura->appendChild($moneda);
        $infoFactura->appendChild($pagos);

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
        //adicion de elementos                
        $facturaElement->appendChild($infoTributaria);
        $facturaElement->appendChild($infoFactura);        
        $facturaElement->appendChild($detalles);
        $facturaElement->appendChild($infoAdicional);    
        //adicion a etiqueta factura
        $xmlDoc->appendChild($facturaElement);
        // Guardar el XML en un archivo o devolverlo como respuesta HTTP
        $nombreArchivo=$claveAcceso->nodeValue;
        $rutaArchivo=dirname(__FILE__,4).'/resources/views/facturas_generadas/';
        $rutaFinal=$rutaArchivo.'/'.$nombreArchivo.".xml";    
        $xmlDoc->save($rutaFinal); 
        return $xmlDoc->saveXML();
        /*
        //llamado del programa JAVA con parametros de firmado
        $rutaArchivoJar=dirname(__FILE__,4).'/resources/ejecutable/firmador-sri.jar';
        $rutaFirmaElectronica=dirname(__FILE__,4).'/resources/ejecutable/firmaActualizadaMV.p12';
        $rutaArchivoFirmado=dirname(__FILE__,4).'/resources/views/facturas_firmadas';
        $nombreArchivoFirmado=$nombreArchivo.'_signed.xml';        
        //exec('java -jar '.$rutaArchivoJar.' '.$rutaFirmaElectronica.' '.'rodrigo2007 '.$rutaFinal.' '.$rutaArchivoFirmado.' '.$nombreArchivoFirmado);     
        
        $rutaCompletaArchivoFirmado=$rutaArchivoFirmado.'/'.$nombreArchivoFirmado;
        //envio al WS del SRI
        // 1. Primero se llama al metodo de recepcion y luego se debe esperar unos segundos para 
        // 2. llamar al metodo de comprobacion de autorizacion de los comprobantes                
        $respuestaRecibida=self::responseSri($rutaCompletaArchivoFirmado,$ambiente);
        sleep(3);
        $respuestaAutorizacion=self::responseSriAutorizaicon($nombreArchivo,$ambiente);        
        $estado=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado;
        $comprobante=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante;        
        $numeroAutorizacionSri=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
        $ambienteAutorizado=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
        $fechaAutorizacion=$respuestaAutorizacion->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;

        self::guardarXML($comprobante,$numeroAutorizacionSri,$estado,$fechaAutorizacion,$ambienteAutorizado);
        //(respuestaDeRecepcionSRI,comprobanteRespuestaSRI,claveAccedo,estadoAutorizacionSri,numeroAutorizacionSri)
        array_push($respuestasFacturas,$respuestaRecibida,$comprobante,$nombreArchivo,$estado,$numeroAutorizacionSri);        
        return $respuestasFacturas;
        */
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
    public static function tipoCodImpuesto($impuestoIva,$impuestoIce){
        $response="";
        if(isset($impuestoIva)){
            $response="2";
        }else if($impuestoIce){
            $response="3";
        }else{
            $response="5";
        }
        return $response;
    }
    public static function tipoCodigoPorcentaje($impuestoIva){
        $response="";
        if($impuestoIva=="12"){
            $response="2";
        }else if($impuestoIva=="0"){
            $response="0";
        }else if($impuestoIva=="15"){
            $response="4";          
        }else{
            $response="7";
        }
        return $response;
    }
    public static function codigoformaPago($formaPago){
        $response="";
        if($formaPago=="c_u_s_f"){
            $response="20";
        }else if($formaPago=="s_u_s_f"){
            $response="01";
        }else if($formaPago=="comp_deudas"){
            $response="15";
        }else if($formaPago=="t_debito"){
            $response="16";
        }else if($formaPago=="dinero_electronico"){
            $response="17";
        }else if($formaPago=="t_credito"){
            $response="19";
        }else if($formaPago=="t_prepago"){
            $response="18";
        }else if($formaPago=="endoso"){
            $response="21";
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
    public static function responseSriAutorizaicon(String $claveAcceso,String $ambiente){
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
    public static function guardarXML($dataXml,$numeroAutorizacion,$estado,$fechaAutorizacion,$ambiente){        
          // Aquí puedes usar los datos formateados como desees, por ejemplo, imprimirlos
        $xmlContent = "<autorizacion>";
        $xmlContent .="<estado>$estado</estado>";
        $xmlContent .="<numeroAutorizacion>$numeroAutorizacion</numeroAutorizacion>";
        $xmlContent .="<fechaAutorizacion>$fechaAutorizacion</fechaAutorizacion>";
        $xmlContent .="<ambiente>$ambiente</ambiente>";
        $xmlContent .="<comprobante><![CDATA[$dataXml]]></comprobante>";
        $xmlContent .="</autorizacion>";
        $path=dirname(__FILE__,4).'/resources/views/facturas_autorizadas/'.$numeroAutorizacion.'.xml';
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

    public static function getCodImpuesto(String $impuesto){
        
    }

}
