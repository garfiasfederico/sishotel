<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('venta_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId("productos_id")->references("id")->on("productos")->onDelete('cascade');
            $table->foreignId("ventas_id")->references("id")->on("ventas")->onDelete('cascade');
            $table->double("precio_unitario");
            $table->double("cantidad");
            $table->double("total");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_productos');
    }
};
