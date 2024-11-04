<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserLog;

class UserLogController extends Controller
{
    // Mostrar registros de un usuario específico (requiere permisos)
    public function logsByUser($userId)
    {
        return UserLog::where('user_id', $userId)->get();
    }
}
