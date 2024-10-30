<?php 

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Usuario;

class UserController extends Controller
{
    public function index()
    {        
        return User::first();
    }
    public function store(Request $request)
    {
        //registro en User
        $user = new User();
        $user->ruc = $request->ruc;
        $user->password = Hash::make($request->password);
        $user->user= $request->ruc;
        $user->correo=$request->correo;
        $user->fecha_registro=$request->fecha_registro;
        $user->fecha_expiracion=$request->fecha_expiracion;
        $user->save();

        //registro en Usuario
        $usuario=new Usuario();
        $usuario->ruc=$request->ruc;
        $usuario->password=Hash::make($request->password);
        $usuario->correo=$request->correo;
        $usuario->user=$request->ruc;        
        $usuario->permite_transacciones=true;
        $usuario->estado=true;
        $usuario->save();
        return $user;
    }

    public function show(User $user)
    {        
        return $user;
    }
    public function update(Request $request, User $user)
    {
        $user->ruc = $request->ruc;        
        $password = trim($request->pass);        
        if($password == null || $password == ""){
            $user->password = $user->password;
        }else{
            $user->password = Hash::make($password);
        }
        $user->user = $request->user;
        $user->correo = $request->correo;
        $user->fecha_registro = $request->fecha_registro;
        $user->fecha_expiracion = $request->fecha_expiracion;        
        
        $user->save();
    }
    public function destroy(User $user)
    {
    }

    public function showAllUsersIdentifier(){
        return User::all();
    }



}