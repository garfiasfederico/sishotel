<style>
    th {
        color: gray;
        width: 120pt;
    }

    td {
        padding: 10pt;
    }
    .cliente{
        font-size: 1.3em
    }
</style>
<table style="width: 100%" border="0">
    <tr>
        <td style="width: 50%;padding:10pt;border-bottom:solid 1px gray">
            <table style="100%" border="0">
                <tr>
                    <td colspan="2" style="font-size: 1.3em;color:gray"><br /><b>Datos de la Habitación</b><br /></td>
                </tr>
                <tr>
                    <th>Habitación:</th>
                    <td style="80%">{{ $recepcion->habitacion }}</td>
                </tr>
                <tr>
                    <th>Categoria:</th>
                    <td>{{ $recepcion->categoria }}</td>
                </tr>
                <tr>
                    <th>Precio por alojamiento (24hrs.):</th>
                    <td>$ {{ number_format($recepcion->precio, 2) }}</td>
                </tr>
            </table>
        </td>
        <td style="width: 50%;padding:10pt;border-bottom:solid 1px gray">
            <table>
                <tr>
                    <td colspan="2" style="font-size: 1.3em;color:gray"><br/><b>Datos del Alojamiento</b><br /></td>
                </tr>
                <tr>
                    <th>Fecha y Hora de Entrada:</th>
                    <td>{{$recepcion->fecha_hora_entrada}}</td>
                </tr>
                <tr>
                    <th>Fecha y Hora de Salida:</th>
                    <td>{{$recepcion->fecha_hora_salida}}</td>
                </tr>
                @php
                    $entrada = strtotime($recepcion->fecha_hora_entrada);
                    $salida = strtotime($recepcion->fecha_hora_salida);
                    $diff = ($salida - $entrada)/(60 * 60 * 24)
                @endphp
                <tr>
                    <th>Tiempo Estimado de Alojamiento:</th>
                    <td>{{" ".round($diff)." Dias"}}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<h1></h1>
<table>
    <tr>
        <td style="width: 100%;padding:10pt;">
            <table>
                <tr>
                    <td colspan="2" style="font-size: 1.3em;text-align:center;color:gray"><br /><b>Datos del Cliente</b><br /></td>
                </tr>
                <tr>
                    <th>Nombre:</th>
                    <td class="cliente"><b>{{ $recepcion->cliente }}</b></td>
                </tr>
                <tr>
                    <th>Documento:</th>
                    <td class="cliente"><b>{{ $recepcion->documento }}</b></td>
                </tr>
                <tr>
                    <th>Telefono:</th>
                    <td class="cliente"><b>{{ $recepcion->telefono }}</b></td>
                </tr>
                <tr>
                    <th>Correo Electronico:</th>
                    <td class="cliente"><b>{{ $recepcion->email }}</b></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<h1></h1>
<table>
    <tr>
        <td style="width: 100%;padding:10pt;border-top:solid 1px gray">
            <table style="width: 100%;font-size:1.3em">
             <thead>
                <tr>
                    <td colspan="5" style="font-size: 1.3em;text-align:center;color:gray"><br /><b>Cierre de Alojamiento</b><br /></td>
                </tr>
                <tr>
                    <td style="text-align:center;background-color:gray;color:white">Costo de Alojamiento</td>
                    <td style="text-align:center;background-color:gray;color:white">Cobro Extra</td>
                    <td style="text-align:center;background-color:gray;color:white">Adelanto</td>
                    <td style="text-align:center;background-color:gray;color:white">Penalidad o Mora</td>
                    <td style="text-align:center;background-color:gray;color:white">Total</td>
                </tr>
                <tr>
                    <td style="text-align:center">$ {{number_format($recepcion->precio,2)}}</td>
                    <td style="text-align:center">$ {{number_format($recepcion->cobro_extra,2)}}</td>
                    <td style="text-align:center">$ {{number_format($recepcion->adelanto,2)}}</td>
                    <td style="text-align:center">$ {{number_format($recepcion->mora,2)}}</td>
                    <td style="text-align:center">$ {{number_format($recepcion->total_pagar,2)}}</td>
                </tr>
                <tr>
                    <td style="text-align:right;background-color:gray;color:white" colspan="4">Pago:&nbsp;</td>
                    <td style="text-align:center;">$ {{number_format($recepcion->pago,2)}}</td>
                </tr>
                <tr>
                    <td style="text-align:right;background-color:rgb(166, 166, 166);color:white" colspan="4">Cambio:&nbsp;</td>
                    <td style="text-align:center;">$ {{number_format(0,2)}}</td>
                </tr>
                <tr>
                    <td style="text-align:right;background-color:gray;color:white" colspan="4">Método de Pago:&nbsp;</td>
                    <td style="text-align:center;">{{$recepcion->metodo_pago}}</td>
                </tr>
             </thead>
            </table>
        </td>
    </tr>
</table>
<div style="font-size: .5em">
   <p> Nota: Si desea facturar su alojamiento, favor de escanear el código QR que se encuentra en este documento, rellene los campos faltantes para proceder con la facturacion.</p>
</div>
