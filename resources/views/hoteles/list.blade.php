@extends('layouts.principal')
@section('contenido')
    <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white">Listado de Hoteles</h3>
    <div class="flex justify-end">
        <a href="{{route('hoteles')}}" class="justified-end font-medium text-greed-600 dark:text-blue-500 hover:underline"><button
                class="bg-green-800 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Nuevo Hotel</button></a>
    </div>
    @if ($hoteles->count() > 0)
        @csrf
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-5">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="tableHoteles">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="p-4 hidden">
                            <div class="flex items-center">
                                <input id="checkbox-all-search" type="checkbox"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox-all-search" class="sr-only">checkbox</label>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Encargado
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Ubicacion
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Teléfono
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hoteles as $key => $hotel)
                        <tr id="hotel{{$hotel->id}}"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="w-4 p-4 hidden">
                                <div class="flex items-center">
                                    <input id="checkbox-table-search-1" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                </div>
                            </td>
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $hotel->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $hotel->nombre }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $hotel->encargado }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $hotel->ubicacion }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $hotel->email }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $hotel->telefono }}
                            </td>
                            <td class="flex items-center px-6 py-4">
                                <a href="{{route("hoteles.edit",$hotel->id)}}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><button
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Editar</button></a>
                                <a href="#"
                                    class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3"><button
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteHotel({{$hotel->id}},'{{$hotel->nombre}}')">Eliminar</button></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-5">
                {{$hoteles->links('pagination::tailwind')}}
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
                No existen Hoteles Registrados!
            </div>
            <button class="button">

            </button>
        </div>
    @endif
@endsection
