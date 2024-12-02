<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cliente;
use App\Models\Recepcion;
use App\Models\Habitacion;
use App\Models\Reservacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservacionController extends Controller
{
    public function index()
    {
        $habitaciones = Habitacion::select("habitaciones.*", "niveles.nombre  as nivel", "categorias.nombre  as categoria")
            ->join("niveles", "niveles.id", "=", "habitaciones.niveles_id")
            ->join("categorias", "categorias.id", "=", "habitaciones.categorias_id")
            ->where("habitaciones.status", true)->where("habitaciones.hoteles_id", auth()->user()->hoteles_id)->get();
        $clientes = Cliente::where("status", true)->where("hoteles_id", auth()->user()->hoteles_id)->get();
        return view('reservaciones.index')->with("habitaciones", $habitaciones)->with("clientes", $clientes);
    }

    public function all()
    {

        $reservaciones = Reservacion::select("reservaciones.*", "habitaciones.nombre as habitacion", "clientes.nombre as cliente")
            ->where("reservaciones.status", true)
            ->where("reservaciones.hoteles_id", auth()->user()->hoteles_id)
            ->join("clientes", "clientes.id", "=", "reservaciones.clientes_id")
            ->join("habitaciones", "habitaciones.id", "=", "reservaciones.habitaciones_id")->get();
        $data = null;


        if ($reservaciones->count() > 0) {
            foreach ($reservaciones as $reservacion) {
                switch ($reservacion->estado) {
                    case 'sin_confirmar':
                        $color = "gray";
                        break;
                    case 'confirmada':
                        $color = "orange";
                        break;
                    case 'ingreso':
                        $color = "green";
                        break;
                    case 'no_ingreso':
                        $color = "red";
                        break;
                    case 'terminada':
                        $color = "black";
                        break;
                }




                $data[] = array(
                    'id'   => $reservacion->id,
                    'title'   => $reservacion->cliente . " Hab: " . $reservacion->habitacion,
                    'start'   => $reservacion->fecha_hora_entrada,
                    'end'   => $reservacion->fecha_hora_salida,
                    'color' => $color
                );
            }
        }
        return response()->json($data);
    }
    public function store(Request $request)
    {
        try {
            //Procedemos a realizar las validaciones correspondientes a las fechas de reservación por habitacion


            $fecha_hora_entrada = strtotime($request->fecha_hora_entrada . " 12:30:00");
            $fecha_hora_salida = strtotime($request->fecha_hora_salida . " 12:00:00");

            if ($fecha_hora_entrada >= $fecha_hora_salida) {
                return response()->json([
                    "result" => "error",
                    "message" => "La fecha de entrada no puede ser mayor a la fecha de salida."
                ]);
            }
            DB::enableQueryLog();



            if ($request->id == "") {
                $reservaciones_registradas = Reservacion::where(function ($query) use ($request) {
                    $query->where([
                        ['fecha_hora_entrada', '<=', $request->fecha_hora_entrada . " 12:30:00"],
                        ['fecha_hora_salida', ">=", $request->fecha_hora_entrada . " 12:30:00"]
                    ])
                        ->orWhere([
                            ['fecha_hora_entrada', '<=', $request->fecha_hora_salida . " 12:00:00"],
                            ['fecha_hora_salida', ">=", $request->fecha_hora_salida . " 12:00:00"]
                        ]);
                })
                    ->where([
                        ["status", true],
                        ["habitaciones_id", $request->habitaciones_id],
                        ["hoteles_id", auth()->user()->hoteles_id],
                        ["estado", "<>", "terminada"],
                        ["estado", "<>", "no_ingreso"]
                    ])->get();
            } else {
                $reservaciones_registradas = Reservacion::where(function ($query) use ($request) {
                    $query->where([
                        ['fecha_hora_entrada', '<=', $request->fecha_hora_entrada . " 12:30:00"],
                        ['fecha_hora_salida', ">=", $request->fecha_hora_entrada . " 12:30:00"]
                    ])
                        ->orWhere([
                            ['fecha_hora_entrada', '<=', $request->fecha_hora_salida . " 12:00:00"],
                            ['fecha_hora_salida', ">=", $request->fecha_hora_salida . " 12:00:00"]
                        ]);
                })
                    ->where([
                        ["status", true],
                        ["habitaciones_id", $request->habitaciones_id],
                        ["hoteles_id", auth()->user()->hoteles_id],
                        ["estado", "<>", "terminada"],
                        ["estado", "<>", "no_ingreso"],
                        ["id", "<>", $request->id]
                    ])->get();
            }

            $recepciones_registradas = Recepcion::where(function ($query) use ($request) {
                $query->where([
                    ['fecha_hora_entrada', '<=', $request->fecha_hora_entrada . " 12:30:00"],
                    ['fecha_hora_salida', ">=", $request->fecha_hora_entrada . " 12:30:00"]
                ])
                    ->orWhere([
                        ['fecha_hora_entrada', '<=', $request->fecha_hora_salida . " 12:00:00"],
                        ['fecha_hora_salida', ">=", $request->fecha_hora_salida . " 12:00:00"]
                    ]);
            })
                ->where([
                    ["status", true],
                    ["habitaciones_id", $request->habitaciones_id],
                    ["hoteles_id", auth()->user()->hoteles_id],
                    ["estado", "<>", "terminada"],
                    ["estado", "<>", "no_ingreso"]
                ])->get();


            //dd(DB::getQueryLog());

            if ($reservaciones_registradas->count() > 0 || $recepciones_registradas->count() > 0) {
                return response()->json([
                    "result" => "error",
                    "message" => "Las fechas de la reservación no están disponibles, coinciden con reservaciones o alojamientos ya registrados para esta habitación."
                ]);
            }

            DB::beginTransaction();
            //primero almacenamos la información del Cliente
            if ($request->clientes_id == "") {
                $cliente = Cliente::create([
                    "nombre" => $request->nombre,
                    "tipo_documento" => $request->tipo_documento,
                    "documento" => $request->documento,
                    "rfc" => $request->rfc,
                    "razon_social" => $request->razon_social,
                    "email" => $request->email,
                    "telefono" => $request->telefono,
                    "hoteles_id" => auth()->user()->hoteles_id,
                ]);
            } else {
                $cliente = Cliente::find($request->clientes_id)->update([
                    "nombre" => $request->nombre,
                    "tipo_documento" => $request->tipo_documento,
                    "documento" => $request->documento,
                    "rfc" => $request->rfc,
                    "razon_social" => $request->razon_social,
                    "email" => $request->email,
                    "telefono" => $request->telefono,
                ]);
            }

            if ($request->id == "") {
                $reservacion = Reservacion::create([
                    "fecha_hora_entrada" => $request->fecha_hora_entrada . " 12:30:00",
                    "fecha_hora_salida" => $request->fecha_hora_salida . " 12:00:00",
                    "clientes_id" => $request->clientes_id == "" ? $cliente->id : $request->clientes_id,
                    "habitaciones_id" => $request->habitaciones_id,
                    "hoteles_id" => auth()->user()->hoteles_id,
                    "descuento" => $request->descuento,
                    "cobro_extra" => $request->cobro_extra,
                    "adelanto" => $request->adelanto,
                    "total_pagar" => $request->total_pagar,
                    "metodo_pago" => $request->metodo_pago,
                    "observaciones" => $request->observaciones,
                    "estado" => $request->estado,
                    "users_id" => auth()->user()->id
                ]);
            } else {
                $reservacion = Reservacion::find($request->id)->update([
                    "fecha_hora_entrada" => $request->fecha_hora_entrada . " 12:30:00",
                    "fecha_hora_salida" => $request->fecha_hora_salida . " 12:00:00",
                    "clientes_id" => $request->clientes_id,
                    "habitaciones_id" => $request->habitaciones_id,
                    "descuento" => $request->descuento,
                    "cobro_extra" => $request->cobro_extra,
                    "adelanto" => $request->adelanto,
                    "total_pagar" => $request->total_pagar,
                    "metodo_pago" => $request->metodo_pago,
                    "observaciones" => $request->observaciones,
                    "estado" => $request->estado,
                ]);
            }

            //Si el status de la reservacion cambia a Ingreso, se genera el registro de alojamiento para su respectivo seguimiento.
            if ($request->estado == "ingreso") {
                //Verificamos si la reservacion ya se convirtió en alojamiento previamente
                $recepcion_e = Recepcion::where("reservaciones_id", $request->id == "" ? $reservacion->id : $request->id)->first();
                $habitacion = Habitacion::where("id", $request->habitaciones_id)->first();
                if ($recepcion_e == null) {
                    if ($habitacion->estado != "ocupada") {
                        $recepcion = Recepcion::create([
                            "fecha_hora_entrada" => $request->fecha_hora_entrada . " 12:30:00",
                            "fecha_hora_salida" => $request->fecha_hora_salida . " 12:00:00",
                            "clientes_id" => $request->clientes_id == "" ? $cliente->id : $request->clientes_id,
                            "habitaciones_id" => $request->habitaciones_id,
                            "hoteles_id" => auth()->user()->hoteles_id,
                            "descuento" => $request->descuento,
                            "cobro_extra" => $request->cobro_extra,
                            "adelanto" => $request->adelanto,
                            "total_pagar" => $request->total_pagar,
                            "metodo_pago" => $request->metodo_pago,
                            "observaciones" => $request->observaciones,
                            "estado" => $request->estado,
                            "users_id" => auth()->user()->id,
                            "reservaciones_id" => $request->id == "" ? $reservacion->id : $request->id
                        ]);
                        $habitacion->estado = "ocupada";
                        $habitacion->save();
                    } else {
                        DB::rollBack();
                        return response()->json([
                            "result" => "error",
                            "message" => "La Habitación está actualmente ocupada por otro Huésped diferente al que reservó!"]);
                    }
                }
            }
            DB::commit();
            return response()->json([
                "result" => "ok",
                "message" => "Reservación Almacenada Satisfactoriamente!"
            ]);
        } catch (Exception $ex) {
            //dd($ex);
            DB::rollback();
            return response()->json([
                "result" => "error",
                "message" => "Ocurrió un error al almacenar la Reservación!" . $ex
            ]);
        }
    }

    public function getinfo($id)
    {
        $reservacion = Reservacion::select("reservaciones.*", "reservaciones.estado  as estado_reservacion", "reservaciones.id as reservacion", "habitaciones.nombre as habitacion", "clientes.nombre as cliente", "clientes.*", "habitaciones.*")
            ->where("reservaciones.status", true)
            ->where("reservaciones.hoteles_id", auth()->user()->hoteles_id)
            ->join("clientes", "clientes.id", "=", "reservaciones.clientes_id")
            ->join("habitaciones", "habitaciones.id", "=", "reservaciones.habitaciones_id")
            ->where("reservaciones.id", $id)
            ->first();

        return response()->json([
            "result" => "ok",
            "reservacion" => $reservacion->toArray()
        ]);
    }

    public function showinfo($id)
    {
        $reservacion = Reservacion::select("reservaciones.*", "reservaciones.estado  as estado_reservacion", "reservaciones.id as reservacion", "habitaciones.nombre as habitacion", "clientes.nombre as cliente", "clientes.*", "habitaciones.*")
            ->where("reservaciones.status", true)
            ->where("reservaciones.hoteles_id", auth()->user()->hoteles_id)
            ->join("clientes", "clientes.id", "=", "reservaciones.clientes_id")
            ->join("habitaciones", "habitaciones.id", "=", "reservaciones.habitaciones_id")
            ->where("reservaciones.id", $id)
            ->first();
        return view("reservaciones.showinfo")->with("reservacion", $reservacion);
    }
}
