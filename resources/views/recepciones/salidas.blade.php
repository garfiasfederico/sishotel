@php
    use App\Models\Habitacion;
    use App\Models\Recepcion;
    use App\Models\Reservacion;

    $habitaciones = Habitacion::select('habitaciones.*', 'categorias.nombre as categoria')
        ->where('habitaciones.status', true)
        ->where('habitaciones.hoteles_id', auth()->user()->hoteles_id)
        ->where('habitaciones.estado', 'ocupada')
        ->join('categorias', 'categorias_id', '=', 'categorias.id')
        ->get();

@endphp
@extends('layouts.principal')
@section('contenido')
    <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white">
        Alojamientos Activos
    </h3>
    <div class="fix bg-white p-3">
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <div class="text-right items-end">
                <button data-modal-target="alojamientos-list" data-modal-toggle="alojamientos-list"
                    class=" text-white bg-orange-700 hover:bg-orange-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Historial
                    de Alojamientos</button>
            </div>
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab"
                data-tabs-toggle="#default-tab-content" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-tab" data-tabs-target="#todos"
                        type="button" role="tab" aria-controls="todos" aria-selected="false">Todos</button>
                </li>

                @foreach ($niveles as $nivel)
                    <li class="me-2" role="presentation">
                        <button
                            class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                            id="nivel{{ $nivel->id }}-tab" data-tabs-target="#nivel{{ $nivel->id }}" type="button"
                            role="tab" aria-controls="nivel{{ $nivel->id }}"
                            aria-selected="false">{{ $nivel->nombre }}</button>
                    </li>
                @endforeach



            </ul>
        </div>

        <div id="default-tab-content">
            <div class="hidden flex p-4 rounded-lg bg-gray-50 dark:bg-gray-800 gap-2" id="todos" role="tabpanel"
                aria-labelledby="profile-tab">
                @if ($habitaciones->count() > 0)
                    @foreach ($habitaciones as $habitacion)
                        @php
                            //obtenemos la reservación vigente
                            $today = Date('Y-m-d');
                            //verificamos si la habitacion ocupada tiene una reservación vigente
                            //dd($today);
                            DB::enableQueryLog();
                            $reservaciones_registradas = Reservacion::where(function ($query) use ($today) {
                                $query->where([
                                    ['fecha_hora_entrada', '<=', $today . ' 12:30:00'],
                                    ['fecha_hora_salida', '>=', $today . ' 12:00:00'],
                                ]);
                            })
                                ->where([
                                    ['status', true],
                                    ['habitaciones_id', $habitacion->id],
                                    ['hoteles_id', auth()->user()->hoteles_id],
                                    ['estado', '<>', 'terminada'],
                                    ['estado', '<>', 'no_ingreso'],
                                ])
                                ->first();

                            if ($habitacion->estado != 'ocupada' && $reservaciones_registradas != null) {
                                $habitacion->estado = 'reservada';
                                $habitacion->save();
                            }

                            //dd(DB::getQueryLog());
                            $recepcion = Recepcion::where('status', true)
                                ->where('estado', 'ingreso')
                                ->where('habitaciones_id', $habitacion->id)
                                ->first();

                            $color1 = 'bg-black';
                            $color2 = 'bg-gray-700';
                            $texto = 'No Definido';
                            switch ($habitacion->estado) {
                                case 'ocupada':
                                    $color1 = 'bg-red-700';
                                    $color2 = 'bg-red-600';
                                    $texto = 'Registrar Salida';
                                    break;
                            }
                        @endphp
                        <div class="relative w-64 p-2 {{ $color1 }} border border-gray-200 rounded-lg shadow  ">
                            <a href="#">
                                <h5 class="mb-2 text-4xl font-bold tracking-tight text-white dark:text-white">
                                    {{ $habitacion->nombre }}
                                </h5>
                            </a>
                            <p class="mb-3 font-normal text-white">{{ $habitacion->categoria }}</p>
                            <hr />
                            @if ($reservaciones_registradas != null)
                                @if ($recepcion != null && $recepcion->reservaciones_id != $reservaciones_registradas->id)
                                    <div class="absolute top-3 right-2">
                                        <span class="bg-purple-300 hover:cursor-pointer"
                                            data-tooltip-target="tooltip-no-arrow">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                                            </svg>

                                        </span>
                                        <div id="tooltip-no-arrow" role="tooltip"
                                            class=" w-64 absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            Está habitación está reservada para día de hoy, sin embargo se encuentra
                                            ocupada,
                                            favor de registrar la liberación de la misma.
                                        </div>
                                    </div>
                                @endif
                            @endif
                            <form action="{{ route('recepciones.salida', $recepcion->id) }}">
                                <button type="submit"
                                    class=" w-full text-center  {{ $color2 }} text-white hover:{{ $color1 }}">
                                    <p>{{ $texto }}</p>
                                </button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <p class="text-md text-gray-500 dark:text-gray-400">No existen Habitaciones con alojamiento activo!</p>
                @endif
            </div>

            @foreach ($niveles as $nivel)
                <div class="flex hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800 gap-2" id="nivel{{ $nivel->id }}"
                    role="tabpanel" aria-labelledby="nivel{{ $nivel->id }}-tab">
                    @php
                        $habitaciones = Habitacion::select('habitaciones.*', 'categorias.nombre as categoria')
                            ->where('habitaciones.status', true)
                            ->where('habitaciones.hoteles_id', auth()->user()->hoteles_id)
                            ->where('habitaciones.estado', 'ocupada')
                            ->where('habitaciones.niveles_id', $nivel->id)
                            ->join('categorias', 'categorias_id', '=', 'categorias.id')
                            ->get();
                    @endphp
                    @if ($habitaciones->count() > 0)
                        @foreach ($habitaciones as $habitacion)
                            @php
                                //obtenemos la reservación vigente
                                $today = Date('Y-m-d');
                                //verificamos si la habitacion ocupada tiene una reservación vigente
                                //dd($today);
                                DB::enableQueryLog();
                                $reservaciones_registradas = Reservacion::where(function ($query) use ($today) {
                                    $query->where([
                                        ['fecha_hora_entrada', '<=', $today . ' 12:30:00'],
                                        ['fecha_hora_salida', '>=', $today . ' 12:00:00'],
                                    ]);
                                })
                                    ->where([
                                        ['status', true],
                                        ['habitaciones_id', $habitacion->id],
                                        ['hoteles_id', auth()->user()->hoteles_id],
                                        ['estado', '<>', 'terminada'],
                                        ['estado', '<>', 'no_ingreso'],
                                    ])
                                    ->first();

                                if ($habitacion->estado != 'ocupada' && $reservaciones_registradas != null) {
                                    $habitacion->estado = 'reservada';
                                    $habitacion->save();
                                }

                                //dd(DB::getQueryLog());
                                $recepcion = Recepcion::where('status', true)
                                    ->where('estado', 'ingreso')
                                    ->where('habitaciones_id', $habitacion->id)
                                    ->first();
                                $color1 = 'bg-black';
                                $color2 = 'bg-gray-700';
                                $texto = 'No Definido';
                                switch ($habitacion->estado) {
                                    case 'ocupada':
                                        $color1 = 'bg-red-700';
                                        $color2 = 'bg-red-600';
                                        $texto = 'Registar Salida';
                                        break;
                                }
                            @endphp

                            <div class="relative w-64 p-2 {{ $color1 }} border border-gray-200 rounded-lg shadow  ">
                                <a href="#">
                                    <h5 class="mb-2 text-4xl font-bold tracking-tight text-white dark:text-white">
                                        {{ $habitacion->nombre }}
                                    </h5>
                                </a>
                                <p class="mb-3 font-normal text-white">{{ $habitacion->categoria }}</p>
                                <hr />
                                @if ($reservaciones_registradas != null)
                                    @if ($recepcion != null && $recepcion->reservaciones_id != $reservaciones_registradas->id)
                                        <div class="absolute top-3 right-2">
                                            <span class="bg-purple-300 hover:cursor-pointer"
                                                data-tooltip-target="tooltip-no-arrow{{ $habitacion->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                                                </svg>

                                            </span>
                                            <div id="tooltip-no-arrow{{ $habitacion->id }}" role="tooltip"
                                                class=" w-64 absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                Está habitación está reservada para día de hoy, sin embargo se encuentra
                                                ocupada,
                                                favor de registrar la liberación de la misma.
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                <form action="{{ route('recepciones.salida', $recepcion->id) }}">
                                    <button type="submit"
                                        class=" w-full text-center  {{ $color2 }} text-white hover:{{ $color1 }}">
                                        <p>{{ $texto }}</p>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @else
                        <p class="text-md text-gray-500 dark:text-gray-400">No existen Habitaciones con alojamiento activo
                            en este
                            nivel!</p>
                    @endif

                </div>
            @endforeach

        </div>
    </div>
    <div id="alojamientos-list" tabindex="-1"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-7xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                        Historial de Alojamientos
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="alojamientos-list">
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
                    @if ($alojamientos->count() > 0)
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
                                    <th scope="col" class="px-6 py-3 hidden">
                                        Id
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Entrada
                                    </th>
                                    <th scope="col" class="px-6 py-3 ">
                                        Salida
                                    </th>
                                    <th scope="col" class="px-6 py-3 ">
                                        Cliente
                                    </th>
                                    <th scope="col" class="px-6 py-3 ">
                                        Habitacion
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Total
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Metodo de Pago
                                    </th>
                                    <th scope="col" class="px-6 py-3">
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
                                        <td class="w-4 p-4 hidden">
                                            <div class="flex items-center">
                                                <input id="checkbox-table-search-1" type="checkbox"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                            </div>
                                        </td>
                                        <td scope="row"
                                            class=" hidden px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $alojamiento->id }}
                                        </td>
                                        <td scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $alojamiento->fecha_hora_entrada }}
                                        </td>
                                        <td scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white ">
                                            {{ $alojamiento->fecha_hora_salida }}
                                        </td>
                                        <td scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white ">
                                            {{ $alojamiento->cliente }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $alojamiento->habitacion }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $alojamiento->total_pagar }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $alojamiento->metodo_pago }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $alojamiento->estado }}
                                        </td>
                                        <td class="items-center px-6 py-4 mr-2" align="center">
                                           <a href="{{route('recepciones.imprime',$alojamiento->id)}}" target="_blank"> <button
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="">
                                                Comprobante</button></a>
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
                                No existen Alojamientos Registrados!
                            </div>
                            <button class="button">

                            </button>
                        </div>
                    @endif
                </div>
                <!-- Modal footer -->
                <div
                    class="text-right items-right p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="alojamientos-list" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cerrar
                        Ventana</button>

                </div>
            </div>
        </div>
    </div>
@endsection
