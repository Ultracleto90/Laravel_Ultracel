<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Taller; // Importamos el modelo Taller
use Illuminate\Support\Facades\Hash;

class LicenciaController extends Controller
{
    public function activar(Request $request)
    {
        // 1. Validar lo que nos manda Python
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Buscar al usuario
        $user = User::where('email', $request->email)->first();

        // 3. Verificar que exista y la contraseña sea correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Credenciales incorrectas o cuenta no encontrada.'
            ], 401);
        }

        // 4. Verificar que sea el DUEÑO (Admin) el que intenta activar
        if ($user->rol !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Solo el administrador del taller puede activar esta licencia.'
            ], 403);
        }

        // 5. Verificar el estado de la licencia del Taller
        $taller = Taller::find($user->taller_id);
        $hoy = now()->format('Y-m-d');

        if (!$taller || !$taller->activo || $taller->fecha_vencimiento_licencia < $hoy) {
            return response()->json([
                'status' => false,
                'message' => 'La licencia ha expirado o el taller está inactivo. Por favor renueva en la página web.'
            ], 403);
        }

        // 6. ¡Todo en orden! Generamos un Token de acceso simulado por ahora
        // (Más adelante lo cambiaremos por un Token criptográfico real con Laravel Sanctum)
        $tokenGenerado = hash('sha256', $user->email . $taller->id . now());

        return response()->json([
            'status' => true,
            'message' => '¡Licencia activada exitosamente!',
            'datos_licencia' => [
                'taller_id' => $taller->id,
                'nombre_negocio' => $taller->nombre_negocio,
                'token_seguridad' => $tokenGenerado,
                'vencimiento' => $taller->fecha_vencimiento_licencia
            ]
        ], 200);
    }
}