<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Factura</title>        
    
    <style type="text/css">
        *{
	margin: 0;
	padding: 0;
	box-sizing: border-box;
}
p, label, span, table{
	font-family: 'BrixSansRegular';
	font-size: 9pt;
}
.h2{
	font-family: 'BrixSansBlack';
	font-size: 16pt;
}
.h3{
	font-family: 'BrixSansBlack';
	font-size: 12pt;
	display: block;
	background: #0a4661;
	color: #FFF;
	text-align: center;
	padding: 3px;
	margin-bottom: 5px;
}
#page_pdf{
	width: 95%;
	margin: 15px auto 10px auto;
}

#factura_head, #factura_cliente, #factura_detalle{
	width: 100%;
	margin-bottom: 10px;
}
.logo_factura{
	width: 25%;
}
.info_empresa{
	width: 50%;
	text-align: center;
}
.info_factura{
	width: 25%;
}
.info_cliente{
	width: 100%;
}
.datos_cliente{
	width: 100%;
}
.datos_cliente tr td{
	width: 50%;
}
.datos_cliente{
	padding: 10px 10px 0 10px;
}
.datos_cliente label{
	width: 75px;
	display: inline-block;
}
.datos_cliente p{
	display: inline-block;
}

.info_cliente {
    position: absolute;
    left: 0;
    width: 50%; /* O el ancho deseado */
    height:auto;
}

.forma_pago {
    position: relative;
    right: 0;
    width: 50%; /* O el ancho deseado */
    height:auto;
}

.textright{
	text-align: right;
}
.textleft{
	text-align: left;
}
.textcenter{
	text-align: center;
}
.round{
	border-radius: 10px;
	border: 1px solid #0a4661;
	overflow: hidden;
	padding-bottom: 15px;
}
.round p{
	padding: 0 15px;
}

#factura_detalle{
	border-collapse: collapse;
}
#factura_detalle thead th{
	background: #058167;
	color: #FFF;
	padding: 5px;
}
#detalle_productos tr:nth-child(even) {
    background: #ededed;
}
#detalle_totales span{
	font-family: 'BrixSansBlack';
}
.nota{
	font-size: 8pt;
}
.label_gracias{
	font-family: verdana;
	font-weight: bold;
	font-style: italic;
	text-align: center;
	margin-top: 20px;
}
.anulada{
	position: absolute;
	left: 50%;
	top: 50%;
	transform: translateX(-50%) translateY(-50%);
}
.f-16{
    font-size: 0.9rem;
}
.codeBar{
	padding: 5px 5px;
}
.mr{
	margin-right:70px;
}
</style>
</head>
<body>

