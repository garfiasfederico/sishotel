@php
    $total = 0;
    foreach ($alojamientos as $alojamiento) {
        $total += $alojamiento->total_pagar;
    }
@endphp
<div class="col-span-12 text-right">
    @if ($alojamientos->count() > 0)
        <form action="{{ route('reportes.filteringexcel') }}" method="GET" target="_blank">
            <input type="hidden" name="fecha_inicial" value="{{ $fecha_inicial }}" />
            <input type="hidden" name="fecha_final" value="{{ $fecha_final }}" />
            <input type="hidden" name="responsable" value="{{ $responsable }}" />
            <button
                class=" text-white bg-orange-700 hover:bg-orange-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Descargar
                Reporte</button>
        </form>
    @endif
</div>
<div class="col-span-6 p-4 text-center text-2xl">
    Total del periodo: <span class="text-green-600">$ {{ number_format($total, 2) }}</span>
</div>
<div class="col-span-6 p-4 text-center text-2xl">
    Hospedajes registrados: <span class="text-green-600">{{ $alojamientos->count() }}</span>
</div>
<div class="xl:col-span-12 md:col-span-6 sm:col-span-2">
    <h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white">Resultado</h3>
    <div class="xl:col-span-12 md:col-span-6 sm:col-span-2">
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
                        <th scope="col" class="px-6 py-3 ">
                            Total Pago
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
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                                $ {{ number_format($alojamiento->total_pagar, 2) }}
                            </td>
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <button data-modal-show="default-modal" onclick="showModalInfo({{$alojamiento->id}})"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                    style="">
                                    Ver Info</button>
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
                    No hay Alojamientos registrados!
                </div>
                <button class="button">

                </button>
            </div>
        @endif
    </div>
</div>

