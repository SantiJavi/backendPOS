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
	<span class="h3">Datos del Cliente</span>
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
				<?php foreach($prevFactura as $detalle) { ?>
				<tr>
					<td class="textcenter f-16">{{$detalle->cantidad}}</td>                   
                    <td class="textcenter f-16">{{$detalle->descuento}}</td>
					<td class="textcenter f-16">{{number_format($detalle->subtotal,2,'.','')}}</td>
				</tr>
				<?php } ?>
			</tbody>
					
	</table>
</div>

</body>
</html>