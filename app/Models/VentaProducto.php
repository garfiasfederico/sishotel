<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaProducto extends Model
{
    use HasFactory;
    public $timestamps =false;
    protected $table = "venta_productos";
    protected $fillable=[
        "ventas_id",
        "productos_id",
        "precio_unitario",
        "cantidad",
        "total"
    ];
}
