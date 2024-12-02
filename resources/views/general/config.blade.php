@extends('layouts.principal')
@section('contenido')
@if(isset($message))
<div id="message" class="hidden fixed right-5 p-4 mb-4 text-sm @if($type="success") text-green-800 rounded-lg bg-green-200 @else text-red-800 rounded-lg bg-red-200 @endif" role="alert">
    <span class="font-medium">Éxito!</span> {{$message}}.
  </div>
@endif

<h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white">
   Configuración General
</h3>
<div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
    <h3 class="mb-4 text-xl font-semibold">Datos Generales</h3>
    <form action="{{ route('configstore') }}" novalidate method="POST" id="configform" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="id"
            value="{{ $hotel->id }}">
        <div class="grid grid-cols-6 gap-6">
            @if (isset($hotel))
                @if ($hotel->logo != null)
                    <div class="flex items-center text-center col-span-full  justify-center">
                        <img class="w-1/4" src="{{{asset('uploads/hoteles').'/'.$hotel->logo}}}"/>
                    </div>
                @endif
            @endif
            <div class="col-span-full gap-6">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Logo</label>
                <input type="file" name="logo" id="logo"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="example@company.com"
                    @if (isset($hotel)) value="{{ old('logo', $hotel->logo) }}" @else value="{{ old('logo') }}" @endif
                    accept="image/png, image/jpeg, image/svg">
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre Completo del Hotel
                    <span class="text-red-500">*</span></label>
                <input type="text" name="nombre" id="nombre"
                    class="shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Nombre completo de Usuario Administrador" required
                    @if (isset($hotel)) value="{{ old('nombre', $hotel->nombre) }}" @else value="{{ old('nombre') }}" @endif>
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="ubicacion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ubicación<span class="text-red-500">*</span></label>
                <input type="text" name="ubicacion" id="ubicacion"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="" required
                    @if (isset($hotel)) value="{{ old('ubicacion', $hotel->ubicacion) }}" @else value="{{ old('ubicacion') }}" @endif>
                    <x-input-error :messages="$errors->get('ubicacion')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="encargado" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Encargado<span class="text-red-500">*</span></label>
                <input type="text" name="encargado" id="encargado"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="" required
                    @if (isset($hotel)) value="{{ old('encargado', $hotel->encargado) }}" @else value="{{ old('encargado') }}" @endif>
                <x-input-error :messages="$errors->get('encargado')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="country" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email<span
                        class="text-red-500">*</span></label>
                <input type="text" name="email" id="email"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="ejemplo@ejemplo.com" required
                    @if (isset($hotel)) value="{{ old('email', $hotel->email) }}" @else value="{{ old('email') }}" @endif>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telefono<span
                        class="text-red-500">*</span></label>
                <input type="text" name="telefono" id="telefono"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="" required
                    @if (isset($hotel)) value="{{ old('telefono', $hotel->telefono) }}" @else value="{{ old('telefono') }}" @endif>
                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo
                    Moneda</label>
                <select name="tipo_moneda" id="tipo_moneda"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    required>
                    <option value="MXN" @if (isset($hotel)) @selected($hotel->tipo_moneda == 'MXN') @endif>Peso
                        Mexicano</option>
                    <option value="USD" @if (isset($hotel)) @selected($hotel->tipo_moneda == 'USD') @endif>Dolar
                        Estadounidense</option>
                    <option value="EUR" @if (isset($hotel)) @selected($hotel->tipo_moneda == 'EUR') @endif>Euro
                    </option>
                </select>
            </div>
            <div class="col-span-6 sm:col-full text-right">
                <a href="{{ route('dashboard') }}">
                    <button
                        class="text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                        type="button">Cancelar</button></a>
                <button
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                    type="submit">
                        Almacenar información
                </button>

            </div>
        </div>
    </form>
</div>



@endsection
