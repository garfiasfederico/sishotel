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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->double("pago_total");
            $table->string("metodo_pago");
            $table->boolean(1);
            $table->foreignId("alojamientos_id")->references("id")->on("alojamientos")->onDelete('cascade');
            $table->foreignId("hoteles_id")->references("id")->on("hoteles")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
