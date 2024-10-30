<?php

namespace App\Http\Controllers;

use App\Models\CodigosRetencion;
use Illuminate\Http\Request;

class CodigosRetencionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CodigosRetencion::all();
    }

    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {           
         $codigosRetenciones =CodigosRetencion::where('codigo_retencion',$id)->get();        
        return $codigosRetenciones->first()->id;
    }
    public function showByID($id){
        $codigosRetenciones =CodigosRetencion::where('codigo_retencion',$id)->get();        
        return $codigosRetenciones->first()->id;
       
    }

    public function update(Request $request, CodigosRetencion $codigosRetencion)
    {
        
    }


    public function destroy(CodigosRetencion $codigosRetencion)
    {

    }
}
