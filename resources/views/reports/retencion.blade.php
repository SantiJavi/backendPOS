<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Retención</title>        
    
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
	width: 50%;
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
.info_adicional{
	margin-top:30px;
	width:200%;

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
.f-20{
	font-size: 1.2rem;
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
				<img src='{{asset($retencion->secuencial->datos_emisores->usuarios->logo)}}' alt="">
				</div>
			</td>
			<td class="info_empresa">
				<div>
					<span class="h2">{{$retencion->secuencial->datos_emisores->razon_social}}</span>					
					<p class="f-16">RUC: <span class="f-16">{{$retencion->secuencial->datos_emisores->usuarios->ruc}}</span></p>
					<p class="f-16">Dir.Matriz: <span>{{$retencion->secuencial->datos_emisores->direccion}}</span></p>
					<p class="f-16">Dir.Estab: <span>{{$retencion->secuencial->datos_emisores->direccion}}</span></p>
					<p class="f-16">{{$retencion->secuencial->datos_emisores->email}}</p>
                    <p class="f-16">{{$retencion->secuencial->datos_emisores->lleva_contabilidad==1 ? "Obligado a llevar Contabilidad": "No Obligado a llevar Contabilidad"}}</p>
					<p class="f-16">{{$retencion->secuencial->datos_emisores->contribuyente_retencion==1 ? "Contribuyente Especial: SI": "Contribuyente Especial: NO"}}</p>
				</div>
			</td>
			<td class="info_factura">
				<div class="round">
					<span class="h3">Comprobante de Retención</span>
					<p class="f-16">No. Comp. Retención: <strong>{{$retencion->secuencial->codigo_establecimiento}} - {{$retencion->secuencial->punto_emision}} - {{$retencion->secuencial_sig}} </strong></p>
					<p class="f-16">No. de Autorización: </p>	
					<p><span>{{$autorizacion->clave_acceso_sri}} </span></p>
					<p class="f-16">Ambiente: {{$retencion->ambiente==0 ? "Pruebas":"Producción"}}</p>
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
                            <p class="f-16">Razón Social / Nombres Apellidos:  <strong>{{$retencion->vendedor->razon_social}} </strong></p>
                            <p class="f-16">RUC: <span class="f-16">{{$retencion->vendedor->numero_documento}} </span></p>                                                     
							<table>
								<tr>
									<td>
										<p class="f-16 mr">Fecha: {{$retencion->fecha_emision}}</p>
									</td>								
								</tr>							
							</table>							                                                							
                        </div>
                    </div>
                    
				</div>
			</td>            
		</tr>
	</table>

	<table id="factura_detalle">
			<thead>
				<tr>
					<th class="textcenter f-16" width="150px">Num.Comprobante</th>
                    <th class="textcenter f-16" width="50px">Ejercicio Fiscal</th>					
					<th class="textcenter f-16" width="150px">Base Imponible</th>
                    <th class="textcenter f-16" width="100px">Impuesto</th>
					<th class="textcenter f-16" width="50px">Porcentaje Retención</th>
					<th class="textcenter f-16" width="150px">Valor Retenido</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">
				<?php foreach($detalles as $detalle) {  $codigosRetencion = $detalle->CodigosRetencion;?>				
				<tr>
					<td class="textcenter f-16">{{$retencion->numero_comprobante_retencion}}</td>
					<td class="textcenter f-16">{{$periodo}}</td>
					<td class="textcenter f-16">{{$detalle->base_imponible_retencion}}</td>					
					<td class="textcenter f-16">{{$detalle->tipo_impuesto_retencion == 'iva' ? "IVA" : "RENTA"}}</td>
					<td class="textcenter f-16">{{number_format($codigosRetencion->porcentaje_cod_retencion,2,'.','')}}</td>
					<td class="textcenter f-16">{{number_format($detalle->valor_retencion,2,'.','')}}</td>
				</tr>
				<?php } ?>
			</tbody>
			<br>
			<tfoot id="detalle_totales">	
					<tr>
						<tr>						
							<td colspan="5" class="textright f-20"><strong>TOTAL</strong></td>
							<td class="textcenter f-20"><strong class="f-20"> {{number_format($totalRetenciones,2,'.','')}}</strong></td>
						</tr>
					</tr>             
			</tfoot>

	</table>

    <table id="">
	<tr>
		<div class="round info_adicional f-16">				                
			<div class="">
				<div class="">                                          					
					<p class="btn btn-primary f-16"><strong>Dirección:</strong><span>{{$retencion->direccion}} </span></p>                            
					<p class="f-16"><strong> Telefóno: </strong><span>{{$retencion->telefono}}</span></p>
					<p class="f-16"><strong> E-mail: </strong><span>{{$retencion->vendedor->correo}} </span></p>											
				</div>
			</div>                    
		</div>
	</tr>        		
    </table>

	<!--div>
		<p class="nota">Detalle Adicional: sasasasasasasasasasa</p>		
	</div-->

</div>

</body>
</html>