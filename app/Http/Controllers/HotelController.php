<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Faker\Core\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Image;

class HotelController extends Controller
{
    public function index(): View
    {
        return view("hoteles.index");
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "nombre" => 'required',
            "encargado" => 'required',
            "email" => 'required|email',
            "telefono" => 'required'
        ]);

        try {

            if ($request->logo) {
                $imagen = $request->file('logo');
                $nombreImagen = Str::uuid() . "." . $imagen->extension();
                $imagenPath_c = public_path('uploads/hoteles');
                $imagenServidor  = Image::make($imagen);
                $imagenServidor->fit(1000, 1000);

                $imagenPath = $imagenPath_c . "/" . $nombreImagen;
                $imagenServidor->save($imagenPath);
            }

            if ($request->id == "") {
                Hotel::create([
                    "nombre" => $request->nombre,
                    "encargado" => $request->encargado,
                    "email" => $request->email,
                    "telefono" => $request->telefono,
                    "ubicacion" => $request->ubicacion,
                    "tipo_moneda" => $request->tipo_moneda,
                    "logo" => $nombreImagen ?? null
                ]);
            }else{
                $reg = Hotel::find($request->id);
                //Borramos el logo cargado con anterioridad
                if(isset($nombreImagen) && $reg->logo!=null){
                    unlink(public_path('uploads/hoteles')."/".$reg->logo);
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
            }
            $resultado = true;
            $nombre = $request->nombre;
            return redirect()->route('hoteles.list');
        } catch (Exception $ex) {
            $resultado = false;
            $nombre = "";
        }
        return view('hoteles.index')->with("resultado", $resultado)->with("nombre", $nombre);
    }

    public function list()
    {
        $hoteles = Hotel::where("status", true)->paginate(5);
        return view("hoteles.list")->with("hoteles", $hoteles);
    }

    public function edit($id)
    {
        $hotel = Hotel::find($id);
        return view("hoteles.index")->with("hotel", $hotel);
    }

    public function destroy(Request $request)
    {

        Hotel::find($request->id)->delete();

        return response()->json([
            "result"=>"ok",
            "message"=>"Registro Eliminado Satifactoriamente",

        ]);
    }
}
