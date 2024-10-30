<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use Illuminate\Http\Request;
use App\Utils\Utils;

class VendedorController extends Controller
{
    public function index()
    {
        return Vendedor::orderBy('razon_social','asc')->get();
    }

    public function store(Request $request)
    {
        if($this->showCodVendedor($request->codigo_vendedor,$request->user_id)){
            return response()->json(['code' => 500, 'message' => 'Proveedor ya registrado'], 200);      
        }else{
            $vendedor=new Vendedor();
            $vendedor->tipo_identificador=$request->tipo_documento;        
            $vendedor->numero_documento=$request->numero_documento;
            $vendedor->razon_social=Utils::replaceSpecialCharacters($request->razon_social);
            $vendedor->celular=$request->celular;
            $vendedor->correo=Utils::replaceSpecialCharacters($request->correo);
            $vendedor->codigo_vendedor=Utils::replaceSpecialCharacters($request->codigo_vendedor);        
            //$vendedor->empresa=$request->empresa;
            $vendedor->user_id = $request->user_id;
            $vendedor->save();
            return response()->json(['code' => 200, 'message' => 'Proveedor Creado Correctamente'], 200);
        }
    }

    public function show(String $idVendedor)
    {
        return Vendedor::where('user_id',$idVendedor)->get(); 
    }

    public function showCodVendedor(String $codVendedor,String $idVendedor){
        return Vendedor::where('codigo_vendedor',$codVendedor)
        ->where('user_id',$idVendedor)
        ->get()->first();
    }

    public function showProveedor(String $idVendedor){
        return Vendedor::where('id',$idVendedor)->get()->first();
    }


    public function update(Request $request, Vendedor $vendedor)
    {
        if($this->showCodVendedor($request->codigo_vendedor,$request->user_id)){
            $vendedor->tipo_identificador=$request->tipo_identificador;        
            $vendedor->numero_documento=$request->numero_documento;
            $vendedor->razon_social=Utils::replaceSpecialCharacters($request->razon_social);
            $vendedor->celular=$request->celular;
            $vendedor->correo=Utils::replaceSpecialCharacters($request->correo);
            $vendedor->codigo_vendedor=Utils::replaceSpecialCharacters($request->codigo_vendedor);        
            //$vendedor->empresa=$request->empresa;
            $vendedor->save();
            return response()->json(['code' => 200, 'message' => 'Proveedor Actualizado correctamente'], 200);            
        }else{
            return response()->json(['code' => 500, 'message' => 'Proveedor ya registrado'], 200);      
        }
    }

    public function destroy(Vendedor $vendedor)
    {
        $vendedor->delete();
        return response()->json(['message' => 'Vendedor eliminado correctamente'], 200);
    }
}
