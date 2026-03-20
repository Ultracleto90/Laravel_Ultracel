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
            ->where('taller_id', $request->taller_id)
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
            'id_reparacion' => 'required|integer',
            'diagnostico' => 'required|string',
            'presupuesto' => 'required|numeric',
            'piezas' => 'required|array'
        ]);

        // Iniciamos una transacción para que si algo falla, no se guarde a medias
        DB::beginTransaction();

        try {
            // A) Actualizamos el estado de la reparación
            DB::table('reparaciones')
                ->where('id_reparacion', $request->id_reparacion)
                ->update([
                    'diagnostico_tecnico' => $request->diagnostico,
                    'presupuesto' => $request->presupuesto,
                    'estado' => 'Esperando Aprobación'
                ]);

            // B) Limpiamos piezas viejas por si acaso
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
            
            DB::table('reparacion_piezas')->insert($piezasAInsertar);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Diagnóstico guardado correctamente.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Error al guardar el diagnóstico: ' . $e->getMessage()], 500);
        }
    }
}