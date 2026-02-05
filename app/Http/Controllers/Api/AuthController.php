<?php

// app/Http/Controllers/Api/AuthController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentar autenticar al usuario
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'error' => 'Credenciales inválidas. Por favor, verifica el email y la contraseña.'
            ], 401);
        }

        // Obtener el usuario autenticado
        $user = Auth::user();

        // 💡 Generar el token (asignando un nombre simple, ej: 'api-token')
        // El método createToken es proporcionado por el trait HasApiTokens
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->email,
        ], 200);
    }
}