<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(){
        $productos = Producto::where("status",true)->where("hoteles_id",auth()->user()->hoteles_id)->paginate(10);
        return view("productos.index")->with("productos",$productos);

    }
    public function store(Request $request){
        try {
            if ($request->id == "") {
                Producto::create([
                    "nombre" => $request->nombre,
                    "tipo" => $request->tipo,
                    "precio_unitario" => $request->precio_unitario,
                    "hoteles_id" => auth()->user()->hoteles_id
                ]);
            } else {
                Producto::find($request->id)->update([
                    "nombre" => $request->nombre,
                    "tipo" => $request->tipo,
                    "precio_unitario" => $request->precio_unitario
                ]);
            }
            return response()->json([
                "result" => "ok",
                "message" => "Producto Almacenada Satisfactoriamente"
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "result" => "error",
                "message" => "Ocurrió un error al tratar de almacenar el Producto".$ex
            ]);
        }

    }

    public function destroy(Request $request){
        try{
            Producto::find($request->id)->delete();
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

    public function getproductosbynombre($nombre){
        $productos = Producto::where("nombre","like","%".$nombre."%")
                ->where("status",true)
                ->where("hoteles_id",auth()->user()->hoteles_id)->get();
        return view("ventas.getproductos")->with("productos",$productos);
    }

    public function getrowproducto($id){
        $producto = Producto::where("id",$id)
                ->where("status",true)
                ->where("hoteles_id",auth()->user()->hoteles_id)->first();

        return response()->json([
            'result' => "ok",
            'producto' => $producto->toArray(),
        ]);
        //return view("ventas.getrowproducto")->with("producto",$producto);
    }
}
