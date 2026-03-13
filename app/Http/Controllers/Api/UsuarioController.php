<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsuarioController extends Controller
{
    // Función original: Listar todos los empleados del taller
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
        $empleado = User::find($request->id);
        return response()->json(['status' => true, 'empleado' => $empleado]);
    }

    // 2. Crear un nuevo empleado
    public function crearEmpleado(Request $request)
    {
        $nuevo = new User();
        $nuevo->name = $request->name;
        $nuevo->email = $request->email;
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