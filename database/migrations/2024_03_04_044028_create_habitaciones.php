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
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->double("precio");
            $table->string("tarifa")->nullable();
            $table->string("detalles")->nullable();
            $table->boolean("status")->default(1);
            $table->string("estado")->default("libre");
            $table->foreignId("niveles_id")->references("id")->on("niveles")->onDelete('cascade');
            $table->foreignId("categorias_id")->references("id")->on("categorias")->onDelete('cascade');
            $table->foreignId("hoteles_id")->references("id")->on("hoteles")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};
