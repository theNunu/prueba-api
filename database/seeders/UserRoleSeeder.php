<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reemplaza '2' con el id del usuario al que deseas cambiar el rol
        $user = User::find(4); 
        $user->role_id = 1; // Asigna el role_id de administrador
        $user->save();
    }
}
