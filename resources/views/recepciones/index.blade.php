@php
    use App\Models\Habitacion;
    use App\Models\Recepcion;
    use App\Models\Reservacion;

    $habitaciones = Habitacion::select('habitaciones.*', 'categorias.nombre as categoria')
        ->where('habitaciones.status', true)
        ->where('habitaciones.hoteles_id', auth()->user()->hoteles_id)
        ->join('categorias', 'categorias_id', '=', 'categorias.id')
        ->get();

@endphp
@extends('layouts.principal')
@section('contenido')
    <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white">
        Recepciones
    </h3>
    <div class="fix bg-white p-3">
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
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
            <div class="grid lg:grid-cols-6 md:grid-cols-3 sm:grid-cols-1 hidden flex p-4 rounded-lg bg-gray-50 dark:bg-gray-800 gap-2"
                id="todos" role="tabpanel" aria-labelledby="profile-tab">
                @foreach ($habitaciones as $habitacion)
                    @php
                        //obtenemos la reservación vigente
                        $today = Date('Y-m-d H:i:s');
                        //verificamos si la habitacion ocupada tiene una reservación vigente
                        //dd($today);
                        DB::enableQueryLog();
                        $reservaciones_registradas = Reservacion::where(function ($query) use ($today) {
                            $query->where([['fecha_hora_entrada', '<=', $today], ['fecha_hora_salida', '>=', $today]]);
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

                        if ($habitacion->estado == 'reservada' && $reservaciones_registradas == null) {
                            $habitacion->estado = 'libre';
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
                            case 'libre':
                                $color1 = 'bg-green-700';
                                $color2 = 'bg-green-600';
                                $texto = 'Libre';
                                break;
                            case 'ocupada':
                                $color1 = 'bg-red-700';
                                $color2 = 'bg-red-600';
                                $texto = 'Ocupada';
                                break;
                            case 'reservada':
                                $color1 = 'bg-gray-700';
                                $color2 = 'bg-gray-600';
                                $texto = 'Reservada';
                                break;
                            case 'limpieza':
                                $color1 = 'bg-blue-700';
                                $color2 = 'bg-blue-600';
                                $texto = 'En Limpieza';
                                break;
                        }
                    @endphp
                    <div
                        class="col-span-1 relative w-64 p-2 {{ $color1 }} border border-gray-200 rounded-lg shadow  ">
                        <a href="#">
                            <h5 class="mb-2 text-4xl font-bold tracking-tight text-white dark:text-white">
                                {{ $habitacion->nombre }}
                            </h5>
                        </a>
                        <p class="mb-3 font-normal text-white">{{ $habitacion->categoria }}</p>
                        <hr />
                        @if ($habitacion->estado == 'libre')
                            <form action="{{ route('recepciones.add', $habitacion->id) }}">
                                <button type="submit"
                                    class=" w-full text-center  {{ $color2 }} text-white hover:{{ $color1 }}">
                                    <p>{{ $texto }}</p>
                                </button>
                            </form>
                        @elseif ($habitacion->estado == 'ocupada')
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
                            <form action="{{ route('recepciones.edit', $recepcion->id) }}">
                                <button type="submit"
                                    class=" w-full text-center  {{ $color2 }} text-white hover:{{ $color1 }}">
                                    <p>{{ $texto }}</p>
                                </button>
                            </form>
                        @elseif($habitacion->estado == 'reservada')
                            <button type="button" data-modal-target="extralarge-modal" data-modal-toggle="extralarge-modal"
                                onclick="showinforeservacion({{ $reservaciones_registradas->id }})"
                                class=" w-full text-center  {{ $color2 }} text-white hover:{{ $color1 }}">
                                <p>{{ $texto }}</p>
                            </button>
                        @else
                            <button type="button"
                                class=" w-full text-center  {{ $color2 }} text-white hover:{{ $color1 }}">
                                <p>{{ $texto }}</p>
                            </button>
                        @endif

                    </div>
                @endforeach
            </div>

            @foreach ($niveles as $nivel)
                <div class="grid lg:grid-cols-6 md:grid-cols-3 sm:grid-cols-1 flex hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800 gap-2"
                    id="nivel{{ $nivel->id }}" role="tabpanel" aria-labelledby="nivel{{ $nivel->id }}-tab">
                    @php
                        $habitaciones = Habitacion::select('habitaciones.*', 'categorias.nombre as categoria')
                            ->where('habitaciones.status', true)
                            ->where('habitaciones.hoteles_id', auth()->user()->hoteles_id)
                            ->where('habitaciones.niveles_id', $nivel->id)
                            ->join('categorias', 'categorias_id', '=', 'categorias.id')
                            ->get();
                    @endphp
                    @if ($habitaciones->count() > 0)
                        @foreach ($habitaciones as $habitacion)
                            @php
                                //obtenemos la reservación vigente
                                $today = Date('Y-m-d H:i:s');
                                //verificamos si la habitacion ocupada tiene una reservación vigente
                                //dd($today);
                                DB::enableQueryLog();
                                $reservaciones_registradas = Reservacion::where(function ($query) use ($today) {
                                    $query->where([
                                        ['fecha_hora_entrada', '<=', $today],
                                        ['fecha_hora_salida', '>=', $today],
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

                                if ($habitacion->estado == 'reservada' && $reservaciones_registradas == null) {
                                    $habitacion->estado = 'libre';
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
                                    case 'libre':
                                        $color1 = 'bg-green-700';
                                        $color2 = 'bg-green-600';
                                        $texto = 'Libre';
                                        break;
                                    case 'ocupada':
                                        $color1 = 'bg-red-700';
                                        $color2 = 'bg-red-600';
                                        $texto = 'Ocupada';
                                        break;
                                    case 'reservada':
                                        $color1 = 'bg-gray-700';
                                        $color2 = 'bg-gray-600';
                                        $texto = 'Reservada';
                                        break;
                                    case 'limpieza':
                                        $color1 = 'bg-blue-700';
                                        $color2 = 'bg-blue-600';
                                        $texto = 'En Limpieza';
                                        break;
                                }
                            @endphp

                            <div
                                class="col-span-1 relative w-64 p-2 {{ $color1 }} border border-gray-200 rounded-lg shadow  ">
                                <a href="#">
                                    <h5 class="mb-2 text-4xl font-bold tracking-tight text-white dark:text-white">
                                        {{ $habitacion->nombre }}
                                    </h5>
                                </a>
                                <p class="mb-3 font-normal text-white">{{ $habitacion->categoria }}</p>
                                <hr />
                                @if ($habitacion->estado == 'libre')
                                    <form action="{{ route('recepciones.add', $habitacion->id) }}">
                                        <button type="submit"
                                            class=" w-full text-center  {{ $color2 }} text-white hover:{{ $color1 }}">
                                            <p>{{ $texto }}</p>
                                        </button>
                                    </form>
                                @elseif ($habitacion->estado == 'ocupada')
                                    @if ($reservaciones_registradas != null)
                                        @if ($recepcion != null && $recepcion->reservaciones_id != $reservaciones_registradas->id)
                                            <div class="absolute top-3 right-2">
                                                <span class="bg-purple-300 hover:cursor-pointer"
                                                    data-tooltip-target="tooltip-no-arrow{{ $habitacion->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="white"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
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
                                    <form action="{{ route('recepciones.edit', $recepcion->id) }}">
                                        <button type="submit"
                                            class=" w-full text-center  {{ $color2 }} text-white hover:{{ $color1 }}">
                                            <p>{{ $texto }}</p>
                                        </button>
                                    </form>
                                @elseif($habitacion->estado == 'reservada')
                                    <button type="button" data-modal-target="extralarge-modal"
                                        data-modal-toggle="extralarge-modal"
                                        onclick="showinforeservacion({{ $reservaciones_registradas->id }})"
                                        class=" w-full text-center  {{ $color2 }} text-white hover:{{ $color1 }}">
                                        <p>{{ $texto }}</p>
                                    </button>
                                @else
                                    <button type="button"
                                        class=" w-full text-center  {{ $color2 }} text-white hover:{{ $color1 }}">
                                        <p>{{ $texto }}</p>
                                    </button>
                                @endif

                            </div>
                        @endforeach
                    @else
                        <p class="text-md text-gray-500 dark:text-gray-400">No existen Habitaciones registradas en este
                            nivel!</p>
                    @endif

                </div>
            @endforeach

        </div>
    </div>
    <!-- Extra Large Modal -->
    <div id="extralarge-modal" tabindex="-1"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-4xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                        Reservación
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
                <div class="p-4 md:p-5 space-y-4" id="inforeservacion">

                </div>
                <!-- Modal footer -->
                <div
                    class="text-right items-right p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="extralarge-modal" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cerrar
                        Ventana</button>

                </div>
            </div>
        </div>
    </div>
@endsection
