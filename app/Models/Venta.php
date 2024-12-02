<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table="ventas";
    protected $fillable=[
        "cuando_paga",
        "estado",
        "metodo_pago",
        "total",
        "alojamiento_id",
        "hoteles_id",
        "users_id"
    ];
}
