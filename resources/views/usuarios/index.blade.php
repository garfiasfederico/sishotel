@extends('layouts.principal')
@section('contenido')
<h3 class="mb-4 text-xl font-semibold text-gray-500 dark:text-white">
    @if (!isset($usuario))
        Registro de Usuarios
    @else
        Actualizar Usuario
    @endif
</h3>
<div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
    <h3 class="mb-4 text-xl font-semibold dark:text-white">Datos Generales</h3>
    <form action="{{ route('usuarios.store') }}" novalidate method="POST" id="formUsuario" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="id"
            @if (isset($usuario)) value="{{ $usuario->id }}" @else value="" @endif>
        <div class="grid grid-cols-6 gap-6">
            <div class="col-span-6 sm:col-span-3">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre Completo
                    <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name"
                    class="shadow-sm bg-gray-50 border border-gray-300  text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Nombre completo de Usuario Administrador" required
                    @if (isset($usuario)) value="{{ old('name', $usuario->name) }}" @else value="{{ old('name') }}" @endif>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="fecha_nacimiento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="" required
                    @if (isset($usuario)) value="{{ old('fecha_nacimiento', $usuario->fecha_nacimiento) }}" @else value="{{ old('fecha_nacimiento') }}" @endif>
                <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="country" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email<span
                        class="text-red-500">*</span></label>
                <input type="text" name="email" id="email"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="ejemplo@ejemplo.com" required
                    @if (isset($usuario)) value="{{ old('email', $usuario->email) }}" @else value="{{ old('email') }}" @endif>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telefono<span
                        class="text-red-500">*</span></label>
                <input type="text" name="telefono" id="telefono"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="" required
                    @if (isset($usuario)) value="{{ old('telefono', $usuario->telefono) }}" @else value="{{ old('telefono') }}" @endif>
                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="direccion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dirección</label>
                <input type="text" name="direccion" id="direccion"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="" required
                    @if (isset($usuario)) value="{{ old('direccion', $usuario->direccion) }}" @else value="{{ old('direccion') }}" @endif>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hotel Asociado</label>
                <select name="hoteles_id" id="hoteles_id"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    required>
                    <option value="">--Seleccione</option>
                    @foreach ($hoteles as $hotel )
                        <option value="{{$hotel->id}}" @if (isset($usuario)) @selected($usuario->hoteles_id == $hotel->id) @endif>{{$hotel->nombre}}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('hoteles_id')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="cuenta" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cuenta<span
                        class="text-red-500">*</span></label>
                <input type="text" name="cuenta" id="cuenta"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="" required
                    @if (isset($usuario)) value="{{ old('cuenta', $usuario->cuenta) }}" @else value="{{ old('cuenta') ?? "SIHOTEL.".Str::random("4") }}" @endif>
                    <x-input-error :messages="$errors->get('cuenta')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password<span
                        class="text-red-500">*</span></label>
                <input type="text" name="password" id="password"
                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="" required
                    @if (isset($usuario)) value="{{ old('password', $usuario->password_enc) }}" @else value="{{ old('password') ?? Str::random("10") }}" @endif>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-full text-right">
                <a href="{{ route('usuarios.list') }}">
                    <button
                        class="text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                        type="button">Cancelar</button></a>
                <button
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                    type="submit">
                    @if (!isset($usuario))
                        Generar Usuario
                    @else
                        Actualizar Datos del Usuario
                    @endif
                </button>

            </div>
        </div>
    </form>
</div>
@endsection
