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
        Schema::create('alojamientos', function (Blueprint $table) {
            $table->id();
            $table->timestamp("fecha_hora_entrada");
            $table->timestamp("fecha_hora_salida");
            $table->foreignId("clientes_id")->references("id")->on("clientes")->onDelete('cascade');
            $table->foreignId("habitaciones_id")->references("id")->on("habitaciones")->onDelete('cascade');
            $table->foreignId("hoteles_id")->references("id")->on("hoteles")->onDelete('cascade');
            $table->foreignId("users_id")->references("id")->on("users")->onDelete('cascade');
            $table->double("descuento")->default(0);
            $table->double("cobro_extra")->default(0);
            $table->double("adelanto")->default(0);
            $table->double("mora")->default(0);
            $table->double("pago")->default(0);
            $table->double("total_pagar")->default(0);
            $table->string("metodo_pago")->nullable();
            $table->string("observaciones")->nullable();
            $table->string("estado");
            $table->boolean("status")->default(1);
            $table->bigInteger("reservaciones_id",false,true)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alojamientos');
    }
};
