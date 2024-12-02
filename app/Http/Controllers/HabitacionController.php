<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Habitacion;
use App\Models\Nivel;
use Exception;
use Illuminate\Http\Request;

class HabitacionController extends Controller
{
    public function index()
    {
        $habitaciones = Habitacion::select("habitaciones.*","niveles.nombre  as nivel","categorias.nombre  as categoria")
                    ->join("niveles","niveles.id","=","habitaciones.niveles_id")
                    ->join("categorias","categorias.id","=","habitaciones.categorias_id")
                    ->where("habitaciones.status", true)->where("habitaciones.hoteles_id", auth()->user()->hoteles_id)->paginate(10);
        $niveles = Nivel::where("status", true)->where("hoteles_id", auth()->user()->hoteles_id)->get();
        $categorias = Categoria::where("status", true)->where("hoteles_id", auth()->user()->hoteles_id)->get();
        return view("habitaciones.index")->with("habitaciones", $habitaciones)->with("niveles", $niveles)->with('categorias', $categorias);
    }

    public function store(Request $request)
    {
        try {
            if ($request->id == "") {
                Habitacion::create([
                    "nombre" => $request->nombre,
                    "precio" => $request->precio,
                    "tarifa" => $request->tarifa,
                    "detalles" => $request->detalles,
                    "niveles_id" => $request->niveles_id,
                    "categorias_id" => $request->categorias_id,
                    "hoteles_id" => auth()->user()->hoteles_id
                ]);
            } else {
                Habitacion::find($request->id)->update([
                    "nombre" => $request->nombre,
                    "precio" => $request->precio,
                    "tarifa" => $request->tarifa,
                    "detalles" => $request->detalles,
                    "niveles_id" => $request->niveles_id,
                    "categorias_id" => $request->categorias_id,
                ]);
            }
            return response()->json([
                "result" => "ok",
                "message" => "Habitación Almacenada Satisfactoriamente"
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "result" => "error",
                "message" => "Ocurrió un error al tratar de almacenar la habitación".$ex
            ]);
        }
    }

    public function destroy(Request $request)
    {
        try{
            Habitacion::find($request->id)->delete();
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
