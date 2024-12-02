<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = "clientes";
    protected $fillable = [
        "nombre",
        "tipo_documento",
        "documento",
        "rfc",
        "razon_social",
        "email",
        "telefono",
        "hoteles_id"
    ];
}
