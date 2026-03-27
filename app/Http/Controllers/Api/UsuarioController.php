<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsuarioController extends Controller
{
    // Listar todos los empleados del taller
    public function listarEmpleados(Request $request)
    {
        $request->validate([
            'taller_id' => 'required|integer'
        ]);

        $empleados = User::where('taller_id', $request->taller_id)->get();

        return response()->json([
            'status' => true,
            'empleados' => $empleados
        ], 200);
    }

    // 1. Ver un solo empleado para el Modal de Edición
    public function verEmpleado(Request $request)
    {
        // 🔒 CANDADO: Solo si pertenece al taller que lo solicita
        $empleado = User::where('id', $request->id)
                        ->where('taller_id', $request->taller_id)
                        ->first();

        if (!$empleado) {
            return response()->json(['status' => false, 'message' => 'Usuario no encontrado en este taller'], 404);
        }

        return response()->json(['status' => true, 'empleado' => $empleado]);
    }

    // 2. Crear un nuevo empleado
    public function crearEmpleado(Request $request)
    {
        // Validación básica para evitar choques de emails
        $existe = User::where('email', $request->email)->first();
        if ($existe) {
            return response()->json(['status' => false, 'message' => 'Este correo ya está registrado'], 400);
        }

        $nuevo = new User();
        $nuevo->name = $request->name;
        $nuevo->email = $request->email;
        $nuevo->password = Hash::make($request->password); 
        $nuevo->rol = $request->rol;
        $nuevo->especialidad = $request->especialidad ?? 'General';
        $nuevo->taller_id = $request->taller_id; // 🔒 SE ASIGNA AL TALLER CORRECTO
        $nuevo->permitido = 1;
        $nuevo->save();

        return response()->json(['status' => true, 'message' => 'Usuario creado exitosamente']);
    }

    // 3. Actualizar datos de un empleado
    public function actualizarEmpleado(Request $request)
    {
        // 🔒 CANDADO: El usuario debe existir Y pertenecer al taller
        $empleado = User::where('id', $request->id)
                        ->where('taller_id', $request->taller_id)
                        ->first();
        
        if ($empleado) {
            $empleado->name = $request->name;
            $empleado->email = $request->email;
            $empleado->rol = $request->rol;
            $empleado->especialidad = $request->especialidad ?? '';
            $empleado->permitido = $request->permitido;
            
            // Si mandan contraseña nueva, se encripta y se guarda
            if ($request->filled('password')) {
                $empleado->password = Hash::make($request->password);
            }

            $empleado->save();
            
            return response()->json(['status' => true, 'message' => 'Usuario actualizado']);
        }
        
        return response()->json(['status' => false, 'message' => 'Usuario no encontrado en este taller'], 404);
    }

    // Login (Este se mantiene igual ya que el email es el identificador único global)
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Credenciales incorrectas'], 401);
        }

        if ($user->permitido == 0) {
            return response()->json(['status' => false, 'message' => 'Usuario deshabilitado'], 403);
        }

        return response()->json([
            'status' => true,
            'rol' => $user->rol,
            'name' => $user->name,
            'taller_id' => $user->taller_id
        ], 200);
    }
}