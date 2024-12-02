@extends('layouts.principal')
@section('contenido')
<button id="btnShowModal" class="hidden" data-modal-target="default-modal" data-modal-toggle="default-modal"></button>
    <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white">Reportes</h3>
    <div class="grid xl:grid-cols-12 md:grid-cols-6 sm:grid-cols-2 w-full bg-white rounded-lg p-10">
        @csrf
        <div class="col-span-2 border-l-2 border-l-green-300 p-3">
            <label for="fecha_inicial" class="flex gap-2 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha
                Inicial<span id="procesando" class="text-green-300"></span></label>
            <input type="date" id="fecha_inicial" name="fecha_inicial" autocomplete="false" onchange="getReport()"
                class=" text-black bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
        </div>
        <div class="col-span-2 border-l-2 border-l-green-300 p-3">
            <label for="fecha_final" class="flex gap-2 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha
                Final<span id="procesando" class="text-green-300"></span></label>
            <input type="date" id="fecha_final" name="fecha_final" autocomplete="false" onchange="getReport()"
                class=" text-black bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
        </div>
        <div class="col-span-3 border-l-2 border-l-green-300 p-3">
            <label for="responsable"
                class="flex gap-2 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Responsable<span
                     class="text-green-300"></span></label>
            <select id="responsable" name="responsable" onchange="getReport()"
                class=" w-80 text-black bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <option value="">Todos</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <br />
    <div class="grid xl:grid-cols-12 md:grid-cols-6 sm:grid-cols-2 w-full bg-white rounded-lg p-10" id="resultadoconsulta">
    </div>

    <!-- Main Modal -->
<div id="default-modal" tabindex="-1" aria-hidden="true" data-modal-backdrop="static"
class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
<div class="relative p-4 w-full max-w-4xl max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Información del Alojamiento Terminado
            </h3>
            <button type="button"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                data-modal-hide="default-modal">
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
                <div class=" col-span-full" id="infoalojamiento">
                </div>
            </div>
            <!-- Modal footer -->
            <div class="text-right p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button data-modal-hide="default-modal" type="button"
                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cerrar Ventana</button>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
