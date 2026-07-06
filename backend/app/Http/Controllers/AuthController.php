<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Format the user response to include profile details.
     */
    private function formatUserResponse($user)
    {
        $primaryUniversity = $user->universities()->wherePivot('is_primary', true)->first();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'role' => $user->role,
            'verification_status' => $user->verification_status ?? 'PENDING',
            'is_tutor' => $user->is_tutor ?? false,
            'university' => $primaryUniversity ? [
                'id' => $primaryUniversity->id,
                'name' => $primaryUniversity->name,
            ] : null,
            'relationshipType' => $primaryUniversity ? $primaryUniversity->pivot->relationship_type : null,
            'profileImageUrl' => $user->profile_image_url, // URL directo de Cloudinary
            'bio' => $user->bio,
            'createdAt' => $user->created_at,
        ];
    }

    /**
     * Recupera la información del usuario autenticado (JWT).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        return response()->json($this->formatUserResponse($user));
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
            'user' => $this->formatUserResponse($user)
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
            'user' => $this->formatUserResponse($user)
        ], 201);
    }
}
