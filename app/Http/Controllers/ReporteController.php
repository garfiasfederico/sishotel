<?php

namespace App\Http\Controllers;

use Excel;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Recepcion;
use App\Models\Habitacion;
use Illuminate\Http\Request;
use App\Exports\ReporteAlojamientos;

class ReporteController extends Controller
{
    public function index()
    {
        $alojamientos = Recepcion::select("alojamientos.*", "habitaciones.nombre as habitacion", "clientes.nombre as cliente")
            ->join("habitaciones", "habitaciones.id", "=", "alojamientos.habitaciones_id")
            ->join("clientes", "clientes.id", "=", "alojamientos.clientes_id")
            ->where("alojamientos.status", true)
            ->where("alojamientos.hoteles_id", auth()->user()->hoteles_id)
            ->where("alojamientos.estado", "=", "terminada")
            ->get();

        $usuarios = User::where("status",true)->where("hoteles_id",auth()->user()->hoteles_id)->get();
        return view("reportes.index")->with("alojamientos",$alojamientos)->with("usuarios",$usuarios);
    }

    public function filtering(Request $request){
        $alojamientos = Recepcion::select("alojamientos.*", "habitaciones.nombre as habitacion", "clientes.nombre as cliente")
            ->join("habitaciones", "habitaciones.id", "=", "alojamientos.habitaciones_id")
            ->join("clientes", "clientes.id", "=", "alojamientos.clientes_id");

        if($request->fecha_inicial!=null){
            $alojamientos->where("fecha_hora_entrada",">=",$request->fecha_inicial." 00:00:00");
        }

        if($request->fecha_final!=null){
            $alojamientos->where("fecha_hora_entrada","<=",$request->fecha_final." 23:59:59");
        }

        if($request->responsable!=null){
            $alojamientos->where("users_id","=",$request->responsable);
        }

        $alojamientos->where("alojamientos.hoteles_id","=",auth()->user()->hoteles_id);
        $alojamientos->where("alojamientos.estado","=","terminada");

        return view("reportes.filtering")->with("alojamientos",$alojamientos->get())->with("fecha_inicial",$request->fecha_inicial)->with("fecha_final",$request->fecha_final)->with("responsable",$request->responsable);
    }

    public function filteringexcel(Request $request){
        try{
            return Excel::download(new ReporteAlojamientos($request->fecha_inicial,$request->fecha_final,$request->responsable), 'alojamientos'.date('YmdHis').'.xlsx');
        }catch(Exception $ex){
           dd($ex);
        }
    }

    public function getinfoalojamiento($id){
        $recepcion = Recepcion::find($id);
        $habitacion = Habitacion::select("habitaciones.*", "habitaciones.nombre  as habitacion", "niveles.nombre  as nivel", "categorias.nombre  as categoria")
            ->join("niveles", "niveles.id", "=", "habitaciones.niveles_id")
            ->join("categorias", "categorias.id", "=", "habitaciones.categorias_id")->where("habitaciones.id", $recepcion->habitaciones_id)->first();
        $clientes = Cliente::where("status", true)->where("hoteles_id", auth()->user()->hoteles_id)->get();
        $cliente = Cliente::where("id", $recepcion->clientes_id)->first();
        //dd($habitacion);
        return view("reportes.infoalojamiento")->with("habitacion", $habitacion)->with("clientes", $clientes)->with("recepcion", $recepcion)->with("cliente", $cliente);
    }
}
