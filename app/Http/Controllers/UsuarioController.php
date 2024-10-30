<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;


class UsuarioController extends Controller
{
    public function index()
    {
        return Usuario::all()->each(function($image){
            $image->url = url($image->logo);
            $image->url_firma = url($image->url_firma);
        });
    }
    public function store(Request $request)
    {
        $usuario=new Usuario();
        $usuario->ruc=$request->ruc_root;
        $usuario->password=Hash::make($request->modelo['password']);        
        $usuario->correo=$request->modelo['correo'];        
        $usuario->logo=$request->modelo['logo'];
        $usuario->user=$request->ruc_root;        
        $usuario->permite_transacciones=$request->modelo['permite_transacciones'];
        $usuario->estado=$request->modelo['estado'];
        $usuario->save(); 
        
        //register User
        $user = new User();
        $user->ruc = $request->ruc_root;
        $user->password = Hash::make($request->modelo['password']);        
        $user->user= $request->ruc_root;
        $user->correo= $request->modelo['correo'];
        $user->fecha_registro= $request->fecha_registro;
        $user->fecha_expiracion= $request->fecha_expiracion;
        $user->save();

        return $usuario;
    }
    public function show(Usuario $usuario)
    {
        //return [$usuario];
        return Usuario::where('id',$usuario->id )->get()->each(function($image){
            $image->url = url($image->logo);
            $image->url_firma = url($image->url_firma);

        });
    }
    public function showById($id){
        return Usuario::where('id',$id)->get();
    }

    public function update(Request $request, Usuario $usuario)
    {
        $usuario->ruc=$request->ruc;        
        if(isset($request->password)){
            if(!empty($request->password)){
                $usuario->password= Hash::make($request->password);
            }
        }        
        $usuario->nombre=$request->nombre;
        $usuario->correo=$request->correo;
        $usuario->fecha_registro=$request->fecha_registro;
        $usuario->fecha_expiracion=$request->fecha_expiracion;
        $usuario->logo=$request->logo;
        $usuario->user=$request->user;
        //$usuario->cod_estab=$request->cod_estab;
        //$usuario->punto_emision=$request->punto_emision;
        $usuario->permite_transacciones=$request->permite_transacciones;
        $usuario->estado=$request->estado;
        $usuario->save(); 
        return $usuario;
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }

    public function login(LoginFormRequest $request)
    {               
        if(Auth::attempt(['user'=>$request->user,'password'=>$request->password],false)){
            $usuario  = Auth::user();
            return $usuario;            
        }else{
            return response()->json(['errors'=>['login'=>['Los Datos no son Validos']]]);
        }        
    }

    public function changePassword(Request $request, Usuario $usuario){        
        $usuario = Usuario::where('ruc',$request->ruc)->get()->first();
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        $user = User::where('ruc',$request->ruc)->get()->first();
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['message' => 'Contraseña Actualizada'], 200);        
    }

    public function updatePassword(Request $request, Usuario $usuario){
        $usuario = Usuario::where('ruc',$request->ruc)->get()->first();        
        $usuario->password_firma = $request->password_firma;
        $usuario->estado_firma = $request->estado_firma;
        $usuario->save();
        return response()->json(['message' => 'Datos de Firma Actualizados'], 200);        
    }

    public function uploadImageFile(Request $request,$id){      
        if($this->isImage($request)){            
            $file = $request->file('file')->store('public/imagenes/');
            $url = Storage::url($file);
            $this->saveUrlImage($id,$url);            
            return response()->json(['success' => 'Logo cargado correctamente'], 200);
        }else{            
            return response()->json(['error' => 'Tipo de archivo no permitido'], 400);
        }            
    }

    public function uploadSign(Request $request, $id){                        
        if($this->isSing($request)){
            $file = $request->file('file');
            $path = $file->storeAs('public/firmas', $this->renameSing($request));
            $url = Storage::url($path);
            $this->saveUrlFirma($id,$url);
            return response()->json(['success' => 'Firma cargada correctamente'], 200);
        }else{
            return response()->json(['error' => 'Tipo de archivo no permitido'], 400);
        }        
    }

    public function getSingforId($id){        
        $usuario = $this->showById($id)[0];        
        $partesUrl = explode("/",$usuario->url_firma);
        $filename = $partesUrl[3];
        $path = storage_path('app/public/firmas/' . $filename);
        if (!file_exists($path)) {
        return response()->json(['message' => 'File not found'], 404);
        }
        return  file_get_contents($path);    
    }


    public function isSing(Request $request){
        $file = $request->file('file');        
        $mimeType = $file->getClientMimeType();
        echo "Fun: ".$mimeType;
        return $mimeType == 'application/x-pkcs12' ? true : false;
    }

    public function renameSing(Request $request){
        $file = $request->file('file');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);        
        return $originalName . '.p12';
    }

    public function isImage(Request $request){
        $file = $request->file('file');
        $mimeType = $file->getClientMimeType();
        return $mimeType == 'image/png' || $mimeType == 'image/jpg' || $mimeType == 'image/jpeg' ? true : false;        
    }

    public function saveUrlImage($id,$url){
        $usuario = $this->showById($id)[0];            
        $usuario->logo = $url;
        $usuario->save();     
    }
    public function saveUrlFirma($id,$url){
        $usuario = $this->showById($id)[0];
        $usuario->url_firma = $url;
        $usuario->save();
    }


}
