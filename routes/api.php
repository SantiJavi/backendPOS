<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Mail\Notificacion;
use Illuminate\Support\Facades\Mail;

Route::get('/reportes/facturas/{factura}','FacturaController@facturaPdf');
Route::get('/reportes/retenciones/{retencion}','RetencionController@retencionPdf');
Route::get('/descargar_factura/{factura}','XmlController@descargarXML');
Route::get('/descargar_retencion/{retencion}','XmlRetencionController@descargarXML');
Route::post('/firmar/sign','XmlController@saveXMLFirmado');
Route::get('/mail/test',function (){    
    $reponse=Mail::to('facturadorenvio@mvelectricful.com')->send(new Notificacion('Santiago'));
});

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/
