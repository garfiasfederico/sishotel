@extends('layouts.principal')
@section('contenido')
    <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white">
        Registrar Alojamiento
    </h3>

    <div class="fix bg-white p-3 border-2 border-l-green-500">
        <div class="flex justify-center ">

            <table class="w-full p-2">
                <thead>
                    <tr>
                        <th class="w-1/6">Habitacion:</th>
                        <td class="w-1/6 text-1xl text-gray-500">{{ $habitacion->habitacion }}</td>
                        <th class="w-1/6 ">Nivel:</th>
                        <td class="w-1/6 text-1xl text-gray-500">{{ $habitacion->nivel }}</td>
                        <th class="w-1/6 ">Categoria:</th>
                        <td class="w-1/6 text-1xl text-gray-500">{{ $habitacion->categoria }}</td>
                    </tr>
                    <tr>
                        <th class="w-1/6">Precio :</th>
                        <td class="w-1/6 text-1xl text-gray-500">$ {{ number_format($habitacion->precio, 2) }}</td>
                        <th class="w-1/6">Detalles:</th>
                        <td class="w-1/6 text-1xl text-gray-500">{{ $habitacion->detalles }}</td>
                        <th class="w-1/6">Estado:</th>
                        <td class="w-1/6 text-1xl text-gray-500">{{ $habitacion->estado }}</td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <form id="formRecepcion">
        <input type="hidden" id="id" name="id" @if(isset($recepcion)) value="{{$recepcion->id}}" @endif>
        <input type="hidden" name="habitaciones_id" id="habitaciones_id" value="{{$habitacion->id}}">
        @csrf
    <div class="grid grid-cols-2 gap-6 bg-white pt-3">
        <div class="grid grid-cols-6 gap-6 bg-white p-3 border-2 border-l-green-500">
            <div class="col-span-6">
                <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white pt-4">
                    Datos del Cliente
                </h3>
                <hr />
            </div>
            <div class="col-span-6">
                <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecciona el Cliente<span class="text-red-500">*</span></label>
                <select type="text" name="cliente" id="cliente" onchange="setCliente()"
                    class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Nombre del Cliente" required >
                    <option value="">--Seleccione</option>
                    @foreach ($clientes as $cliente_)
                        <option value="{{$cliente_->id}}" @if(isset($recepcion)) @if($recepcion->clientes_id == $cliente_->id) selected @endif @endif>{{$cliente_->nombre}}</option>
                    @endforeach
                    <option value="0">--Nuevo</option>
                </select> <span id="loadingcliente"></span>
                <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                    Debe indicar el cliente que registra el alojamiento
                </div>
            </div>
            <div class="col-span-3">
                <input type="hidden" name="clientes_id" id="clientes_id" value="@if(isset($recepcion)){{$recepcion->clientes_id}}@endif">
                <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre
                    Completo<span class="text-red-500">*</span></label>
                <input type="text" name="nombre" id="nombre"
                    class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Nombre del Cliente" required value="@if(isset($cliente)){{$cliente->nombre}}@endif"/>
                <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                    Debe indicar nombre del cliente
                </div>
            </div>
            <div class=" col-span-3">
                <label for="tipo_documento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo
                    Documento<span class="text-red-500">*</span></label>
                <select name="tipo_documento" id="tipo_documento"
                    class=" form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Bebidas" required>
                    <option value="">--Seleccione</option>
                    <option value="ine" @if(isset($cliente)){{$cliente->tipo_documento=='ine'?'selected':''}}@endif>INE</option>
                    <option value="pasaporte" @if(isset($cliente)){{$cliente->tipo_documento=='pasaporte'?'selected':''}}@endif>Pasaporte</option>
                    <option value="licencia_conducir" @if(isset($cliente)){{$cliente->tipo_documento=='licencia_conducir'?'selected':''}}@endif>Licencia de Conducir</option>
                    <option value="otro" @if(isset($cliente)){{$cliente->tipo_documento=='otro'?'selected':''}}@endif>Otro</option>
                </select>
                <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                    Debe indicar el tipo de documento que presenta el cliente
                </div>
            </div>
            <div class=" col-span-3">
                <label for="documento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Documento<span
                        class="text-red-500">*</span></label>
                <input type="text" name="documento" id="documento"
                    class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="DOC-" required value="@if(isset($cliente)){{$cliente->documento}}@endif"/>
                <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                    Debe indicar el No. de documento del cliente
                </div>
            </div>
            <div class=" col-span-3">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email<span
                        class="text-red-500">*</span></label>
                <input type="text" name="email" id="email"
                    class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="ejemplo@ejemplo.com" required value="@if(isset($cliente)){{$cliente->email}}@endif"/>
                <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                    Debe indicar un Email válido
                </div>
            </div>
            <div class=" col-span-3">
                <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono<span
                        class="text-red-500">*</span></label>
                <input type="text" name="telefono" id="telefono"
                    class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="95112311" required value="@if(isset($cliente)){{$cliente->telefono}}@endif"/>
                <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                    Debe indicar un Teléfono Válido
                </div>
            </div>
            <div class=" col-span-3">
                <label for="rfc" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">RFC</label>
                <input type="text" name="rfc" id="rfc"
                    class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="" required value="@if(isset($cliente)){{$cliente->rfc}}@endif"/>
            </div>
            <div class=" col-span-3">
                <label for="razon_social" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Razon
                    Social</label>
                <input type="text" name="razon_social" id="razon_social" value="@if(isset($cliente)){{$cliente->razon_social}}@endif"
                    class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="" required />
            </div>
        </div>
        <div class="grid grid-cols-6 gap-6 bg-white p-3  border-2 border-l-green-500">
            <div class="col-span-6">
                <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white pt-4">
                    Datos del Alojamiento
                </h3>
                <hr />
            </div>
            <div class=" col-span-3">
                <label for="fecha_hora_entrada" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha y
                    hora de entrada<span class="text-red-500">*</span></label>
                {{ /*dateTime-local*/ Form::input('date', 'fecha_hora_entrada', isset($recepcion)?explode(" ",$recepcion->fecha_hora_entrada)[0]:Date("Y-m-d"), ['onchange' => 'actualizaTotalReservacion()', 'id' => 'fecha_hora_entrada', 'class' => 'form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500','readonly'=>true]) }}
                <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                    Debe indicar la fecha y la hora de entrada
                </div>

            </div>
            <div class=" col-span-3">
                <label for="fecha_hora_salida" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha
                    y hora de salida<span class="text-red-500">*</span></label>
                {{ Form::input('date', 'fecha_hora_salida', isset($recepcion)?explode(" ",$recepcion->fecha_hora_salida)[0]:'', ['onchange' => 'actualizaTotalReservacion()', 'id' => 'fecha_hora_salida', 'class' => 'form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500']) }}
                <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                    Debe indicar la fecha y la hora de salida
                </div>
            </div>
            <div class=" col-span-3">
                <label for="precio_hospedaje" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Costo
                    de hospedaje<span class="text-red-500">*</span></label>
                <input type="number" name="precio_hospedaje" id="precio_hospedaje" value="{{$habitacion->precio}}"
                    class="text-right form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="0" required readonly autocomplete="off" />
            </div>
            <div class=" col-span-3">
                <label for="cobro_extra" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cobro
                    Extra</label>
                <input type="number" name="cobro_extra" id="cobro_extra" onchange="actualizaTotalReservacion()" value="{{isset($recepcion)?$recepcion->cobro_extra:0}}"
                    class="text-right form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="0" required autocomplete="off" />
            </div>
            <div class=" col-span-3">
                <label for="descuento"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descuento</label>
                <input type="number" name="descuento" id="descuento" onchange="actualizaTotalReservacion()" value="{{isset($recepcion)?$recepcion->descuento:0}}"
                    class="text-right form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="0" required autocomplete="off" />
            </div>
            <div class=" col-span-3">
                <label for="adelanto"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Adelanto</label>
                <input type="number" name="adelanto" id="adelanto" onchange="actualizaTotalReservacion()" value="{{isset($recepcion)?$recepcion->adelanto:0}}"
                    class="text-right form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="0" required autocomplete="off" />
            </div>
            <div class=" col-span-3">
                <label for="metodo_pago" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Metodo de
                    Pago</label>
                <select name="metodo_pago" id="metodo_pago"
                    class=" form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option value="">--Seleccione</option>
                    <option value="efectivo" @if(isset($recepcion)){{$recepcion->metodo_pago == "efectivo"?"selected":""}}@endif>Efectivo</option>
                    <option value="tarjeta" @if(isset($recepcion)){{$recepcion->metodo_pago == "tarjeta"?"selected":""}}@endif>Tarjeta</option>
                </select>
                <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                    Debe indicar el estado de la reservación
                </div>
            </div>
            <div class="col-span-3 text-center">
                <label for="total"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total</label>
                    $ <span class="text-3xl" id="total_pagar">{{isset($recepcion)?$recepcion->total_pagar:0.00}}</span>
            </div>
        </div>
    </div>
    </form>
    <div class="w-full flex justify-end gap-3 p-4 bg-white">
        <a href="{{route('recepciones')}}"><button
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">Cancelar</button></a>
        <button onclick="addRecepcion()" id="btnAlmacenaAlojamiento"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Registrar
            Alojamiento</button>
    </div>
@endsection
