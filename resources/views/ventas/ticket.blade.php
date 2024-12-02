<div style="text-align:center; font-weight:bold">
    Ticket de venta: {{$venta->id}}
</div>
<br/>
<table style="width: 100%; font-size:.8em; border:solid 1px gray">
    <thead>
        <tr>
            <th style="width:10%;background-color: gray;text-align:center">No.</th>
            <th style="width:40%;background-color: gray;text-align:center">Nombre</th>
            <th style="width:20%;background-color: gray;text-align:center" >Precio U.</th>
            <th style="width:10%;background-color: gray;text-align:center">Can.</th>
            <th style="width:20%;background-color: gray;text-align:center">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($productos as $producto )
            <tr>
                <td style="width: 10%;font-size:.8em; border:dotted 1px gray;text-align:center">{{$producto->id}}</td>
                <td style="width: 40%;font-size:.8em; border:dotted 1px gray">{{$producto->nombre}}</td>
                <td style="width: 20%;font-size:.8em; border:dotted 1px gray;text-align:right">{{number_format($producto->precio_unitario,2)}}</td>
                <td style="width: 10%;font-size:.8em; border:dotted 1px gray;text-align:right">{{number_format($producto->cantidad,2)}}</td>
                <td style="width: 20%;font-size:.8em; border:dotted 1px gray;text-align:right">{{number_format($producto->total,2)}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<div></div>
<table style="width: 100%; font-size:.8em; ">
    <tr>
        <td style="width:80%;text-align:right; font-size:1.3em">Total:</td>
        <td style="width:20%;text-align:right; font-size:1.3em">{{"$ ".number_format($venta->total,2)}}</td>
    </tr>
</table>
