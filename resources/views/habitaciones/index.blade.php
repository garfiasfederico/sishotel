@extends('layouts.principal')
@section('contenido')
    <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white">Habitaciones Registradas</h3>
    <div class="flex justify-end">
        <button data-modal-target="extralarge-modal" data-modal-toggle="extralarge-modal" onclick="clearDataHabitacion()"
            class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mb-2"
            type="button">Nueva Habitacion</button>
    </div>
    @if ($habitaciones->count() > 0)
        @csrf
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-5">
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
                        <th scope="col" class="px-6 py-3 hidden">
                            Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3 hidden">
                            nivel_id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nivel
                        </th>
                        <th scope="col" class="px-6 py-3 hidden">
                            categoria_id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Categoria
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Precio
                        </th>
                        <th scope="col" class="px-6 py-3">
                            tarifa
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Detalles
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($habitaciones as $key => $habitacion)
                        <tr id="habitacion{{ $habitacion->id }}"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="w-4 p-4 hidden">
                                <div class="flex items-center">
                                    <input id="checkbox-table-search-1" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                </div>
                            </td>
                            <td scope="row"
                                class=" hidden px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $habitacion->id }}
                            </td>
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $habitacion->nombre }}
                            </td>
                            <td class="px-6 py-4 hidden">
                                {{ $habitacion->niveles_id }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $habitacion->nivel }}
                            </td>
                            <td class="px-6 py-4 hidden">
                                {{ $habitacion->categorias_id }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $habitacion->categoria }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $habitacion->precio }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $habitacion->tarifa }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $habitacion->detalles }}
                            </td>
                            <td class="items-center px-6 py-4 mr-2">
                                <button data-modal-target="extralarge-modal" data-modal-toggle="extralarge-modal"
                                    onclick="setDataHabitacion({{ $habitacion->id }})"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Editar</button>

                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="deleteHabitacion({{ $habitacion->id }},'{{ $habitacion->nombre }}')"
                                    id="btnDeleteHabitacion{{ $habitacion->id }}">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-5">
                {{ $habitaciones->links('pagination::tailwind') }}
            </div>
        </div>
    @else
        <div class="flex flex-col items-center justify-center p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-center"
            role="alert">
            <span class="flex flex-col space-x-7">
                <svg class="h-10 w-10 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="purple" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="16" x2="12" y2="12" />
                    <line x1="12" y1="8" x2="12.01" y2="8" />
                </svg>
            </span>
            <div class="text-gray-500  text-2xl">
                No existen Habitaciones Registradas!
            </div>
            <button class="button">

            </button>
        </div>
    @endif

    <!-- Extra Large Modal -->
    <div id="extralarge-modal" tabindex="-1"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-7xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                        Registar nueva habitación
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
                        <div class=" col-span-3">
                            <input type="hidden" id="id">
                            <label for="nombre"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre de la
                                Habitación<span class="text-red-500">*</span></label>
                            <input type="text" name="nombre" id="nombre"
                                class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="301" required />
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar un nombre para la habitacion
                            </div>
                        </div>
                        <div class=" col-span-3">
                            <label for="niveles_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nivel <span
                                    class="text-red-500">*</span></label>
                            <select name="niveles_id" id="niveles_id"
                                class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                                <option value="">--Seleccione</option>
                                @foreach($niveles as $key => $nivel)
                                    <option value="{{$nivel->id}}">{{$nivel->nombre}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar el Nivel en el que se encuentra la Habitación
                            </div>
                        </div>
                        <div class=" col-span-3">

                            <label for="categorias_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categoría
                                <span class="text-red-500">*</span></label>
                            <select name="categorias_id" id="categorias_id"
                                class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                                <option value="">--Seleccione</option>
                                @foreach($categorias as $key => $categoria)
                                    <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar la Categoría en el que se encuentra la Habitación
                            </div>
                        </div>
                        <div class=" col-span-3">
                            <label for="niveles_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Precio<span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="precio" id="precio"
                                class="text-right form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="300.00" required />
                            <div class="invalid-feedback bg-red-500 text-white w-full text-center rounded-md">
                                Debe indicar un precio para la habitacion
                            </div>
                        </div>
                        <div class=" col-span-3">
                            <label for="niveles_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tarifa</label>
                            <input type="text" name="tarifa" id="tarifa"
                                class="form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="24hrs" required />
                        </div>
                        <div class=" col-span-3">
                            <label for="niveles_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Detalles</label>
                            <textarea name="detalles" id="detalles"
                                class=" form-control shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Detalles" required></textarea>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div
                    class="text-right items-right p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="extralarge-modal" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancelar</button>
                    <button type="button" onclick="addHabitacion()" id="btnAlmacenaHabitacion"
                        class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Almacenar Habitacion</button>

                </div>
            </div>
        </div>
    </div>
@endsection
