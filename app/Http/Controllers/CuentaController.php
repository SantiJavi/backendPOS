<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;
use App\Models\Venta;
use App\Http\Controllers\DetalleVentaController;

class CuentaController extends Controller
{

    public function index()
    {
        return Cuenta::all();
    }

    public function store(Request $request)
    {
        $cuenta = new Cuenta();
        $cuenta->fecha_pago = $request->fecha_pago;
        $cuenta->total_cuenta = $request->total;
        $cuenta->total_pagado = $request->valor_recibido;
        $cuenta->saldo = $request->saldo;
        $cuenta->cliente_id = $request->clienteId;
        //actualizar fechas registros        
        $cuenta->save();
        $this->updateFechas($request->clienteId,$request->fecha_pago,$cuenta->id);
        return "Pago Registrado Correctamente";
    }

    public function show(String $idCliente)
    {
        $maximaFecha = Cuenta::max('fecha_pago');
        $maximoRegistro = Cuenta::max('id');
        return Cuenta::where('cliente_id',$idCliente)            
            ->where('fecha_pago',$maximaFecha)
            ->where('id',$maximoRegistro)
            ->first();
    }
    public function showCuenta(String $id){
        return Cuenta::where('cliente_id',$id)->get();
    }

    public function update(Request $request, Cuenta $cuenta)
    {
        //
    }

    public function destroy(Cuenta $cuenta)
    {
        //
    }

    public function calcularCuenta(String $clienteId){                
        $totalCalculado = Venta::where('cliente_id',$clienteId)
        ->whereNull('fecha_pago')
        ->where('tipo_pago','credito')
        ->sum('total_grabado');             
        $detalleController = new DetalleVentaController();
        $detalleCuenta=$detalleController->consultaDetalleCuentaActual($clienteId);     
        $saldoAnterior = $this->show($clienteId)!=null ? $this->show($clienteId)->saldo : 0 ;        
        return response()->json([
            'total' => $totalCalculado,            
            'saldo_anterior'=> $saldoAnterior,
            'detalleCuenta' => $detalleCuenta
        ]);                
    }
    public function updateFechas(String $clienteId,String $fechaPago, String $idCuenta){
        $cuentas = $this->searchCuentaDeuda($clienteId);        
        foreach ($cuentas as $cuenta){
            $cuenta->fecha_pago = $fechaPago;
            $cuenta->cuenta_id = $idCuenta;
            $cuenta->save();                       
        }
    }
    public function searchCuentaDeuda(String $clienteId){
        return Venta::where('tipo_pago','credito')
        ->where('cliente_id',$clienteId)
        ->whereNull('fecha_pago')
        ->get();        
    }

    public function cuentasPendientes(){
        Cuenta::where('cliente_id',4)->get();
        //->selectRaw('cliente_id,sum(saldo)') 
        //->groupBy('cliente_id')
        //->havingRaw('SUM(saldo) != 0')
        //->get();
    }

}
