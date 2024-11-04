<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Laravel\Sanctum\PersonalAccessToken;

class AdminController extends Controller
{
    public function viewUserSessions($userId)
    {
        // Verifica que el usuario sea administrador
        if (auth()->user()->role_id !== 1) {
            return response()->json(['message' => 'Acceso no autorizado.'], 403);
        }

        // Obtener el usuario y sus tokens de inicio de sesión
        $user = User::findOrFail($userId);
        $sessions = PersonalAccessToken::where('tokenable_id', $user->id)
            ->where('tokenable_type', User::class)
            ->get(['id', 'name', 'last_used_at', 'created_at', 'expires_at']);

        return response()->json([
            'user' => $user->only(['id', 'username', 'email']),
            'sessions' => $sessions
        ]);
    }
}
