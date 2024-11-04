<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

//use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validación de datos
        $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'identification' => 'required|digits:10|unique:users|regex:/^(?!.*(\d)\1{3})\d{10}$/',
            'username' => 'required|string|unique:users|min:8|max:20|regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]+$/',
            'password' => 'required|min:8|regex:/^(?=.*[A-Z])(?=.*\W)(?!.*\s).+$/',
            'role_id' => 'required|exists:roles,id',
            'status' => 'in:active,blocked', // Asegúrate de permitir estos valores
        ]);

        // Generar correo electrónico
        $firstInitial = strtolower(substr($request->name, 0, 1));
        $lastName = strtolower(explode(' ', $request->lastname)[0]);
        $email = $firstInitial . $lastName . '@gmail.com';

        // Alta del usuario
        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $email,
            'password' => Hash::make($request->password),
            'identification' => $request->identification,
            'role_id' => $request->role_id,
            'status' => $request->status ?? 'active', // Usar 'active' como valor por defecto
        ]);

        return response()->json(['message' => 'Usuario registrado exitosamente', 'email' => $email], HttpFoundationResponse::HTTP_CREATED);
    }

    public function login(request $request)
    {
         // Validación de credenciales
    // $credentials = $request->validate([
    //     'email' => ['required', 'email'],
    //     'password' => ['required']
    // ]);

    // // Busca al usuario por su correo
    // $user = User::where('email', $credentials['email'])->first();

    // // Verifica si el usuario está bloqueado
    // if ($user && $user->is_blocked) {
    //     return response()->json(['message' => 'Tu cuenta está bloqueada debido a intentos de inicio de sesión fallidos.'], HttpFoundationResponse::HTTP_FORBIDDEN);
    // }

    // // Intenta autenticar al usuario
    // if (FacadesAuth::attempt($credentials)) {
    //     // Reiniciar los intentos de inicio de sesión
    //     $user->login_attempts = 0;
    //     $user->is_blocked = false; // Asegurarse de que no esté bloqueado
    //     $user->save();

    //     $token = $user->createToken('token')->plainTextToken;
    //     return response()->json(["token" => $token], HttpFoundationResponse::HTTP_OK);
    // } else {
    //     // Incrementa el contador de intentos de inicio de sesión
    //     if ($user) {
    //         $user->login_attempts++;
    //         if ($user->login_attempts >= 3) {
    //             $user->is_blocked = true; // Bloquear al usuario
    //         }
    //         $user->save();
    //     }

    //     return response()->json(["message" => "Credenciales no válidas"], HttpFoundationResponse::HTTP_UNAUTHORIZED);
    // }
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required']
    ]);

    // Busca al usuario por su correo
    $user = User::where('email', $credentials['email'])->first();

    // Verifica si el usuario está bloqueado
    if ($user && $user->is_blocked) {
        return response()->json(['message' => 'Tu cuenta está bloqueada debido a intentos de inicio de sesión fallidos.'], HttpFoundationResponse::HTTP_FORBIDDEN);
    }

    // Intenta autenticar al usuario
    if (FacadesAuth::attempt($credentials)) {
        // Reiniciar los intentos de inicio de sesión
        $user->login_attempts = 0;
        $user->is_blocked = false; // Asegurarse de que no esté bloqueado
        $user->save();

        // Elimina cualquier token anterior para forzar una única sesión activa
        $user->tokens()->delete();

        // Crea un nuevo token para la sesión actual
        $token = $user->createToken('token')->plainTextToken;

        return response()->json(["token" => $token], HttpFoundationResponse::HTTP_OK);
    } else {
        // Incrementa el contador de intentos de inicio de sesión
        if ($user) {
            $user->login_attempts++;
            if ($user->login_attempts >= 3) {
                $user->is_blocked = true; // Bloquear al usuario
            }
            $user->save();
        }

        return response()->json(["message" => "Credenciales no válidas"], HttpFoundationResponse::HTTP_UNAUTHORIZED);
    }
    }
    public function userProfile(Request $request)
    {
        return response()->json([
            "message" => "userProfile OK",
            "userData" => auth()->user()
        ], HttpFoundationResponse::HTTP_OK);
    }
    public function logout()
    {
        $cookie = Cookie::forget(('cookie_token'));
        return response(
            ["message" => "Cierre de sesión OK"],
            HttpFoundationResponse::HTTP_OK
        )->withCookie($cookie);
    }

    public function allUsers()
    {
        $users = User::all();
        return response()->json([
            "users" => $users
        ]);
    }
}
