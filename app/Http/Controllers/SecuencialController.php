<?php

namespace App\Http\Controllers;

use App\Models\Secuencial;
use Illuminate\Http\Request;

class SecuencialController extends Controller
{
    public function index()
    {                
        return Secuencial::with(['DatosEmisores'=>function($query){
            $query->with('Usuarios');
        }])->get();            
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($this->showUniqueSecuencial($request->codigo_establecimiento,$request->punto_emision,$request->datos_emisores_id)){
            return response()->json(['code'=>500,'message'=>"Secuencial ya Registrado"],200);
        }else{
            $secuencial=new Secuencial();
            $secuencial->direccion_sucursal=$request->direccion_sucursal;
            $secuencial->punto_emision=$request->punto_emision;
            $secuencial->codigo_establecimiento=$request->codigo_establecimiento;        
            $secuencial->sec_ini_fact=$request->sec_ini_fact;
            $secuencial->sec_sig_fact=$request->sec_sig_fact;
            $secuencial->sec_ini_com_ret=$request->sec_ini_com_ret;
            $secuencial->sec_sig_com_ret=$request->sec_sig_com_ret;
            $secuencial->sec_ini_not_cred=$request->sec_ini_not_cred;
            $secuencial->sec_sig_not_cred=$request->sec_sig_not_cred;
            $secuencial->sec_ini_guia_rem=$request->sec_ini_guia_rem;
            $secuencial->sec_sig_guia_rem=$request->sec_sig_guia_rem;
            $secuencial->sec_ini_not_deb=$request->sec_ini_not_deb;
            $secuencial->sec_sig_not_deb=$request->sec_sig_not_deb;
            $secuencial->sec_ini_liq_comp=$request->sec_ini_liq_comp;
            $secuencial->sec_sig_liq_comp=$request->sec_sig_liq_comp;
            $secuencial->estado=$request->estado;        
            $secuencial->datos_emisores_id=$request->datos_emisores_id;
            $secuencial->save();
            return response()->json(['code' => 200, 'message' => 'Secuencial Creado correctamente'], 200);
        }
        
    }   


    public function show(Secuencial $secuencial)
    {
        $secuencial = Secuencial::with('DatosEmisores')->find($secuencial->id);
        $secuencial->datos_emisor=$secuencial->DatosEmisor;        
        return $secuencial;
    
    }
    public function showSecuencial(String $idSecuencial){
        return Secuencial::with('DatosEmisores')->where('id',$idSecuencial)->get()->first();
    }
    public function showValorSecuencialSiguiente($id){
        $secuencial = Secuencial::with('DatosEmisores')->find($id);        
        return $secuencial;
    }
    
    public function findByValueFactura(String $sec_sig_fact){
        $secuenciales = Secuencial::where('sec_sig_fact', $sec_sig_fact)->get()->first();
        return $secuenciales;
    }
    public function findByValueRetencion(String $sec_sig_ret){
        $secuenciales = Secuencial::where('sec_sig_com_ret', $sec_sig_ret)->get()->first();
        return $secuenciales;
    }
    public function findByValueNota(String $sec_sig_not){
        $secuenciales = Secuencial::where('sec_sig_not_cred', $sec_sig_not)->get()->first();
        return $secuenciales;
    }
    public function showUniqueSecuencial(String $codEstablecimiento,String $ptoEmision,String $datosEmisor){
        return Secuencial::where('codigo_establecimiento',$codEstablecimiento)
        ->where('punto_emision',$ptoEmision)
        ->where('datos_emisores_id',$datosEmisor)->get()->first();
    }
    public function findByUserInSecuencial(String $idUser) {
        return Secuencial::whereHas('DatosEmisores', function ($query) use ($idUser) {
            $query->where('usuarios_id', $idUser);
        })->with(['DatosEmisores' => function ($query) {
            $query->with('Usuarios');
        }])->get();
    }
 
    public function update(Request $request, Secuencial $secuencial)
    {
        $secuencial->direccion_sucursal=$request->direccion_sucursal;
        $secuencial->codigo_establecimiento=$request->codigo_establecimiento;        
        $secuencial->punto_emision=$request->punto_emision;        
        $secuencial->sec_ini_fact=$request->sec_ini_fact;
        $secuencial->sec_sig_fact=$request->sec_sig_fact;
        $secuencial->sec_ini_com_ret=$request->sec_ini_com_ret;
        $secuencial->sec_sig_com_ret=$request->sec_sig_com_ret;
        $secuencial->sec_ini_not_cred=$request->sec_ini_not_cred;
        $secuencial->sec_sig_not_cred=$request->sec_sig_not_cred;
        $secuencial->sec_ini_guia_rem=$request->sec_ini_guia_rem;
        $secuencial->sec_sig_guia_rem=$request->sec_sig_guia_rem;
        $secuencial->sec_ini_not_deb=$request->sec_ini_not_deb;
        $secuencial->sec_sig_not_deb=$request->sec_sig_not_deb;
        $secuencial->sec_ini_liq_comp=$request->sec_ini_liq_comp;
        $secuencial->sec_sig_liq_comp=$request->sec_sig_liq_comp;
        $secuencial->estado=$request->estado;
        $secuencial->datos_emisores_id=$request->datos_emisores_id;
        $secuencial->save();
        return $secuencial;
    }


    public function destroy(Secuencial $secuencial)
    {
        $secuencial->delete();        
        return response()->json(['message' => 'Secuencial eliminado correctamente'], 200);
    }

}
