<?php

use App\Models\Categoria;
use App\Models\Recepcion;
use App\Models\Habitacion;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\ReservacionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    $habitaciones = Habitacion::where("status",true)->where("hoteles_id",auth()->user()->hoteles_id)->get();
    $alojamientos = Recepcion::select("alojamientos.*","habitaciones.nombre as habitacion","clientes.nombre as cliente")
                    ->join("habitaciones","habitaciones.id","=","alojamientos.habitaciones_id")
                    ->join("clientes","clientes.id","=","alojamientos.clientes_id")
                    ->where("alojamientos.status",true)
                    ->where("alojamientos.hoteles_id",auth()->user()->hoteles_id)
                    ->where("alojamientos.estado","<>","terminada")
                    ->get();
    return view('dashboard')->with("habitaciones",$habitaciones)->with("alojamientos",$alojamientos);
})->middleware(['auth', 'verified','blocked'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::middleware('blocked')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/nopermitido', [GeneralController::class, 'nopermitido'])->name('nopermitido');

    Route::middleware('super')->group(function () {

    Route::get('/hoteles', [HotelController::class, 'index'])->name('hoteles');
    Route::post('/hoteles', [HotelController::class, 'store'])->name('hoteles.store');
    Route::get('/hoteles/listado', [HotelController::class, 'list'])->name('hoteles.list');
    Route::get('/hoteles/edit/{id}', [HotelController::class, 'edit'])->name('hoteles.edit');
    Route::post('/hoteles/destroy', [HotelController::class, 'destroy'])->name('hoteles.destroy');

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/list', [UsuarioController::class, 'list'])->name('usuarios.list');
    Route::get('/usuarios/edit/{id}', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::post('/usuarios/destroy', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    });





    Route::get('/productos', [ProductoController::class, 'index'])->name('productos');
    Route::post('/productos/store', [ProductoController::class, 'store'])->name('productos.store');
    Route::post('/productos/destroy', [ProductoController::class, 'destroy'])->name('productos.destroy');
    Route::get('/productos/{nombre}', [ProductoController::class, 'getproductosbynombre'])->name('productos.getbyname');
    Route::get('/producto/{id}', [ProductoController::class, 'getrowproducto'])->name('producto.row');

    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas');
    Route::post('/ventas/store', [VentaController::class, 'store'])->name('ventas.store');
    Route::get('/ventas/imprimeticket/{id}', [VentaController::class, 'imprimeticket'])->name('ventas.imprimeticket');

    Route::get('/reservaciones', [ReservacionController::class, 'index'])->name('reservaciones');
    Route::get('/reservaciones/all', [ReservacionController::class, 'all'])->name('reservaciones.all');
    Route::post('/reservaciones/store', [ReservacionController::class, 'store'])->name('reservaciones.store');
    Route::get('/reservaciones/getinfo/{id}', [ReservacionController::class, 'getinfo'])->name('reservaciones.getinfo');
    Route::get('/reservaciones/showinfo/{id}', [ReservacionController::class, 'showinfo'])->name('reservaciones.showinfo');
    Route::post('/reservaciones/destroy', [ReservacionController::class, 'destroy'])->name('reservaciones.destroy');




    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes');
    Route::post('/clientes/store', [ClienteController::class, 'store'])->name('clientes.store');
    Route::post('/clientes/destroy', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    Route::get('/clientes/getinfo/{id}', [ClienteController::class, 'getinfo'])->name('clientes.getinfo');

    Route::get('/recepciones', [RecepcionController::class, 'index'])->name('recepciones');
    Route::post('/recepciones/store', [RecepcionController::class, 'store'])->name('recepciones.store');
    Route::get('/recepciones/add/{habitaciones_id}', [RecepcionController::class, 'add'])->name('recepciones.add');
    Route::get('/recepciones/edit/{id}', [RecepcionController::class, 'edit'])->name('recepciones.edit');
    Route::post('/recepciones/destroy', [RecepcionController::class, 'destroy'])->name('recepciones.destroy');

    Route::get('/salidas', [RecepcionController::class, 'salidas'])->name('recepciones.salidas');
    Route::get('/salida/edit/{id}', [RecepcionController::class, 'salida'])->name('recepciones.salida');
    Route::post('/salida/terminar', [RecepcionController::class, 'terminar'])->name('recepciones.terminar');
    Route::get('/salida/imprime/{id}', [RecepcionController::class, 'imprime'])->name('recepciones.imprime');

    Route::middleware('admin')->group(function () {

    Route::post('/usuarios/updatestatus', [UsuarioController::class, 'updatestatus'])->name('usuarios.updatestatus');

    Route::get('/niveles', [NivelController::class, 'index'])->name('niveles');
    Route::post('/niveles/add', [NivelController::class, 'add'])->name('niveles.add');
    Route::post('/niveles/destroy', [NivelController::class, 'destroy'])->name('niveles.destroy');

    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias');
    Route::post('/categorias/add', [CategoriaController::class, 'add'])->name('categorias.add');
    Route::post('/categorias/destroy', [CategoriaController::class, 'destroy'])->name('categorias.destroy');

    Route::get('/habitaciones', [HabitacionController::class, 'index'])->name('habitaciones');
    Route::post('/habitaciones/store', [HabitacionController::class, 'store'])->name('habitaciones.store');
    Route::post('/habitaciones/destroy', [HabitacionController::class, 'destroy'])->name('habitaciones.destroy');

    Route::get('/usuariosinternos', [UsuarioController::class, 'usuariosinternos'])->name('usuariosinternos');
    Route::post('/usuariosinternos/store', [UsuarioController::class, 'storeusuariosinternos'])->name('usuariosinternos.store');
    Route::post('/usuariosinternos/destroy', [UsuarioController::class, 'destroyusuariosinternos'])->name('usuariosinternos.destroy');

    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes');
    Route::post('/reportes/filtering', [ReporteController::class, 'filtering'])->name('reportes.filtering');
    Route::get('/reportes/filteringexcel', [ReporteController::class, 'filteringexcel'])->name('reportes.filteringexcel');
    Route::get('/reportes/getinfoalojamiento/{id}', [ReporteController::class, 'getinfoalojamiento'])->name('reportes.getinfoalojamiento');

    Route::get('/config', [GeneralController::class, 'config'])->name('config');
    Route::post('/config', [GeneralController::class, 'configstore'])->name('configstore');
    });



});


});

require __DIR__.'/auth.php';
