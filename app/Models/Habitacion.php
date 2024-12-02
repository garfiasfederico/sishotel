<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
   protected $table="habitaciones";
   protected $fillable = [
    "nombre",
    "precio",
    "tarifa",
    "detalles",
    "niveles_id",
    "categorias_id",
    "hoteles_id"
   ];
}
