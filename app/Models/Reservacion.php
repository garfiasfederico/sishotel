<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    protected $table = "reservaciones";
    protected $fillable = [
        "fecha_hora_entrada",
        "fecha_hora_salida",
        "clientes_id",
        "habitaciones_id",
        "hoteles_id",
        "descuento",
        "cobro_extra",
        "adelanto",
        "total_pagar",
        "metodo_pago",
        "observaciones",
        "estado",
        "users_id"
    ];
}
