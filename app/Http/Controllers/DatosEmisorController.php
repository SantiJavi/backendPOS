<?php

namespace App\Http\Controllers;

use App\Models\DatosEmisor;
use Illuminate\Http\Request;
use App\Utils\Utils;

class DatosEmisorController extends Controller
{
    public function index()
    {
        return DatosEmisor::with(['Usuarios'])->get();        
    }
    public function store(Request $request)
    {
        $datos=new DatosEmisor();
        $datos->nombre_comercial=Utils::replaceSpecialCharacters($request->nombre_comercial);
        $datos->razon_social=Utils::replaceSpecialCharacters($request->razon_social);
        $datos->direccion=Utils::replaceSpecialCharacters($request->direccion);
        $datos->email=Utils::replaceSpecialCharacters($request->email);
        $datos->lleva_contabilidad=$request->lleva_contabilidad;
        $datos->ambiente=$request->ambiente;
        $datos->contribuyente_retencion=$request->contribuyente_retencion;
        $datos->agente_retencion=$request->agente_retencion;
        //$datos->activar_regimen=$request->activar_regimen;
        $datos->usuarios_id=$request->usuarios_id;
        $datos->save();
        return $datos;
    }

    public function show($idUser)
    {
        $datosEmisor = DatosEmisor::with('usuarios')->where('usuarios_id', $idUser)->firstOrFail();
        return [$datosEmisor];
    }

    public function showEmisor($idEmisor){
        return DatosEmisor::where('id',$idEmisor)->get()->first();
    }

    public function update(Request $request, DatosEmisor $datosEmisor)
    {
        $datosEmisor->nombre_comercial=Utils::replaceSpecialCharacters($request->nombre_comercial);
        $datosEmisor->razon_social=Utils::replaceSpecialCharacters($request->razon_social);
        $datosEmisor->direccion=Utils::replaceSpecialCharacters($request->direccion);
        $datosEmisor->email=Utils::replaceSpecialCharacters($request->email);
        $datosEmisor->lleva_contabilidad=$request->lleva_contabilidad;
        $datosEmisor->ambiente=$request->ambiente;
        $datosEmisor->contribuyente_retencion=$request->contribuyente_retencion;
        $datosEmisor->agente_retencion=$request->agente_retencion;
        //$datosEmisor->activar_regimen=$request->activar_regimen;
        $datosEmisor->usuarios_id=$request->usuarios_id;
        $datosEmisor->save();
        return $datosEmisor;
    }

    public function destroy(DatosEmisor $datosEmisor)
    {
        $datosEmisor->delete();
        return response()->json(['message' => 'Datos Emisor eliminado correctamente'], 200);        
    }
}
