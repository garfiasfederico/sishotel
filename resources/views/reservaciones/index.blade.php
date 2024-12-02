@extends('layouts.principal')
@section('contenido')
    <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white">Realizar Reservaciones</h3>
    <div class="absolute left-10  justify-center ">

        <table class="w-full text-gray-500 border border-solid border-gray-300 rounded-lg">
            <tr>
                <td class=" text-1xl p-1 m-2 text-center" colspan="2">Simbología</td>

            </tr>
            <tr>
                <td class="border border-solid border-white border-4 bg-gray-500 rounded-lg w-9 h-7 text-2xl p-1 m-2"></td>
                <td class="p-1">Sin Confirmar</td>
            </tr>
            <tr>
                <td class=" border border-solid border-white border-4 bg-orange-400 rounded-lg w-9 h-7 text-2xl p-1 m-2">
                </td>
                <td class="p-1">Confirmada</td>
            </tr>
            <tr>
                <td class="border border-solid border-white border-4 bg-green-600 rounded-lg w-9 h-7 text-2xl p-1 m-2"></td>
                <td class="p-1">Ingresó</td>
            </tr>
            <tr>
                <td class="border border-solid border-white border-4 bg-red-500 rounded-lg w-7 h-9 text-2xl p-1 m-2"></td>
                <td class="p-1">No ingresó</td>
            </tr>
            <tr>
                <td class="border border-solid border-white border-4 bg-black rounded-lg w-7 h-9 text-2xl p-1 m-2"></td>
                <td class="p-1">Hospedaje Terminado</td>
            </tr>

        </table>

    </div>
    <div class="flex justify-center bg-white">
        <button type="button" data-modal-target="extralarge-modal" data-modal-toggle="extralarge-modal"
            onclick="clearReservacion()" id="btnNuevaReservacion"
            class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mb-2">Nueva
            Reservación</button>
    </div>
    <div id="calendarioContenedor" class="bg-white text-center items-center">
        <div id='calendar' data-toggle="calendar"></div>
    </div>

    <!-- Extra Large Modal -->
    <div id="extralarge-modal" tabindex="-1"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-4xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-large text-gray-900 dark:text-white" id="titulo">
                        Registrar nueva reservación
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="extralarge-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <div class="grid grid-cols-4 gap-6">
                        <div class="col-span-4 text-lg text-white w-full bg-gray-500 pl-2">Datos del cliente</div>
                        <div class=" col-span-4">
                            <label for="nombre"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecciona el
                                Cliente<span class="text-red-500">*</span></label>
                            <select type="text" name="cliente" id="cliente" onchange="setCliente()"
                                class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Nombre del Cliente" required>
                                <option value="">--Seleccione</option>
                                @foreach ($clientes as $cliente_)
                                    <option value="{{ $cliente_->id }}"
                                        @if (isset($recepcion)) @if ($recepcion->clientes_id == $cliente_->id) selected @endif
                                        @endif>{{ $cliente_->nombre }}</option>
                                @endforeach
                                <option value="0">--Nuevo</option>
                            </select> <span id="loadingcliente"></span>
                        </div>
                        <div class=" col-span-4">
                            @csrf
                            <input type="hidden" id="id">
                            <input type="hidden" id="clientes_id">
                            <label for="nombre"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del Cliente<span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nombre" id="nombre"
                                class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Cliente que se hospeda" required autocomplete="off" />
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar el nombre del cliente
                            </div>
                        </div>
                        <div class=" col-span-2">
                            <label for="tipo_documento"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo
                                de Documento<span class="text-red-500">*</span></label>
                            <select name="tipo_documento" id="tipo_documento"
                                class=" form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                                <option value="">--Seleccione</option>
                                <option value="ine">INE</option>
                                <option value="pasaporte">Pasaporte</option>
                                <option value="licencia_conducir">Licencia de Conducir</option>
                                <option value="otro">Otro</option>
                            </select>
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar el tipo de Documento Presentado
                            </div>
                        </div>
                        <div class=" col-span-2">
                            <label for="documento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No.
                                Documento<span class="text-red-500">*</span></label>
                            <input type="text" name="documento" id="documento"
                                class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="12391829FBD" required autocomplete="off" />
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar el número del documento presentado
                            </div>
                        </div>
                        <div class=" col-span-2">
                            <label for="rfc"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">RFC</label>
                            <input type="text" name="rfc" id="rfc"
                                class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="XXAA010101XAX" required autocomplete="off" />
                        </div>
                        <div class=" col-span-2">
                            <label for="razon_social"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Razón Social</label>
                            <input type="text" name="razon_social" id="razon_social"
                                class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Razón Social" required autocomplete="off" />
                        </div>
                        <div class=" col-span-2">
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email<span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="email" id="email"
                                class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="ejemplo@ejemplo.com" required autocomplete="off" />
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar un email para hacer llegar el comprobante de la recepción
                            </div>

                        </div>
                        <div class=" col-span-2">
                            <label for="telefono"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono<span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="telefono" id="telefono"
                                class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="951000000" required autocomplete="off" />
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar un teléfono para realizar la confirmación de la reservación
                            </div>
                        </div>
                        <div class="col-span-4 text-lg text-white w-full bg-gray-500 pl-2">Datos del Alojamiento</div>
                        <div class=" col-span-4">
                            <label for="habitaciones_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Habitación<span
                                    class="text-red-500">*</span></label>
                            <select name="habitaciones_id" id="habitaciones_id" onchange="setCosto()"
                                class=" form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                                <option value="">--Seleccione</option>
                                @foreach ($habitaciones as $habitacion)
                                    <option value="{{ $habitacion->id }}" costo="{{ $habitacion->precio }}">Habitacion: -
                                        {{ $habitacion->nombre . ', ' . $habitacion->nivel . ', ' . $habitacion->categoria }}
                                    </option>
                                @endforeach

                            </select>
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar la habitación que se está reservando
                            </div>
                        </div>
                        <div class=" col-span-2">
                            <label for="fecha_hora_entrada"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha y hora de
                                entrada<span class="text-red-500">*</span></label>
                            {{ /*dateTime-local*/ Form::input('date', 'fecha_hora_entrada', '', ['onchange' => 'actualizaTotalReservacion()', 'id' => 'fecha_hora_entrada', 'class' => 'form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500']) }}
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar la fecha y la hora de entrada
                            </div>

                        </div>
                        <div class=" col-span-2">
                            <label for="fecha_hora_salida"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha y hora de
                                salida</label>
                            {{ Form::input('date', 'fecha_hora_salida', '', ['onchange' => 'actualizaTotalReservacion()', 'id' => 'fecha_hora_salida', 'class' => 'form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500']) }}
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar la fecha y la hora de salida
                            </div>
                        </div>
                        <div class="col-span-4 text-lg text-white w-full bg-gray-500 pl-2">Costos</div>
                        <div class=" col-span-2">
                            <label for="precio_hospedaje"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Costo de
                                hospedaje<span class="text-red-500">*</span></label>
                            <input type="number" name="precio_hospedaje" id="precio_hospedaje"
                                class="text-right form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="0" required readonly autocomplete="off" />
                        </div>
                        <div class=" col-span-2">
                            <label for="cobro_extra"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cobro Extra</label>
                            <input type="number" name="cobro_extra" id="cobro_extra"
                                onchange="actualizaTotalReservacion()"
                                class="text-right form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="0" required autocomplete="off" />
                        </div>
                        <div class=" col-span-2">
                            <label for="descuento"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descuento</label>
                            <input type="number" name="descuento" id="descuento" onchange="actualizaTotalReservacion()"
                                class="text-right form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="0" required autocomplete="off" />
                        </div>
                        <div class=" col-span-2">
                            <label for="adelanto"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Adelanto</label>
                            <input type="number" name="adelante" id="adelanto" onchange="actualizaTotalReservacion()"
                                class="text-right form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="0" required autocomplete="off" />
                        </div>
                        <div class=" col-span-2">
                            <label for="metodo_pago"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Metodo de Pago</label>
                            <select name="metodo_pago" id="metodo_pago"
                                class=" form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">--Seleccione</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta</option>
                            </select>
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar el estado de la reservación
                            </div>
                        </div>
                        <div class="col-span-2">
                            <label for="total"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total</label>
                            $ <span class="text-3xl" id="total_pagar">0.00</span>
                        </div>
                        <div class="col-span-4 text-lg text-white w-full bg-gray-500 pl-2">Detalles</div>
                        <div class=" col-span-4">
                            <label for="observaciones"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Observaciones</label>
                            <textarea name="observaciones" id="observaciones"
                                class="text-left form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            </textarea>
                        </div>
                        <div class=" col-span-4">
                            <label for="estado"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Estatus<span
                                    class="text-red-500">*</span></label>
                            <select name="estado" id="estado"
                                class=" form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">--Seleccione</option>
                                <option value="sin_confirmar">Reservación sin Confirmar</option>
                                <option value="confirmada">Reservación Confirmada</option>
                                <option value="ingreso">Ingresó</option>
                                <option value="no_ingreso">No Ingresó</option>
                                <option value="terminada">Hospedaje Terminado</option>
                            </select>
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar el estado de la reservación
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div
                    class="text-right items-right p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="extralarge-modal" type="button" id="btnCancelar"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancelar</button>
                    <button type="button" onclick="addReservacion()" id="btnAlmacenaReservacion"
                        class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Almacenar Reservación</button>
                    <button type="button" onclick="deleteReservacion()" id="btnDeleteReservacion"
                        class="hidden text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                        <!--<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>-->
                        Eliminar Reservación</button>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script></script>
@endsection
