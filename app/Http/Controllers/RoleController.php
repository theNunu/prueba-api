<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    // Crear un rol
    public function registerRole(Request $request)
    {
        // $request->validate(['name' => 'required|string|unique:roles']);

        // Role::create(['name' => $request->name]);

        // return response()->json(['message' => 'Rol creado exitosamente']);
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Rol creado exitosamente',
            'role' => $role
        ], 201);
    }
}
