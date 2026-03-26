<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TallerUserController extends Controller
{
    // 1. CREAR EMPLEADO (El que ya teníamos)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'rol' => 'required|in:vendedor,tecnico',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'taller_id' => Auth::user()->taller_id,
            'activo' => 1
        ]);

        return back()->with('success', '¡Empleado agregado correctamente a tu equipo!');
    }

    // 2. MODIFICAR EMPLEADO
    public function update(Request $request, User $usuario)
    {
        // Seguridad: Asegurar que es su empleado y que no es el admin
        if ($usuario->taller_id !== Auth::user()->taller_id || $usuario->rol === 'admin') {
            abort(403, 'Acción no permitida.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$usuario->id,
            'password' => 'nullable|string|min:8', // Opcional, solo si la quiere cambiar
            'rol' => 'required|in:vendedor,tecnico',
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->rol = $request->rol;
        
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }
        
        $usuario->save();

        return back()->with('success', 'Datos del empleado actualizados.');
    }

    // 3. SUSPENDER / REACTIVAR EMPLEADO
    public function toggleStatus(User $usuario)
    {
        if ($usuario->taller_id !== Auth::user()->taller_id || $usuario->rol === 'admin') {
            abort(403);
        }

        $usuario->activo = !$usuario->activo; // Cambia 1 por 0 o 0 por 1
        $usuario->save();

        $estado = $usuario->activo ? 'reactivado' : 'suspendido';
        return back()->with('success', "El acceso del empleado ha sido $estado.");
    }

    // 4. ELIMINAR EMPLEADO
    public function destroy(User $usuario)
    {
        if ($usuario->taller_id !== Auth::user()->taller_id || $usuario->rol === 'admin') {
            abort(403);
        }

        $usuario->delete();
        return back()->with('success', 'Empleado eliminado permanentemente del sistema.');
    }
}