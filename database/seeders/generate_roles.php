<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class generate_roles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rol::create(["name"=>"super","guard_name"=>"web"]);
        Rol::create(["name"=>"administrador","guard_name"=>"web"]);
        Rol::create(["name"=>"empleado","guard_name"=>"web"]);
        Rol::create(["name"=>"limpieza","guard_name"=>"web"]);

        $usuario = new User();
        $usuario->name = "Super Arministrador";
        $usuario->email = "garfias.federico@gmail.com";
        $usuario->password = Hash::make("f3d3r1k087");
        $usuario->cuenta = "SIHOTEL.SUPER";
        $usuario->telefono  = "9511141737";
        $usuario->direccion = "Conocido";
        $usuario->save();
        $usuario->assignRole("super");


    }
}
