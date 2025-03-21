<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XmlController;

//accede a traves del controlador
//la funcion apiResource internamente construye todos los metodos HTTP(GET,POST,PUT,DELETE)
Route::apiResource('/clientes','ClienteController');
Route::apiResource('/productos','ProductoController');
Route::apiResource('/vendedors','VendedorController');
Route::apiResource('/datos_emisors','DatosEmisorController');
Route::apiResource('/secuencials','SecuencialController');
Route::apiResource('/user','UserController');
Route::apiResource('/venta','VentaController');
Route::apiResource('/cuenta','CuentaController');
Route::get('/calculoCuenta/{id}','CuentaController@calcularCuenta');
Route::get('/cuentasCliente/{id}', 'CuentaController@showCuenta');
Route::apiResource('/detalle_venta','DetalleVentaController');
Route::get('/detalles/{detalleFactura}', 'DetalleFacturaController@show');
Route::apiResource('/detalles','DetalleFacturaController');
Route::get('/facturas/{factura}', 'FacturaController@showById');
Route::apiResource('/facturas','FacturaController');
Route::post('/login','UsuarioController@login');
Route::apiResource('/usuarios','UsuarioController');
Route::get('/autorizaciones/{autorizacion}','SriAutorizacionController@show');
Route::put('/autorizaciones/{autorizacion}','SriAutorizacionController@update');
Route::apiResource('/autorizaciones','SriAutorizacionController');
Route::apiResource('/xmlController','XmlController');
Route::apiResource('/xmlRetencionController','XmlRetencionController');
Route::apiResource('/cod_ret','CodigosRetencionController');
Route::get('/retenciones/{retencion}','RetencionController@show');
Route::apiResource('/retenciones','RetencionController');
Route::apiResource('/detalles_ret','DetalleRetencionController');
Route::get('/allRetenciones','SriAutorizacionController@showAllRetenciones');
Route::get('/getRetenciones/{retencion}','SriAutorizacionController@showRetenciones');
Route::get('/secuencialesUser/{user}','SecuencialController@findByUserInSecuencial'); // busca el secuencial en base a el id del usuario
Route::get('/proveedor/{proveedor}','VendedorController@showProveedor');
Route::get('/clientesId/{cliente}','ClienteController@showCliente');
Route::get('/productosId/{producto}','ProductoController@showProducto');
Route::get('/emisorId/{emisor}','DatosEmisorController@showEmisor');
Route::get('/secuencialId/{secuencial}','SecuencialController@showSecuencial');
Route::get('/secuencialUnique/{emisor}','SecuencialController@showUniqueSecuencial');
Route::get('/sriAutorizacionesFactIdUser/{idUser}','SriAutorizacionController@showDocumentsPerUser');
Route::get('/sriAutorizacionesRetIdUser/{idUser}','SriAutorizacionController@showDocumentsRetentionPerUser');
Route::put('/singpassword/{id}','UsuarioController@updatePassword');
Route::post('/logo/{id}','UsuarioController@uploadImageFile');
Route::put('/changepassword/{id}','UsuarioController@changePassword');
Route::post('/uploadSing/{id}','UsuarioController@uploadSign');
Route::get('/serve/{id}','UsuarioController@getSingforId');
Route::get('/detalleCuenta/{id}','DetalleVentaController@consultaCuenta');
Route::post('/ventaFecha','DetalleVentaController@consultaVentasByDate');
Route::post('/ventasDiarias','VentaController@ventasDiarias');
Route::put('/ventas/{venta}','VentaController@update');
Route::get('/deudores','VentaController@clientesDeudores');
Route::get('/saldos','CuentaController@cuentasPendientes');

//info de Dashboard
Route::get('/infoDashboard/{user}','InfoController@showDataGeneral');
Route::get('/allDocuments','SriAutorizacionController@allDocuments');
Route::get('/showAllUsersIdentifier','UserController@showAllUsersIdentifier');

// rutas para el reenvio automatico de los documentos electronicos
Route::get('/reenvioFacturas','SriAutorizacionController@reenvioFacturaElectronica');
Route::get('/reenvioRetenciones','SriAutorizacionController@reenvioRetencionElectronica');