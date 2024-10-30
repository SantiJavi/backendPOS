<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
        }
        .card-header {
            background-color: #007bff;
            border-radius: 10px 10px 0 0;
            color: #fff;
            font-size: 24px;
            padding: 10px;
            text-align: center;
        }
        .boton{
            background-color: #007bff;
            border-radius: 10px 10px 10px 10px;
            font-size: 15px;
            padding: 10px;
            text-align: center;
            color: #fff;
        }
        .enlaceDescarga {
            color:#fff;
            text-decoration:none
        }        
        .card-body {
            color: #333;
            font-size: 18px;
            padding: 20px;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .description {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            Factura Generada por MVELECTRICFUL. CIA
        </div>
        <div class="card-body">            
            <p>Estimado: {{$name}} </p>
            <p>Le informamos que ha recibido una factura de: </p>             
            <br>
            <p>MVELECTRICFUL. CIA por el valor de: </p>
            <p class="total-amount">${{$valor}}</p>
            <p class="description">Descargue su comprobante en formato PDF aquí:</p>                        
            <a href="{{$urlPdf}}" class="boton enlaceDescarga">Descargar Documento Formato PDF</a>            
            <p class="description">Este correo ha sido generado automaticamente, por favor no lo responda</p>            
            <p>¡Gracias por su colaboración!</p>
        </div>
    </div>
</body>
</html>