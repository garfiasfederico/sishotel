<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;

class GeneralController extends Controller
{
    public function nopermitido(){
        return view("general.nopermitido");
    }

    public function config(){
        $hotel = Hotel::where("id",auth()->user()->hoteles_id)->first();
        return view('general.config')->with("hotel",$hotel);
    }

    public function configstore(Request $request){
        $this->validate($request, [
            "nombre" => 'required',
            "ubicacion" => 'required',
            "encargado" => 'required',
            "email" => 'required|email',
            "telefono" => 'required'
        ]);

        try{
            DB::beginTransaction();
            $reg = Hotel::find($request->id);
                //Borramos el logo cargado con anterioridad
                if(isset($nombreImagen) && $reg->logo!=null){
                    unlink(public_path('uploads/hoteles')."/".$reg->logo);
                }
            if ($request->logo) {
                $imagen = $request->file('logo');
                $nombreImagen = Str::uuid() . "." . $imagen->extension();
                $imagenPath_c = public_path('uploads/hoteles');
                $imagenServidor  = Image::make($imagen);
                $imagenServidor->fit(1000, 1000);

                $imagenPath = $imagenPath_c . "/" . $nombreImagen;
                $imagenServidor->save($imagenPath);
            }
            Hotel::find($request->id)->update([
                "nombre" => $request->nombre,
                "encargado" => $request->encargado,
                "email" => $request->email,
                "telefono" => $request->telefono,
                "ubicacion" => $request->ubicacion,
                "tipo_moneda" => $request->tipo_moneda,
                "logo" => $nombreImagen ?? $reg->logo ?? null
            ]);

            $hotel = Hotel::find($request->id);
            DB::commit();
            return view("general.config")->with("message","Información Actualizada Satisfactoriamente")->with("type","success")->with("hotel",$hotel);
        }catch(Exception $ex){
            DB::rollback();
            $hotel = Hotel::find($request->id);
            return view("general.config")->with("message","Ocurrió un error al tratar de almacenar la información")->with("type","success")->with("hotel",$hotel);
        }

    }

}
