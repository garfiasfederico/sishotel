<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(){
        $categorias = Categoria::where("status",true)
                   ->where("hoteles_id",auth()->user()->hoteles_id)->paginate(5);
        return view("categorias.index")->with("categorias",$categorias);
    }

    public function add(Request $request){
        try{
            if($request->id==""){
                Categoria::create([
                    "nombre" => $request->nombre,
                    "hoteles_id" => auth()->user()->hoteles_id
                ]);
            }else{
                Categoria::find($request->id)->update([
                    "nombre" => $request->nombre
                ]);
            }
            return response()->json([
                "result"=>"ok",
                "message" => "Categoria Almacenada Satisfactoriamente"
            ]);
        }catch(Exception $ex){
            return response()->json([
                "result"=>"error",
                "message" => "Ocurrió un error al tratar de almacenar la categoria"
            ]);
        }

    }

    public function destroy(Request $request){
        try{
            Categoria::find($request->id)->delete();
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
