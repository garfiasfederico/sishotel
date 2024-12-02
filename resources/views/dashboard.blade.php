@extends('layouts.principal')
@section('contenido')
    <div class="grid gap-4 xl:grid-cols-4 2xl:grid-cols-4">

        @php
            $libres = 0;
            $ocupadas = 0;
            $reservadas = 0;
            if ($habitaciones->count() > 0) {
            foreach ($habitaciones as $habitacion) {
                switch ($habitacion->estado) {
                    case 'libre':
                        $libres += 1;
                        break;
                    case 'ocupada':
                        $ocupadas += 1;
                        break;
                    case 'reservada':
                        $reservadas += 1;
                        break;
                }
            }
        }
        @endphp
        <div class="col-span-1 relative w-86 p-2 bg-purple-700 border border-gray-200 rounded-lg shadow  ">
            <a href="#">
                <h5 class="mb-2 text-1xl font-bold tracking-tight text-white dark:text-white">
                    Habitaciones Registradas
                </h5>
            </a>
            <p class="mb-3 font-normal text-white text-3xl">{{ $habitaciones->count() }}</p>
            <hr />
        </div>
        <div class="col-span-1 relative w-86 p-2 bg-green-700 border border-gray-200 rounded-lg shadow  ">
            <a href="#">
                <h5 class="mb-2 text-1xl font-bold tracking-tight text-white dark:text-white">
                    Habitaciones Libres
                </h5>
            </a>
            <p class="mb-3 font-normal text-white text-3xl">{{ $libres }}</p>
            <hr />
        </div>
        <div class="col-span-1 relative w-86 p-2 bg-red-700 border border-gray-200 rounded-lg shadow  ">
            <a href="#">
                <h5 class="mb-2 text-1xl font-bold tracking-tight text-white dark:text-white">
                    Habitaciones Ocupadas
                </h5>
            </a>
            <p class="mb-3 font-normal text-white text-3xl">{{ $ocupadas }}</p>
            <hr />
        </div>
        <div class="col-span-1 relative w-86 p-2 bg-gray-700 border border-gray-200 rounded-lg shadow  ">
            <a href="#">
                <h5 class="mb-2 text-1xl font-bold tracking-tight text-white dark:text-white">
                    Habitaciones Reservadas
                </h5>
            </a>
            <p class="mb-3 font-normal text-white text-3xl">{{ $reservadas }}</p>
            <hr />
        </div>

        <div
            class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-4 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-shrink-0">
                    <span class="text-xl font-bold leading-none text-gray-900 sm:text-2xl dark:text-white">$45,385</span>
                    <h3 class="text-base font-light text-gray-500 dark:text-gray-400">Ventas de la Semana</h3>
                </div>
                <div class="flex items-center justify-end flex-1 text-base font-medium text-green-500 dark:text-green-400">
                    12.5%
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div id="main-chart"></div>
            <!-- Card Footer -->
            <div class="flex items-center justify-between pt-3 mt-4 border-t border-gray-200 sm:pt-6 dark:border-gray-700">
                <div>
                    <button
                        class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                        type="button" data-dropdown-toggle="weekly-sales-dropdown">Ultimos 7 Días <svg class="w-4 h-4 ml-2"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg></button>
                    <!-- Dropdown menu -->
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                        id="weekly-sales-dropdown">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white" role="none">
                                Sep 16, 2021 - Sep 22, 2021
                            </p>
                        </div>
                        <ul class="py-1" role="none">
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    role="menuitem">Yesterday</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    role="menuitem">Today</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    role="menuitem">Last 7 days</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    role="menuitem">Last 30 days</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                    role="menuitem">Last 90 days</a>
                            </li>
                        </ul>
                        <div class="py-1" role="none">
                            <a href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                role="menuitem">Custom...</a>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <a href="#"
                        class="inline-flex items-center p-2 text-xs font-medium uppercase rounded-lg text-primary-700 sm:text-sm hover:bg-gray-100 dark:text-primary-500 dark:hover:bg-gray-700">
                        Reporte de Ventas
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-span-4 bg-white border border-gray-200 rounded-lg shadow-sm p-5 ">
            <h3 class="text-2xl p-3">Alojamientos Activos</h3>
            @if ($alojamientos->count() > 0)
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="tableClientes">
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
                                Habitación
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Fecha Entrada
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Fecha Salida
                            </th>
                            <th scope="col" class="px-6 py-3 ">
                                Cliente
                            </th>
                            <th scope="col" class="px-6 py-3 ">
                                Estado
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alojamientos as $key => $alojamiento)
                            <tr id="alojamiento{{ $alojamiento->id }}"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $alojamiento->habitacion }}
                                </td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $alojamiento->fecha_hora_entrada }}
                                </td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $alojamiento->fecha_hora_salida }}
                                </td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $alojamiento->cliente }}
                                </td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $alojamiento->estado }}
                                </td>
                                <td scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <a href="{{route('recepciones.edit',$alojamiento->id)}}"> <button
                                             class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="">
                                             Actualizar alojamiento</button></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="flex flex-col items-center justify-center p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800 text-center"
                    role="alert">
                    <span class="flex flex-col space-x-7">
                        <svg class="h-10 w-10 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="purple"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="16" x2="12" y2="12" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                        </svg>
                    </span>
                    <div class="text-gray-500  text-2xl">
                        No hay alojamientos activos en este momento!
                    </div>
                    <button class="button">

                    </button>
                </div>
            @endif
        </div>
    </div>
@endsection
