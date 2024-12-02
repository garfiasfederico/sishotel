<?php

namespace App\Http\Controllers;

use App\Models\Nivel;
use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\Recepcion;
use App\Models\Reservacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class RecepcionController extends Controller
{
    public function index()
    {
        $niveles = Nivel::where("status", true)->where("hoteles_id", auth()->user()->hoteles_id)->get();
        return view("recepciones.index")->with("niveles", $niveles);
    }

    public function add($habitaciones_id)
    {
        $habitacion = Habitacion::select("habitaciones.*", "habitaciones.nombre  as habitacion", "niveles.nombre  as nivel", "categorias.nombre  as categoria")
            ->join("niveles", "niveles.id", "=", "habitaciones.niveles_id")
            ->join("categorias", "categorias.id", "=", "habitaciones.categorias_id")->where("habitaciones.id", $habitaciones_id)->first();
        $clientes = Cliente::where("status", true)->where("hoteles_id", auth()->user()->hoteles_id)->get();
        //dd($habitacion);
        return view("recepciones.add")->with("habitacion", $habitacion)->with("clientes", $clientes);
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

            //Validamos del lado de las resevaciones que no haya fecha ya reservadas y que estén coincidiendo con las fechas de alojamiento

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
                    ["estado", "<>", "ingreso"]
                ])->get();


            // Validamos si existe una recepción previa hecha en las fechas solicitadas
            if ($request->id == "") {
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
            } else {
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
                        ["estado", "<>", "no_ingreso"],
                        ["id", "<>", $request->id]
                    ])->get();
            }

            //dd(DB::getQueryLog());

            if ($reservaciones_registradas->count() > 0 || $recepciones_registradas->count() > 0) {
                return response()->json([
                    "result" => "error",
                    "message" => "Las fechas indicadas en la recepción no están disponibles, coinciden con reservaciones o recepciones ya registradas con anterioridad."
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
                $reservacion = Recepcion::create([
                    "fecha_hora_entrada" => $request->fecha_hora_entrada . " 12:30:00",
                    "fecha_hora_salida" => $request->fecha_hora_salida . " 12:00:00",
                    "clientes_id" => $request->clientes_id == "" ? $cliente->id : $request->clientes_id,
                    "habitaciones_id" => $request->habitaciones_id,
                    "hoteles_id" => auth()->user()->hoteles_id,
                    "descuento" => $request->descuento,
                    "cobro_extra" => $request->cobro_extra,
                    "adelanto" => $request->adelanto,
                    "total_pagar" => $request->total,
                    "metodo_pago" => $request->metodo_pago,
                    "observaciones" => $request->observaciones,
                    "estado" => "ingreso",
                    "users_id" => auth()->user()->id
                ]);
            } else {
                $reservacion = Recepcion::find($request->id)->update([
                    "fecha_hora_entrada" => $request->fecha_hora_entrada . " 12:30:00",
                    "fecha_hora_salida" => $request->fecha_hora_salida . " 12:00:00",
                    "clientes_id" => $request->clientes_id == "" ? $cliente->id : $request->clientes_id,
                    "habitaciones_id" => $request->habitaciones_id,
                    "descuento" => $request->descuento,
                    "cobro_extra" => $request->cobro_extra,
                    "adelanto" => $request->adelanto,
                    "total_pagar" => $request->total,
                    "metodo_pago" => $request->metodo_pago,
                    "observaciones" => $request->observaciones,
                    "estado" => "ingreso",
                ]);
            }
            Habitacion::where("id", $request->habitaciones_id)->update([
                "estado" => "ocupada"
            ]);
            DB::commit();
            return response()->json([
                "result" => "ok",
                "message" => "Recepción registrada satisfactoriamente!"
            ]);
        } catch (Exception $ex) {
            dd($ex);
            DB::rollback();
            return response()->json([
                "result" => "error",
                "message" => "Ocurrió un error al registrar la recepción!"
            ]);
        }
    }

    public function edit($id)
    {
        $recepcion = Recepcion::find($id);
        $habitacion = Habitacion::select("habitaciones.*", "habitaciones.nombre  as habitacion", "niveles.nombre  as nivel", "categorias.nombre  as categoria")
            ->join("niveles", "niveles.id", "=", "habitaciones.niveles_id")
            ->join("categorias", "categorias.id", "=", "habitaciones.categorias_id")->where("habitaciones.id", $recepcion->habitaciones_id)->first();
        $clientes = Cliente::where("status", true)->where("hoteles_id", auth()->user()->hoteles_id)->get();
        $cliente = Cliente::where("id", $recepcion->clientes_id)->first();
        //dd($habitacion);
        return view("recepciones.add")->with("habitacion", $habitacion)->with("clientes", $clientes)->with("recepcion", $recepcion)->with("cliente", $cliente);
    }

    public function salidas()
    {
        $niveles = Nivel::where("status", true)->where("hoteles_id", auth()->user()->hoteles_id)->get();
        $alojamientos = Recepcion::select("alojamientos.*","habitaciones.nombre as habitacion","clientes.nombre as cliente")
                        ->where("alojamientos.hoteles_id",auth()->user()->hoteles_id)
                        ->join("habitaciones","habitaciones.id","=","alojamientos.habitaciones_id")
                        ->join("clientes","clientes.id","=","alojamientos.clientes_id")
                        ->get();
        return view("recepciones.salidas")->with("niveles", $niveles)->with("alojamientos",$alojamientos);
    }

    public function salida($id)
    {
        $recepcion = Recepcion::find($id);
        $habitacion = Habitacion::select("habitaciones.*", "habitaciones.nombre  as habitacion", "niveles.nombre  as nivel", "categorias.nombre  as categoria")
            ->join("niveles", "niveles.id", "=", "habitaciones.niveles_id")
            ->join("categorias", "categorias.id", "=", "habitaciones.categorias_id")->where("habitaciones.id", $recepcion->habitaciones_id)->first();
        $clientes = Cliente::where("status", true)->where("hoteles_id", auth()->user()->hoteles_id)->get();
        $cliente = Cliente::where("id", $recepcion->clientes_id)->first();
        //dd($habitacion);
        return view("recepciones.salida")->with("habitacion", $habitacion)->with("clientes", $clientes)->with("recepcion", $recepcion)->with("cliente", $cliente);
    }

    public function terminar(Request $request)
    {
        //Analizamos la información de terminación
        try {
            DB::beginTransaction();
            $recepcion = Recepcion::find($request->id);
            if ($recepcion != null) {
                $recepcion->mora = $request->mora;
                $recepcion->pago  = $request->pago;
                $recepcion->metodo_pago  = $request->metodo_pago;
                $recepcion->estado  = "terminada";
                $recepcion->save();

                $habitacion = Habitacion::where("id", $recepcion->habitaciones_id)->update([
                    "estado" => "libre"
                ]);

                if ($recepcion->reservaciones_id != null) {

                    $reservacion = Reservacion::find($recepcion->reservaciones_id);
                    $reservacion->estado = "terminada";
                    $reservacion->save();
                }
            }
            DB::commit();
            return response()->json([
                "result" => "ok",
                "id" => $recepcion->id,
                "message" => "Alojamiento culminado satisfactoriamente y la habitación fue liberada correctamente"
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                "result" => "error",
                "message" => "Ocurrión un error al tratar de culminar el Alojamiento" . $ex
            ]);
        }
    }

    public function imprime($id)
    {
        PDF::setHeaderCallback(function ($pdf) {
            $style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(240, 240, 240));
            $pdf->setFont('helvetica', 'B', 8);
            $pdf->setY(2);
            $pdf->Image('images/logo.png', '', '', 10, 10, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $pdf->setY(5);
            $pdf->setX(20);
            $pdf->Cell(0, 10, auth()->user()->hotel->nombre, 0, false, 'L', 0, '', 0, false, 'M', 'M');
            $pdf->setFont('helvetica', 'B', 6);
            $pdf->setY(8);
            $pdf->setX(20);
            $pdf->Cell(0, 10, 'Nota de Alojamiento', 0, false, 'L', 0, '', 0, false, 'M', 'M');
            $pdf->setY(10);
            //$pdf->writeHTML("<hr style='width:200px;'>", true, false, false, false, '');
            $pdf->SetDrawColor('12','15','220');
            $pdf->Line(18,10,200,10,$style);
        });

        PDF::setFooterCallBack(function ($pdf) {
            $style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(240, 240, 240));
            $pdf->SetDrawColor('12','15','220');

            $pdf->SetY(-18);
            $pdf->Line(5,130,200,130,$style);
            $pdf->setFont('helvetica', 'I', 7);
            $pdf->Cell(0, 10, auth()->user()->hotel->nombre, 0, false, 'C', 0, '', 0, false, 'T', 'M');
            $pdf->SetY(-15);
            $pdf->Cell(0, 10, auth()->user()->hotel->ubicacion, 0, false, 'C', 0, '', 0, false, 'T', 'M');
            $pdf->SetY(-12);
            $pdf->Cell(0, 10, 'Tel: '.auth()->user()->hotel->telefono." Email: ".auth()->user()->hotel->email, 0, false, 'C', 0, '', 0, false, 'T', 'M');

        });
        $recepcion = Recepcion::select("alojamientos.*","habitaciones.nombre as habitacion","categorias.nombre as categoria","habitaciones.precio","clientes.nombre as cliente","clientes.*")
                    ->join("habitaciones","habitaciones.id","=","alojamientos.habitaciones_id")
                    ->join("categorias","categorias.id","=","habitaciones.categorias_id")
                    ->join("clientes","clientes.id","=","alojamientos.clientes_id")
                    ->where("alojamientos.id",$id)->first();
        //dd($id);

        $html = \View::make("recepciones.imprime")->with("recepcion", $recepcion);
        //die($html);

        $style = array(
            'border' => 0,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        PDF::setMargins(5, 13, 5, 5);
        PDF::setFont('helvetica', '', 8);
        PDF::SetTitle('Alojamiento: ' . $id);
        PDF::AddPage('L','A5');
        //PDF::WriteHTML(0, 'Ticket de Venta');
        PDF::writeHTML($html, true, false, true, false, '');
        PDF::write2DBarcode(route("dashboard"), 'QRCODE,L', 175, 50, 30, 30, $style, 'N');
        //PDF::Text(175, 43, 'QRCODE L');
        PDF::Output('recibo_alojamiento' . $id . '.pdf');
    }
}
