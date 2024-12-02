<tr id="producto{{ $producto->id }}"
    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
    <td class="w-4 p-4 hidden">
        <div class="flex items-center">
            <input id="checkbox-table-search-1" type="checkbox"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
        </div>
    </td>
    <td scope="row"
        class="  px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white id-p">
        {{ $producto->id }}
    </td>
    <td scope="row"
        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white nombre-p" >
        {{ $producto->nombre }}
    </td>
    <td class="px-6 py-4 tipo-p">
        {{ $producto->tipo }}
    </td>
    <td class="px-6 py-4 precio_unitario-p  text-gray-900 text-right">
        {{ number_format($producto->precio_unitario,2) }}
    </td>
    <td class="px-6 py-4">
        <input type="number" onchange="actualizaDataVenta()" class="cantidad-p text-right w-20  text-black bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" value="1"/>
    </td>
    <td class="px-6 py-4 total-p  text-gray-900 text-right">

    </td>
    <td class="items-center px-6 py-4 mr-2">
        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
            onclick="deleteProducto({{ $producto->id }})"
            id="btnDeleteProducto{{ $producto->id }}">Eliminar</button>
    </td>
</tr>
