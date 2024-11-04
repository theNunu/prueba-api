<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLogController;
use App\Http\Controllers\RoleController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::post('register', [AuthController::class, 'register']);
// Route::post('login', [AuthController::class, 'login']);

// Route::group(['middleware'=> ['auth:sanctum']], function(){
//     Route::get('user-profile', [AuthController::class, 'userProfile']);
//     Route::post('logout', [AuthController::class, 'logout']);
// });

Route::get('users', [AuthController::class, 'allUsers']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
// Rutas para la autenticación
Route::post('register', [AuthController::class, 'register']); // Registro de usuario
Route::post('login', [AuthController::class, 'login']);       // Inicio de sesión
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); // Cierre de sesión (requiere autenticación)

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    // Gestión de usuarios
    Route::get('users', [UserController::class, 'index']);      // Lista de todos los usuarios
    Route::get('users/{id}', [UserController::class, 'show']);  // Detalle de un usuario específico
    
    // Registro de actividad de usuario
    Route::get('user/{id}/logs', [UserLogController::class, 'logsByUser']); // Logs de un usuario específico (requiere permisos)

    // Gestión de roles
    
});

Route::post('rol', [RoleController::class, 'registerRole']); // Crear un nuevo rol