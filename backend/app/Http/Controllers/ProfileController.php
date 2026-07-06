<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Actualiza el perfil del usuario autenticado.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validar campos
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'lastname' => 'sometimes|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'universityId' => 'nullable|exists:universities,id',
            'relationshipType' => 'nullable|string|in:STUDENT,TUTOR,PROFESSOR,EMPLOYEE,ALUMNI',
            'profileImage' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // max 2MB
        ]);

        // Actualizar datos básicos
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('lastname')) $user->lastname = $request->lastname;
        if ($request->has('bio')) $user->bio = $request->bio;

        // Subida de imagen de perfil a Cloudinary
        if ($request->hasFile('profileImage')) {
            \Cloudinary\Configuration\Configuration::instance(env('CLOUDINARY_URL'));

            // Subir nueva imagen (sobreescribe la antigua automáticamente si usamos el ID del usuario)
            $response = (new \Cloudinary\Api\Upload\UploadApi())->upload(
                $request->file('profileImage')->getRealPath(),
                [
                    'folder' => 'tu_asesoria/profiles',
                    'public_id' => 'profile_user_' . $user->id,
                    'overwrite' => true,
                    'invalidate' => true // Obliga a refrescar el caché de la CDN si cambió
                ]
            );
            $user->profile_image_url = $response['secure_url'];
        }

        $user->save();

        // Actualizar universidad primaria si se envía
        if ($request->has('universityId')) {
            $relationship = $request->input('relationshipType', 'STUDENT');
            
            // Si ya existe otra primaria, la quitamos
            $user->universities()->updateExistingPivot($user->universities()->pluck('universities.id')->toArray(), ['is_primary' => false]);
            
            // Agregar o actualizar la nueva universidad primaria
            $user->universities()->syncWithoutDetaching([
                $request->universityId => [
                    'is_primary' => true,
                    'relationship_type' => $relationship
                ]
            ]);
        }

        // Retornar al usuario formateado de la misma forma que el AuthController
        $primaryUniversity = $user->universities()->wherePivot('is_primary', true)->first();
        
        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user' => [
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
            ]
        ]);
    }
}
