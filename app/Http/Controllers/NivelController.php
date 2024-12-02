<?php

namespace App\Http\Controllers;

use App\Models\Nivel;
use Illuminate\Http\Request;

class NivelController extends Controller
{
    public function index(){
        $niveles = Nivel::where("status",true)
                   ->where("hoteles_id",auth()->user()->hoteles_id)->paginate(5);
        return view("niveles.index")->with("niveles",$niveles);
    }

    public function add(Request $request){
        try{
            if($request->id==""){
                Nivel::create([
                    "nombre" => $request->nombre,
                    "hoteles_id" => auth()->user()->hoteles_id
                ]);
            }else{
                Nivel::find($request->id)->update([
                    "nombre" => $request->nombre
                ]);
            }
            return response()->json([
                "result"=>"ok",
                "message" => "Nivel Almacenado Satisfactoriamente"
            ]);
        }catch(Exception $ex){
            return response()->json([
                "result"=>"error",
                "message" => "Ocurrió un error al tratar de almacenar el nivel"
            ]);
        }

    }

    public function destroy(Request $request){
        try{
            Nivel::find($request->id)->delete();
            return response()->json([
                "result"=>"ok",
                "message"=>"Registro Eliminado Satifactoriamente",

            ]);
        }catch(Exception $ex){
            return response()->json([
                "result"=>"error",
                "message"=>"Ocurrió un error al intentar eliminar el registro",

            ]);
        }

    }
}
