<h1 class="text-2xl text-gray-400">Datos del Cliente</h1>
<div class="grid grid-cols-8 gap-6 border border-2 border-gray-400 p-3 rounded-md">
    <div class=" col-span-2 text-left">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Nombre
            del Cliente</label>
        <span>{{$reservacion->cliente}}</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Tipo de
            Documento</label>
        <span>{{$reservacion->tipo_documento}}</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Documento</label>
        <span>{{$reservacion->documento}}</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Email</label>
        <span>{{$reservacion->email}}</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Teléfono</label>
        <span>{{$reservacion->telefono}}</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">RFC</label>
        <span>{{$reservacion->rfc}}</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Razón
            Social</label>
        <span>{{$reservacion->razon_social}}</span>
    </div>
</div>
<h1 class="text-2xl text-gray-400">Datos de la Reservación</h1>
<div class="grid grid-cols-6 gap-6 border border-2 border-gray-400 p-3 rounded-md">
    <div class=" col-span-2 text-left">
        <label for="tipo"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Habitacion</label>
        <span>110</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Fecha y
            hora de Entrada</label>
        <span>{{$reservacion->fecha_hora_entrada}}</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Fecha y
            hora de Salida</label>
        <span>{{$reservacion->fecha_hora_salida}}</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Costo
            por hospedaje (24 hrs.)</label>
        <span>$ {{number_format($reservacion->precio,2)}}</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Cobro
            Extra</label>
        <span>$ {{number_format($reservacion->cobro_extra,2)}}</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Descuento</label>
        <span>$ {{number_format($reservacion->descuento,2)}}</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Adelanto</label>
        <span>$ {{number_format($reservacion->adelanto,2)}}</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Total</label>
        <span>$ {{number_format($reservacion->total_pagar,2)}}</span>
    </div>
    <div class=" col-span-2 text-left">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Metodo
            de Pago</label>
        <span>{{$reservacion->metodo_pago}}</span>
    </div>

    @php
        $color="purple";
        switch($reservacion->estado_reservacion){
                    case 'sin_confirmar':
                        $color = "bg-gray-500";
                        break;
                    case 'confirmada':
                        $color = "bg-orange-400";
                        break;
                    case 'ingreso':
                        $color = "bg-green-400";
                        break;
                    case 'no_ingreso':
                        $color = "bg-red-400";
                        break;
                    case 'terminada':
                        $color = "bg-black";
                        break;
        }
    @endphp



    <div class=" col-span-6 text-left">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white text-gray-400">Estado
            de la Reservación</label>
        <div class="w-full text-center {{$color}} text-white p-2 rounded-sm">{{ucfirst(str_replace("_"," ",$reservacion->estado_reservacion))}}</div>
    </div>
</div>
