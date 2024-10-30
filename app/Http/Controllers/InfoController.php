<?php 

namespace App\Http\Controllers;

use App\Models\Sri_Autorizacion;
use App\Models\Vendedor;
use App\Models\Producto;
use App\Models\Cliente;

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;



class InfoController extends Controller{


    public function index(){
        $respuesta= array(
            "numFacturas"=> $this->showNumFacturas(),
            "numClientes"=>$this->showNumClientes(),
            "numProductos"=>$this->showNumProductos(),
            "totalFacturado"=>$this->showMontoTotal()
        );        
        return $respuesta;
    }

    public function showDataGeneral(String $idUser){
        $respuesta= array(
            "numFacturas"=> $this->showNumFacturas($idUser),
            "numClientes"=>$this->showNumClientes($idUser),
            "numProductos"=>$this->showNumProductos($idUser),
            "totalFacturado"=>$this->showMontoTotal($idUser)
        );        
        return $respuesta;
    }
    /*
    public function showNumFacturas(String $idUser){        
        return Sri_Autorizacion::whereNotNull('factura_id')->count();
    }
    */
    public function showNumFacturas(String $idUser) {        
        return Sri_Autorizacion::whereNotNull('factura_id')
            ->whereHas('Factura.Secuencial.DatosEmisores.Usuarios', function($query) use ($idUser) {
                $query->where('id', $idUser);
            })
            ->count();
    }

    public function showNumClientes(String $idUser){        
        $cliente = new ClienteController();
        return $cliente->show($idUser)->count();
    }

    public function showNumProductos(String $idUser){        
        $producto = new ProductoController();
        return $producto->show($idUser)->count();    
    }

    public function showMontoTotal(String $idUser){
        return Sri_Autorizacion::with(['Factura'])->whereNotNull('factura_id')
        ->whereHas('Factura.Secuencial.DatosEmisores.Usuarios', function($query) use ($idUser) {
            $query->where('id', $idUser);
        })
        ->get()->sum('factura.total_grabado');
        
    }



}