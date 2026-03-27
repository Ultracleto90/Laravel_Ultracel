<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiagnosticoController extends Controller
{
    // 1. Obtener inventario con stock > 0 para una sucursal específica
    public function inventarioDisponible(Request $request)
    {
        $request->validate(['taller_id' => 'required|integer']);

        $inventario = DB::table('inventario')
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO OK
            ->where('stock', '>', 0)
            ->select('id_producto', 'nombre_producto', 'stock', 'precio_venta')
            ->orderBy('nombre_producto', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'inventario' => $inventario
        ]);
    }

    // 2. Guardar el diagnóstico y las piezas a utilizar
    public function guardar(Request $request)
    {
        $request->validate([
            'taller_id' => 'required|integer', // 🔒 VALIDACIÓN AÑADIDA
            'id_reparacion' => 'required|integer',
            'diagnostico' => 'required|string',
            'presupuesto' => 'required|numeric',
            'piezas' => 'required|array'
        ]);

        // Iniciamos una transacción
        DB::beginTransaction();

        try {
            // 🔒 VERIFICACIÓN DE PROPIEDAD:
            // Validamos que la reparación pertenezca al taller antes de editar
            $reparacion = DB::table('reparaciones')
                ->where('id_reparacion', $request->id_reparacion)
                ->where('taller_id', $request->taller_id)
                ->first();

            if (!$reparacion) {
                return response()->json(['status' => false, 'message' => 'Reparación no encontrada o acceso denegado.'], 403);
            }

            // A) Actualizamos el estado de la reparación con el candado del taller
            DB::table('reparaciones')
                ->where('id_reparacion', $request->id_reparacion)
                ->where('taller_id', $request->taller_id) 
                ->update([
                    'diagnostico_tecnico' => $request->diagnostico,
                    'presupuesto' => $request->presupuesto,
                    'estado' => 'Esperando Aprobación'
                ]);

            // B) Limpiamos piezas viejas (Solo de esta reparación que ya validamos que es nuestra)
            DB::table('reparacion_piezas')
                ->where('id_reparacion', $request->id_reparacion)
                ->delete();

            // C) Insertamos las piezas nuevas
            $piezasAInsertar = [];
            foreach ($request->piezas as $pieza) {
                $piezasAInsertar[] = [
                    'id_reparacion' => $request->id_reparacion,
                    'id_producto' => $pieza['id_producto'],
                    'cantidad_usada' => 1,
                    'precio_en_reparacion' => $pieza['precio']
                ];
            }
            
            if (!empty($piezasAInsertar)) {
                DB::table('reparacion_piezas')->insert($piezasAInsertar);
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Diagnóstico guardado correctamente.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Error al guardar el diagnóstico: ' . $e->getMessage()], 500);
        }
    }

    // 3. Inventario para diagnóstico (Misma lógica de seguridad)
    public function inventarioParaDiagnostico(Request $request)
    {
        $inventario = DB::table('inventario')
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO OK
            ->where('stock', '>', 0)
            ->select('id_producto', 'nombre_producto', 'stock', 'precio_venta') 
            ->orderBy('nombre_producto', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'inventario' => $inventario
        ]);
    }
}