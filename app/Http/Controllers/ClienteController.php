<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    //
    public function index(){

        $clientes = Cliente::where("status",true)->where("hoteles_id",auth()->user()->hoteles_id)->paginate(5);
        return view("clientes.index")->with("clientes",$clientes);
    }

    public function store(Request $request){
        try{
            DB::beginTransaction();
            if($request->id ==""){
                $cliente = Cliente::create([
                    "nombre" => $request->nombre,
                    "documento" => $request->documento,
                    "email" => $request->email,
                    "telefono" => $request->telefono,
                    "tipo_documento" => $request->tipo_documento,
                    "rfc" => $request->rfc,
                    "razon_social" => $request->razon_social,
                    "hoteles_id" => auth()->user()->hoteles_id
                ]);

            }else{
                $cliente = Cliente::find($request->id)->update([
                    "nombre" => $request->nombre,
                    "documento" => $request->documento,
                    "email" => $request->email,
                    "telefono" => $request->telefono,
                    "tipo_documento" => $request->tipo_documento,
                    "rfc" => $request->rfc,
                    "razon_social" => $request->razon_social,
                ]);
            }
            DB::commit();

            return response()->json([
                "result"=>"ok",
                "message" => "Cliente almacenado satisfactoriamente!"
            ]);

        }catch(Exception $ex){
            DB::rollBack();
            return response()->json([
                "result"=>"ok",
                "message" => "Ocurrió un error al intentar almanenar el cliente, intente más tarde!"
            ]);
        }
    }

    public function destroy(Request $request){
        try{
            Cliente::where("id",$request->id)->update([
                "status" => 0
            ]);
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

    public function getinfo($id){
        $cliente = Cliente::find($id);
        return response()->json([
            "result" => "ok",
            "cliente" => $cliente
        ]);
    }
}
