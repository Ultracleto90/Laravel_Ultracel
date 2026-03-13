<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Usaremos esta herramienta para verificar encriptaciones

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Verificamos que Python no mande campos vacíos
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // 2. Buscamos al usuario en la base de datos por su correo
        $user = User::where('email', $request->email)->first();

        // 3. Si el usuario existe y su contraseña coincide con el hash guardado...
        if ($user && Hash::check($request->password, $user->password)) {
            
            // ¡Éxito!
            return response()->json([
                'status' => true,
                'message' => '¡Bienvenido a Ultracel!',
                'usuario' => $user,
            ], 200);
        }

        // 4. Fracaso: Correo o contraseña incorrectos
        return response()->json([
            'status' => false,
            'message' => 'Credenciales incorrectas. Intenta de nuevo.'
        ], 401);

    }

    
    // 1. Ver un solo empleado para el Modal de Edición
    public function verEmpleado(Request $request)
    {
        $empleado = User::find($request->id);
        return response()->json(['status' => true, 'empleado' => $empleado]);
    }

    // 2. Crear un nuevo empleado
    public function crearEmpleado(Request $request)
    {
        $nuevo = new User();
        $nuevo->name = $request->name;
        $nuevo->email = $request->email;
        // Encriptamos la contraseña aleatoria que nos mandará Python
        $nuevo->password = bcrypt($request->password); 
        $nuevo->rol = $request->rol;
        $nuevo->especialidad = $request->especialidad ?? 'General';
        $nuevo->taller_id = $request->taller_id;
        $nuevo->permitido = 1;
        $nuevo->save();

        return response()->json(['status' => true, 'message' => 'Usuario creado exitosamente']);
    }

    // 3. Actualizar datos de un empleado
    public function actualizarEmpleado(Request $request)
    {
        $empleado = User::find($request->id);
        if ($empleado) {
            $empleado->name = $request->name;
            $empleado->email = $request->email;
            $empleado->rol = $request->rol;
            $empleado->especialidad = $request->especialidad ?? '';
            $empleado->permitido = $request->permitido;
            $empleado->save();
            
            return response()->json(['status' => true, 'message' => 'Usuario actualizado']);
        }
        return response()->json(['status' => false, 'message' => 'Usuario no encontrado'], 404);
    }
}