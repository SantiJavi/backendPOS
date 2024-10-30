<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Utils\Utils;

class ClienteController extends Controller
{
    public function index()
    {
        return Cliente::orderBy('nombre','asc')->get();
    }
    public function store(Request $request)
    {
        if($this->showDocumento($request->numero_documento,$request->id_usuario)){
            return response()->json(['code' => 500, 'message' => 'Cliente ya registrado'], 200);      
        }else{
            $cliente=new Cliente();
            $cliente->tipo_identificador= $request->tipo_identificador;
            $cliente->numero_documento= $request->numero_documento;
            $cliente->nombre= Utils::replaceSpecialCharacters($request->nombre);
            $cliente->correo= Utils::replaceSpecialCharacters($request->correo);
            $cliente->telefono= $request->telefono;
            $cliente->direccion= Utils::replaceSpecialCharacters($request->direccion);
            $cliente->user_id = $request->id_usuario;        
            $cliente->save();            
            return response()->json(['code' => 200, 'message' => 'Cliente Creado correctamente'], 200);
        }

    }
    public function show(String $idUser)
    {
        return Cliente::where('user_id',$idUser)->get();
    }

    public function showDocumento(String $numDocCliente,String $idCliente){
        return Cliente::where('numero_documento',$numDocCliente)
        ->where('user_id',$idCliente)
        ->get()->first();
    }
    
    public function showCliente(String $idUser){
        return Cliente::where('id',$idUser)->get()->first();
    }

    public function update(Request $request, Cliente $cliente)
    {
        if($this->showDocumento($request->numero_documento,$request->user_id)){                                
            $cliente->tipo_identificador= $request->tipo_identificador;
            $cliente->numero_documento= $request->numero_documento;
            $cliente->nombre= Utils::replaceSpecialCharacters($request->nombre);
            $cliente->correo= Utils::replaceSpecialCharacters($request->correo);
            $cliente->telefono= $request->telefono;
            $cliente->direccion= Utils::replaceSpecialCharacters($request->direccion);        
            $cliente->save();
            return response()->json(['code' => 200, 'message' => 'Cliente Actualizado correctamente'], 200);            
        }else{
            return response()->json(['code'=>500,'message'=>'Cliente ya registrado'],200);    
        }
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();    
        return response()->json(['message' => 'Cliente eliminado correctamente'], 200);        
    }
}
