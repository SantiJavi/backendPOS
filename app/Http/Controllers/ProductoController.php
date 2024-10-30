<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Utils\Utils;

class ProductoController extends Controller
{    
    public function index()
    {
        return Producto::all();
    }

    
    public function store(Request $request)
    {
        if($this->showCodProducto($request->codigo_producto,$request->user_id)){
            return response()->json(['code'=>500,'message'=>'Producto ya registrado'],200);
        }else{
            $producto=new Producto();
            $producto->codigo_producto=Utils::replaceSpecialCharacters($request->codigo_producto);
            $producto->nombre_producto=Utils::replaceSpecialCharacters($request->nombre_producto);
            $producto->codigo_aux=Utils::replaceSpecialCharacters($request->codigo_aux);
            $producto->precio_producto=$request->precio_producto;
            $producto->impuesto_iva=$request->impuesto_iva;
            $producto->descuento=$request->descuento;
            $producto->impuesto_ice=$request->impuesto_ice;
            $producto->user_id = $request->user_id;
            $producto->save();
            return response()->json(['code'=>200,'message'=>'Producto Creado correctamente'],200);
        }
      

    }
    public function show(String $idUsuario)
    {
        return Producto::where('user_id',$idUsuario)->get();
    }

    public function showCodProducto(String $codProducto,String $userId){
        return Producto::where('codigo_producto',$codProducto)
        ->where('user_id',$userId)
        ->get()->first();
    }

    public function showProducto(String $idProducto){
        return Producto::where('id',$idProducto)->get()->first();
    }

    public function update(Request $request, Producto $producto)
    {
        if($this->showCodProducto($request->codigo_producto,$request->user_id)){
            $producto->codigo_producto=Utils::replaceSpecialCharacters($request->codigo_producto);
            $producto->nombre_producto=Utils::replaceSpecialCharacters($request->nombre_producto);
            $producto->codigo_aux=Utils::replaceSpecialCharacters($request->codigo_aux);
            $producto->precio_producto=$request->precio_producto;
            $producto->impuesto_iva=$request->impuesto_iva;
            $producto->descuento=$request->descuento;
            $producto->impuesto_ice=$request->impuesto_ice;
            $producto->save();
            return response()->json(['code'=>200,'message'=>'Producto Actualizado correctamente'],200);            
        }else{
            return response()->json(['code'=>500,'message'=>'Producto ya registrado'],200);    
        }
        
    }
    public function destroy(Producto $producto)
    {
        $producto->delete();        
        return response()->json(['message' => 'Producto eliminado correctamente'], 200);
    }
}
