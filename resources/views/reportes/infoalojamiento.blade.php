<div class="grid grid-cols-1 gap-6 bg-white pt-3">
    <div class="col-span-full fix bg-white p-3 border-2 border-l-green-500 rounded-xl">
        <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white pt-2">
            Datos de la Habitación
        </h3>
        <div class="flex justify-center ">
            <hr />
            <table class="w-full p-2 gap-2">
                <thead>
                    <tr>
                        <th class="w-1/6 p-2">Habitacion:</th>
                        <td class="w-1/6 p-2 text-1xl text-gray-500">{{ $habitacion->habitacion }}</td>
                    </tr>
                    <tr>
                        <th class="w-1/6 p-2">Categoria:</th>
                        <td class="w-1/6 p-2 text-1xl text-gray-500">{{ $habitacion->categoria }}</td>
                    </tr>
                    <tr>
                        <th class="w-1/6 p-2">Precio :</th>
                        <td class="w-1/6 p-2 text-1xl text-gray-500">$ {{ number_format($habitacion->precio, 2) }}</td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="col-span-full fix bg-white p-3 border-2 border-l-green-500 rounded-xl">
        <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white pt-2">
            Datos del Cliente
        </h3>
        <div class="flex justify-center ">
            <hr />
            <table class="w-full p-2 gap-2">
                <thead>
                    <tr>
                        <th class="w-1/6 p-2">Nombre:</th>
                        <td class="w-1/6 p-2 text-1xl text-gray-500">{{ $cliente->nombre }}</td>
                    </tr>
                    <tr>
                        <th class="w-1/6 p-2">Documento:</th>
                        <td class="w-1/6 p-2 text-1xl text-gray-500">{{ $cliente->documento }}</td>
                    </tr>
                    <tr>
                        <th class="w-1/6 p-2">Telefono :</th>
                        <td class="w-1/6 p-2 text-1xl text-gray-500">{{ $cliente->telefono }}</td>
                    </tr>
                    <tr>
                        <th class="w-1/6 p-2">Correo :</th>
                        <td class="w-1/6 p-2 text-1xl text-gray-500">{{ $cliente->email }}</td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="col-span-full fix bg-white p-3 border-2 border-l-green-500 rounded-xl">
        <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white pt-2">
            Datos de Alojamiento
        </h3>
        <div class="flex justify-center ">
            <hr />
            <table class="w-full p-2 gap-2">
                <thead>
                    <tr>
                        <th class="w-1/6 p-2">Fecha y hora de entrada:</th>
                        <td class="w-1/6 p-2 text-1xl text-gray-500">{{ $recepcion->fecha_hora_entrada }}</td>
                    </tr>
                    <tr>
                        <th class="w-1/6 p-2">Fecha y hora de salida:</th>
                        <td class="w-1/6 p-2 text-1xl text-gray-500">{{ $recepcion->fecha_hora_salida }}</td>
                    </tr>
                    <tr>
                        <th class="w-1/6 p-2">Tiempo Estimado :</th>
                        <td class="w-1/6 p-2 text-1xl text-green-500">
                            {{ round((strtotime($recepcion->fecha_hora_salida) - strtotime($recepcion->fecha_hora_entrada)) / (60 * 60 * 24)) . ' Dias' }}
                        </td>
                    </tr>
                    <tr>
                        @php
                            $today = $recepcion->updated_at;
                            $excedente = round(
                                (strtotime($today) - strtotime($recepcion->fecha_hora_salida)) / (60 * 60),
                            );
                        @endphp
                        <th class="w-1/6 p-2">Tiempo Rebasado :</th>
                        <td
                            class="w-1/6 p-2 text-1xl @if ($excedente > 0) text-red-500 @else text-green-500 @endif">
                            {{ $excedente . ' Horas' }}</td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="col-span-full fix bg-white p-3 border-2 border-l-green-500 rounded-xl" >
        <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white pt-2">
            Costos
            <input type="hidden" id="id" value="{{$recepcion->id}}">
            @csrf
        </h3>
        <div class="flex justify-center ">
            <hr />
            <table class="w-full p-2 gap-2">
                <thead>
                    <tr class="bg-green-50 p-3">
                        <th class="w-1/6 p-2">Costo de Alojamiento</th>
                        <th class="w-1/6 p-2">Cobro Extra</th>
                        <th class="w-1/6 p-2">Adelanto</th>
                        <th class="w-1/6 p-2">Penalidad o mora</th>
                        <th class="w-1/6 p-2">Pago</th>
                        <th class="w-1/6 p-2">Metodo de Pago</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="w-1/6 p-2">$ {{ number_format($habitacion->precio, 2) }}</td>
                        <td class="w-1/6 p-2">$ {{ number_format($recepcion->cobro_extra, 2) }}</td>
                        <td class="w-1/6 p-2">$ {{ number_format($recepcion->adelanto, 2) }}</td>
                        <td class="w-1/6 p-2">$ {{ number_format($recepcion->mora, 2) }}</td>
                        <td class="w-1/6 p-2" >$ <span id="total_pagar_">{{ number_format($recepcion->total_pagar, 2) }}</span></td>
                        <td class="w-1/6 p-2" > {{$recepcion->metodo_pago}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
