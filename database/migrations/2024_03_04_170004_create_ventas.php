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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string("cuando_paga");
            $table->string("estado");
            $table->boolean("status")->default(1);
            $table->string("metodo_pago");
            $table->double("total");
            $table->bigInteger("alojamientos_id",false,true)->nullable();
            $table->foreignId("hoteles_id")->references("id")->on("hoteles")->onDelete('cascade');
            $table->foreignId("users_id")->references("id")->on("users")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
