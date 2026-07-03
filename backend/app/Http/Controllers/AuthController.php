<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Recupera la información del usuario autenticado (JWT).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_tutor' => $user->is_tutor ?? false,
            'verification_status' => $user->verification_status ?? 'PENDING'
        ]);
    }

    /**
     * Iniciar sesión y retornar token JWT.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth()->guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        $user = auth()->guard('api')->user();

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_tutor' => $user->is_tutor ?? false,
                'verification_status' => $user->verification_status ?? 'PENDING'
            ]
        ]);
    }

    /**
     * Registrar usuario y retornar token JWT.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string'
        ]);

        $nameParts = explode(' ', trim($request->name), 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? ' ';

        $user = \App\Models\User::create([
            'name' => $firstName,
            'lastname' => $lastName,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'USER',
            'is_tutor' => in_array($request->role, ['tutor', 'teacher'])
        ]);

        $token = auth()->guard('api')->login($user);

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_tutor' => $user->is_tutor ?? false,
                'verification_status' => $user->verification_status ?? 'PENDING'
            ]
        ], 201);
    }
}
