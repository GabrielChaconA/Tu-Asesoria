<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TutorController;

Route::get('/subjects', [SubjectController::class, 'index']);
Route::get('/subjects/{id}/tutors', [SubjectController::class, 'tutors']);

Route::get('/tutors/featured', [TutorController::class, 'getFeatured']);
Route::get('/tutors', [TutorController::class, 'getTutors']);
Route::get('/professors', [TutorController::class, 'getProfessors']);
Route::get('/tutors-all', [TutorController::class, 'getAll']);
Route::get('/users/{id}/profile', [TutorController::class, 'getProfile']);

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me']);
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update']);
});

// Ejemplos de rutas protegidas por rol (middleware CheckRole)

Route::middleware(['auth:api', 'role:ADMIN'])->group(function () {
    // Solo ADMIN puede validar tutores.
    Route::post('/admin/validate-tutor', function () {
        return response()->json(['message' => 'Tutor validado exitosamente']);
    });
});

Route::middleware(['auth:api', 'role:TUTOR'])->group(function () {
    
    // Rutas que requieren que el tutor esté aprobado (Sprint 2)
    Route::middleware(['tutor.approved'])->group(function () {
        // Solo TUTOR aprobado puede aceptar asesorías.
        Route::post('/tutor/accept-advisory', function () {
            return response()->json(['message' => 'Asesoría aceptada']);
        });
    });

    // Ejemplo: Rutas a las que un tutor podría acceder incluso estando PENDING
    Route::get('/tutor/status', function (Request $request) {
        return response()->json(['status' => $request->user()->verification_status]);
    });
});

// Ejemplo: Endpoint público de búsqueda de tutores (Sprint 2)
Route::get('/search/tutors', function () {
    // Lógica para devolver SOLO tutores aprobados
    $tutors = \App\Models\User::where('is_tutor', true)
        ->where('verification_status', 'APPROVED')
        ->get();
    
    return response()->json($tutors);
});

Route::middleware(['auth:api', 'role:STUDENT'])->group(function () {
    // Solo STUDENT puede crear solicitudes de asesoría.
    Route::post('/student/request-advisory', function () {
        return response()->json(['message' => 'Solicitud de asesoría creada']);
    });
});

// Ejemplo: Ruta con múltiples roles permitidos
Route::middleware(['auth:api', 'role:ADMIN,TUTOR'])->group(function () {
    Route::get('/shared-data', function () {
        return response()->json(['message' => 'Acceso permitido para ADMIN y TUTOR']);
    });
});
