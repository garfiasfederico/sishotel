@extends('layouts.principal')
@section('contenido')
    <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white">Realizar Ventas</h3>

    <div class="flex justify-between w-full bg-white rounded-lg p-10">
        <div class="relative">
            <label for="nombre" class="flex gap-2 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Buscar
                Producto <span id="procesando" class="text-green-300"></span></label>
            <input type="text" id="busca_producto" name="busca_producto" autocomplete="false"
                onkeyup="getProductosByNombre()"
                class="w-96 text-black bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
            <div class="w-full absolute  hidden grid bg-white gap-1 border-solid border-1 border-gray-300 z-10"
                id="productosList">
            </div>
        </div>
        <div class="text-right p-3">
            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"></label>
            <button data-modal-target="extralarge-modal" data-modal-toggle="extralarge-modal"
                onclick="setTimeout(cierraVenta(),200)" id="btnTerminaVenta"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Terminar
                Venta</button>
        </div>
        <div class="text-right items-end">
            <button data-modal-target="ventas-list" data-modal-toggle="ventas-list"
                class=" text-white bg-orange-700 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Historial
                de Ventas</button>
        </div>

    </div>
    <h1 class="text-gray-400 p-2 text-2xl">Productos</h1>
    <div>

    </div>
    <div class="relative overflow-x-scroll shadow-md sm:rounded-lg h-full overflow-scroll">
        @csrf
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="tableHabitaciones">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4 hidden">
                        <div class="flex items-center">
                            <input id="checkbox-all-search" type="checkbox"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="checkbox-all-search" class="sr-only">checkbox</label>
                        </div>
                    </th>
                    <th scope="col" class="hidden  px-6 py-3 ">
                        Id
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Tipo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Precio Unitario
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Cantidad
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody id="productosTable" class=" max-h-20 overflow-scroll">

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class=" text-md text-right font-bold pr-5 p-4">Total:</td>
                    <td colspan="1" class="text-left text-xl pr-5 font-bold p-4">$ <span id="total_global"
                            class="text-2xl">0.00</span></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- Extra Large Modal -->
    <div id="extralarge-modal" tabindex="-1"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-3xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                        Registrar pago
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
                    <div class="grid grid-cols-6 gap-6">
                        <div class=" col-span-3 text-center">
                            <label for="tipo"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total</label>
                            <label for="tipo"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-3xl">$ <span
                                    id="total_general_box">0.00</span></label>
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar un tipo para el producto
                            </div>
                        </div>
                        <div class=" col-span-3">
                            <label for="percio_unitario"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Metodo de Pago<span
                                    class="text-red-500">*</span></label>
                            <select name="metodo_pago" id="metodo_pago"
                                class="text-xl form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="12.50" required>
                                <option value="efectivo">Efectivo</option>
                                <option value="final" class="hidden" id="optionFinal">Paga al Final</option>
                            </select>
                        </div>
                        <div class=" col-span-3">
                            <label for="pago"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pago<span
                                    class="text-red-500">*</span></label>
                            <input name="pago" id="pago" type="number" onkeyup="setPagoV()"
                                class="text-3xl text-center shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="" required style="font-size: 2em;" />
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar el pago completo de la venta
                            </div>
                        </div>
                        <div class=" col-span-3 text-center">
                            <label for="tipo"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cambio</label>
                            <label for="tipo"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-3xl">$ <span
                                    id="cambio_general_box">0.00</span></label>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div
                    class="text-right items-right p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="extralarge-modal" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancelar</button>
                    <button type="button" onclick="almacenaVenta()" id="btnAlmacenaVenta"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Registrar Venta</button>

                </div>
            </div>
        </div>
    </div>
    <div id="ventas-list" tabindex="-1"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-7xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                        Historial de Ventas
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="ventas-list">
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
                    @if ($ventas->count() > 0)
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                            id="tableClientes">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="p-4 hidden">
                                        <div class="flex items-center">
                                            <input id="checkbox-all-search" type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 ">
                                        Id
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Fecha
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Usuario que realiza venta
                                    </th>

                                    <th scope="col" class="px-6 py-3 ">
                                        Total
                                    </th>
                                    <th scope="col" class="px-6 py-3 ">
                                        Metodo de Pago
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ventas as $key => $venta)
                                    <tr id="venta{{ $venta->id }}"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="w-4 p-4 hidden">
                                            <div class="flex items-center">
                                                <input id="checkbox-table-search-1" type="checkbox"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                            </div>
                                        </td>
                                        <td scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $venta->venta }}
                                        </td>
                                        <td scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $venta->created_at }}
                                        </td>
                                        <td scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white ">
                                            {{ $venta->name }}
                                        </td>
                                        <td scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white ">
                                            $ {{ number_format($venta->total,2) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $venta->metodo_pago }}
                                        </td>
                                        <td class="items-center px-6 py-4 mr-2" align="center">
                                           <a href="{{route('ventas.imprimeticket',$venta->id)}}" target="_blank"> <button
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="">
                                                Ticket</button></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="flex flex-col items-center justify-center p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-center"
                            role="alert">
                            <span class="flex flex-col space-x-7">
                                <svg class="h-10 w-10 text-purple-700" viewBox="0 0 24 24" fill="none"
                                    stroke="purple" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" y1="16" x2="12" y2="12" />
                                    <line x1="12" y1="8" x2="12.01" y2="8" />
                                </svg>
                            </span>
                            <div class="text-gray-500  text-2xl">
                                No existen Ventas Registradas!
                            </div>
                            <button class="button">

                            </button>
                        </div>
                    @endif
                </div>
                <!-- Modal footer -->
                <div
                    class="text-right items-right p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="ventas-list" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cerrar
                        Ventana</button>

                </div>
            </div>
        </div>
    </div>
@endsection
