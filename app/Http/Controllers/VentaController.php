<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Venta;
use App\Models\VentaProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::select("ventas.*", "ventas.id as venta","users.name")
            ->where("ventas.hoteles_id", auth()->user()->hoteles_id)
            ->join("users", "users.id", "=", "ventas.users_id")
            ->get();
            //dd($ventas);
        return  view("ventas.index")->with("ventas", $ventas);
    }

    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            $venta = Venta::create([
                "cuando_paga" => "inmediato",
                "estado" => "pagado",
                "metodo_pago" => $request->metodo_pago,
                "total" => $request->total_global,
                "alojamientos_id" => null,
                "hoteles_id" => auth()->user()->hoteles_id,
                "users_id" => auth()->user()->id
            ]);
            ($venta->id);

            $ids = explode("|", $request->ids);
            $precios = explode("|", $request->precios);
            $cantidades = explode("|", $request->cantidades);
            $totales = explode("|", $request->totales);

            array_pop($ids);

            if (count($ids) > 0) {
                foreach ($ids as $key => $id) {
                    //dd($id." ".$precios[$key]." ".$cantidades[$key]." ".$totales[$key]);
                    VentaProducto::create([
                        "ventas_id" => $venta->id,
                        "productos_id" => $id,
                        "precio_unitario" => $precios[$key],
                        "cantidad" => $cantidades[$key],
                        "total" => $totales[$key]
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                "result" => "ok",
                "message" => "Venta Almacenada Satisfactoriamente!",
                "id" => $venta->id
            ]);
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json([
                "result" => "error",
                "message" => "Ocurrió un error al tratar de almacenar la venta, Intente mas tarde!" . $ex,
                "id" => null
            ]);
        }
    }

    public function imprimeticket($id)
    {
        PDF::setHeaderCallback(function ($pdf) {
            $pdf->setFont('helvetica', 'B', 10);
            $pdf->setY(2);
            $pdf->Image('images/logo.png', '', '', 10, 10, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
            $pdf->setY(5);
            $pdf->Cell(0, 10, auth()->user()->hotel->nombre, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        });

        PDF::setFooterCallBack(function ($pdf) {
            $pdf->SetY(-18);
            $pdf->setFont('helvetica', 'I', 7);
            $pdf->Cell(0, 10, auth()->user()->hotel->nombre, 0, false, 'C', 0, '', 0, false, 'T', 'M');
            $pdf->SetY(-15);
            $pdf->Cell(0, 10, auth()->user()->hotel->ubicacion, 0, false, 'C', 0, '', 0, false, 'T', 'M');
            $pdf->SetY(-12);
            $pdf->Cell(0, 10, "Tel: " . auth()->user()->hotel->telefono . " Email: " . auth()->user()->hotel->email, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        });


        $productos = VentaProducto::select("venta_productos.*", "productos.nombre")->where("ventas_id", $id)
            ->join("productos", "productos.id", "=", "venta_productos.productos_id")->get();
        $infoVenta = Venta::find($id);
        //dd($id);

        $html = \View::make("ventas.ticket")->with("venta", $infoVenta)->with("productos", $productos);

        $style = array(
            'border' => false,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        PDF::setMargins(5, 8, 5, 5);
        PDF::setFont('helvetica', '', 8);
        PDF::SetTitle('Ticket: ' . $id);
        PDF::AddPage('P', array(80, 90));
        //PDF::WriteHTML(0, 'Ticket de Venta');
        PDF::writeHTML($html, true, false, true, false, '');
        //PDF::write1DBarcode($infoVenta->id, 'C39', 28, 50, 0, 15, $style, 'N');
        PDF::write1DBarcode($id, 'C39', 20, 50, '', 15, .6, $style, 'N');
        PDF::Output('ticket_venta' . $id . '.pdf');
    }
}