<div id="page_pdf">
    <br>
    <br>
	<table id="factura_head">
		<tr>
			<td class="logo_factura">
				<div>									
				<!--img src="{{ asset('imagenes/MV_LOGO.jpg') }}" alt="Descripción de la imagen"-->
				<img src='{{asset($factura->secuencial->datos_emisores->usuarios->logo)}}' alt="">
				</div>
			</td>
			<td class="info_empresa">
				<div>
					<span class="h2">{{$factura->secuencial->datos_emisores->razon_social}}</span>					
					<p class="f-16">RUC: <span class="f-16">{{$factura->secuencial->datos_emisores->usuarios->ruc}}</span></p>
					<p class="f-16">{{$factura->secuencial->datos_emisores->direccion}}</p>
					<p class="f-16">{{$factura->secuencial->datos_emisores->email}}</p>
                    <p class="f-16">{{$factura->secuencial->datos_emisores->lleva_contabilidad==1 ? "Obligado a llevar Contabilidad": "No Obligado a llevar Contabilidad"}}</p>
					<p class="f-16">{{$factura->secuencial->datos_emisores->contribuyente_retencion==1 ? "Contribuyente Especial: SI": "Contribuyente Especial: NO"}}</p>
				</div>
			</td>
			<td class="info_factura">
				<div class="round">
					<span class="h3">Factura</span>
					<p class="f-16">No. Factura: <strong>{{$factura->secuencial->codigo_establecimiento}} - {{$factura->secuencial->punto_emision}} - {{$factura->secuencial_sec}} </strong></p>
					<p class="f-16">No. de Autorización: </p>	
					<p><span>{{$autorizacion->clave_acceso_sri}} </span></p>
					<p class="f-16">Ambiente: {{$factura->ambiente==0 ? "Pruebas":"Producción"}}</p>
                    <p class="f-16">Emision: Normal</p>					
                    <p class="f-16">Clave Acceso:</p>
					<div class="codeBar">
						{!!DNS1D::getBarcodeHTML($autorizacion->clave_acceso_sri, 'C128', 1, 30)!!}
					</div>
					<p><span>{{$autorizacion->clave_acceso_sri}} </span></p>
				</div>
			</td>
		</tr>
	</table>
	<span class="h3">Datos del Cliente</span>
    <table id="factura_cliente">        
		<tr>
			<td class="info_cliente">
				<div class="round">				                
                    <div class="">
                        <div class="">
							<table>
								<tr>
									<td>
										<p class="f-16">Razón Social / Nombres Apellidos:  <strong>{{$factura->cliente->nombre}} </strong></p>
									</td>
									<td>
										<p class="f-16">Fecha Emision: {{$factura->fecha_emision}}</p>
									</td>
								</tr>
							</table>
                            
                            <p class="f-16">RUC: <span class="f-16">{{$factura->cliente->numero_documento}} </span></p>                                                     
							<table>
								<tr>
									<td>
										<p class="f-16 mr">Correo: {{$factura->cliente->correo}}</p>
									</td>						
								</tr>
								<tr>
									<td>
										<p class="f-16">Dirección: {{$factura->cliente->direccion}}</p>
									</td>
								</tr>
								<tr>
									<td>
										<p class="f-16 mr">Telf: <span>{{$factura->cliente->telefono}}</span></p>
									</td>									
								</tr>						
							</table>							                                                							
                        </div>
                    </div>
                    
				</div>
			</td>            
            <!--td class="info_cliente">
				<div class="round">				                
                    <div class="">
                        <div class="">                                          
                            <p class="btn btn-primary f-16"><strong>Forma de Pago:</strong><span>{{$formaPago}} </span></p>                            
                            <p class="f-16"><strong> Plazo: </strong><span>{{$factura->plazo}} </span></p>
                            <p class="f-16"><strong> Tiempo: </strong><span>{{$tiempoPago}} </span></p>
							<p class="f-16"><strong> Valor: </strong><span>{{$factura->total_grabado}} </span></p>
                                      
                        </div>
                    </div>                    
				</div>
			</td-->
		</tr>
	</table>

	<table id="factura_detalle">
			<thead>
				<tr>
					<th class="f-16" width="50px">Cant.</th>
                    <th class="f-16" width="50px">Codigo.</th>
					<th class="textcenter f-16">Descripción</th>
					<th class="textcenter f-16" width="150px">Precio Unitario.</th>
                    <th class="textcenter f-16" width="150px">Descuento</th>
					<th class="textcenter f-16" width="150px">Total</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">
				<?php foreach($detalles as $detalle) { ?>
				<tr>
					<td class="textcenter f-16">{{$detalle->cantidad}}</td>
                    <td class="textcenter f-16">{{$detalle->producto->codigo_producto}}</td>
					<td class="textcenter f-16">{{$detalle->producto->nombre_producto}}</td>					
                    <td class="textcenter f-16">{{$detalle->producto->precio_producto}}</td>
                    <td class="textcenter f-16">{{$detalle->descuento}}</td>
					<td class="textcenter f-16">{{number_format($detalle->subtotal,2,'.','')}}</td>
				</tr>
				<?php } ?>
			</tbody>
            <br>            
			<tfoot id="detalle_totales">	
					<tr>
						<tr>
							<td colspan="5" class="textright f-16"><strong>Subtotal 15%</strong></td>
							<td class="textcenter"><span class="f-16">{{number_format($factura->subtotal_12,2,'.','')}} </span></td>
						</tr>
						<tr>
							<td colspan="5" class="textright f-16"><strong>Subtotal 0%</strong></td>
							<td class="textcenter"><span class="f-16">{{number_format($factura->subtotal_0,2,'.','')}} </span></td>
						</tr>
						<tr>
							<td colspan="5" class="textright f-16"><strong>Subtotal No Objeto de IVA</strong></td>
							<td class="textcenter"><span class="f-16">{{number_format($factura->subtotal_no_objeto_iva,2,'.','')}} </span></td>
						</tr>

						<tr>
							<td colspan="5" class="textright f-16"><strong>Subtotal Sin Impuesto</strong></td>
							<td class="textcenter"><span class="f-16">{{number_format($factura->subtotal_sin_impuesto,2,'.','')}}</span></td>
						</tr>
						<tr>
							<td colspan="5" class="textright f-16"><strong>Descuentos</strong></td>
							<td class="textcenter"><span class="f-16">{{number_format($factura->total_descuento,2,'.','')}} </span></td>
						</tr>
						<tr>
							<td colspan="5" class="textright f-16"><strong>Iva 15%</strong></td>
							<td class="textcenter"><span class="f-16">{{number_format($factura->total_grabado- $factura->subtotal_sin_impuesto,2,'.','')}} </span></td>
						</tr>
						<tr>
							<td colspan="5" class="textright f-16"><strong>Valor Total</strong></td>
							<td class="textcenter"><span class="f-16">{{number_format($factura->total_grabado,2,'.','') }} </span></td>
						</tr>
					</tr>             
			</tfoot>
	</table>

    <table id="">        
		<tr>
			<div class="round">				                
				<div class="">
					<div class="">                                          
						<p class="btn btn-primary f-16 textcenter"><strong>Forma de Pago</strong></p><br>
						<p class="btn btn-primary f-16"><strong>Forma de Pago:</strong><span>{{$formaPago}} </span></p>                            
						<p class="f-16"><strong> Plazo: </strong><span>{{$factura->plazo}} </span></p>
						<p class="f-16"><strong> Tiempo: </strong><span>{{$tiempoPago}} </span></p>
						<p class="f-16"><strong> Valor: </strong><span>{{number_format($factura->total_grabado,2,'.','')}} </span></p>
								
					</div>
				</div>                    
			</div>
		</tr>
		<tr>
			<td class="info_cliente">
				<div class="round">				                
                    <div class="">
                        <div class="">
                            <p class="btn btn-primary f-16"><strong>Detalle Adicional: </strong><span class="f-16">{{$factura->detalle_factura}} </span></p>                                                                                                            
                        </div>
                    </div>
                    
				</div>
			</td>
        </tr>            
    </table>

	<!--div>
		<p class="nota">Detalle Adicional: sasasasasasasasasasa</p>		
	</div-->

</div>

</body>
</html>