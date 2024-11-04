<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Obtener todos los usuarios
    public function index()
    {
        return User::all();
    }

    // Mostrar un usuario específico
    public function show($id)
    {
        return User::findOrFail($id);
    }
}
