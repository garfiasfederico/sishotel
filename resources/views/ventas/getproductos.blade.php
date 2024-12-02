@if ($productos->count() > 0)
@foreach($productos as $producto)
    <p onclick="getRowProducto({{$producto->id}})" class=" p-1 border-dotted border-b-2 text-green-700 border-sky-200 hover:bg-gray-200 cursor-pointer">{{$producto->nombre}}</p>
@endforeach
@else
    <p class="p-1 border-dotted border-b-2 text-orange-500 border-sky-200 hover:bg-gray-200 cursor-pointer">No existe el
        producto buscado</p>
@endif
