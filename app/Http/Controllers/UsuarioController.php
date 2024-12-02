<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $hoteles = Hotel::where("status", true)->get();
        return view("usuarios.index")->with("hoteles", $hoteles);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => 'required',
            "email" => 'required|email|unique:users,email,'.$request->id,
            "password" => 'required',
            "cuenta" => 'required',
            "telefono" => 'required',
            "hoteles_id" => 'required',
        ]);

        try {
            if ($request->id == "") {
                $usuario = new User();
                $usuario->name = $request->name;
                $usuario->email = $request->email;
                $usuario->password = Hash::make($request->password);
                $usuario->password_enc = $request->password;
                $usuario->cuenta = $request->cuenta;
                $usuario->telefono = $request->telefono;
                $usuario->hoteles_id = $request->hoteles_id;
                $usuario->direccion = $request->direccion;
                $usuario->save();
                $usuario->assignRole("administrador");
            } else {

                $usuario = User::find($request->id);
                $usuario->name = $request->name;
                $usuario->email = $request->email;
                $usuario->password = Hash::make($request->password);
                $usuario->password_enc = $request->password;
                $usuario->cuenta = $request->cuenta;
                $usuario->telefono = $request->telefono;
                $usuario->hoteles_id = $request->hoteles_id;
                $usuario->direccion = $request->direccion;
                $usuario->save();
            }
            $resultado = true;
            $nombre = $request->name;
            return redirect()->route('usuarios.list');
        } catch (Exception $ex) {
            $resultado = false;
            $nombre = "";
        }
    }
    public function edit($id)
    {
        $hoteles = Hotel::where("status", true)->get();
        $usuario = User::find($id);
        return view("usuarios.index")->with("hoteles", $hoteles)->with("usuario", $usuario);
    }

    public function list()
    {
        $usuarios = User::whereNotNull("hoteles_id")->paginate(10);
        return view("usuarios.list")->with("usuarios", $usuarios);
    }

    public function destroy(Request $request)
    {
        //dd($request->id);
        try {
            User::where("id", $request->id)->delete();
            return response()->json([
                "result" => "ok",
                "message" => "Registro Eliminado Satifactoriamente",

            ]);
        } catch (Exception $ex) {
            return response()->json([
                "result" => "error",
                "message" => "Error " . $ex

            ]);
        }
    }

    public function updatestatus(Request $request)
    {

        try {
            User::where("id", $request->id)->update([
                "status" => $request->status
            ]);
            return response()->json([
                "result" => "ok",
                "message" => "Status Actualizado Satifactoriamente",
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "result" => "error",
                "message" => "Error " . $ex

            ]);
        }
    }

    public function usuariosinternos(){
        $usuarios = User::where("hoteles_id",auth()->user()->hoteles_id)->where("id","<>",auth()->user()->id)->paginate(10);
        //dd($usuarios);
        return view("usuarios.usuariosinternos")->with("usuarios",$usuarios);
    }

    public function storeusuariosinternos(Request $request){
        try {
            if ($request->id == "") {
                $usuario = new User();
                $usuario->name = $request->name;
                $usuario->email = $request->email;
                $usuario->password = Hash::make($request->password);
                $usuario->password_enc = $request->password;
                $usuario->cuenta = $request->cuenta;
                $usuario->telefono = $request->telefono;
                $usuario->hoteles_id = auth()->user()->hoteles_id;
                $usuario->save();
                $usuario->assignRole("empleado");
            } else {

                $usuario = User::find($request->id);
                $usuario->name = $request->name;
                $usuario->email = $request->email;
                $usuario->password = Hash::make($request->password);
                $usuario->password_enc = $request->password;
                $usuario->cuenta = $request->cuenta;
                $usuario->telefono = $request->telefono;
                $usuario->save();
            }

            return response()->json([
                "result" => "ok",
                "message" => "Usuario Interno Almacenado Satisfactoriamente!"
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "result" => "erro",
                "message" => "Ocurrió un error al trata de almacenar al Usuario Interno, intente más tarde!". $ex->getMessage()
            ]);
        }


    }

    public function destroyusuariosinternos(Request $request){
        try {
            User::where("id", $request->id)->update([
                "status" => 0
            ]);
            return response()->json([
                "result" => "ok",
                "message" => "Registro Eliminado Satifactoriamente",

            ]);
        } catch (Exception $ex) {
            return response()->json([
                "result" => "error",
                "message" => "Ocurrió un error al eliminar el usuario"

            ]);
        }
    }
}
