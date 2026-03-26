<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Taller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SuscripcionController extends Controller
{
    // Muestra la vista con los planes y el estatus
    public function index()
    {
        $taller = Taller::find(Auth::user()->taller_id);
        
        // Calculamos cuántos días le quedan (puede ser negativo si ya venció)
        $diasRestantes = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($taller->fecha_vencimiento_licencia)->startOfDay(), false);

        return view('suscripcion', compact('taller', 'diasRestantes'));
    }

    // Simula el pago y actualiza la fecha de vencimiento
    public function pagar(Request $request)
    {
        $taller = Taller::find(Auth::user()->taller_id);
        $tipo = $request->tipo_plan; // Recibe: 'mensual', 'semestral' o 'anual'
        $meses = 1;

        if ($tipo === 'semestral') $meses = 6;
        if ($tipo === 'anual') $meses = 12;

        // Si ya estaba vencida, empezamos a contar desde HOY. Si aún tenía días, se los sumamos.
        $fechaBase = Carbon::parse($taller->fecha_vencimiento_licencia);
        if ($fechaBase->isPast()) {
            $fechaBase = Carbon::now();
        }

        // Actualizamos la base de datos
        $taller->fecha_vencimiento_licencia = $fechaBase->addMonths($meses);
        $taller->estado_licencia = 'activa'; // ¡Adiós 'prueba', hola cliente de pago!
        $taller->save();

        return redirect()->route('dashboard')->with('success', "¡Pago procesado con éxito! Tu licencia ha sido renovada por $meses mes(es) más. 🚀");
    }
}