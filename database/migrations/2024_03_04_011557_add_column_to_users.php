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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string("cuenta")->nullable();
            $table->date("fecha_nacimiento")->nullable();
            $table->string("telefono")->nullable();
            $table->text("direccion")->nullable();
            $table->boolean("status")->default(1);
            $table->string("foto")->nullable();
            $table->integer("hoteles_id")->nullable();
            $table->string("password_enc")->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("cuenta");
            $table->dropColumn("fecha_nacimiento");
            $table->dropColumn("telefono");
            $table->dropColumn("direccion");
            $table->dropColumn("status");
            $table->dropColumn("foto");
            $table->dropColumn("hoteles_id");
        });
    }
};
