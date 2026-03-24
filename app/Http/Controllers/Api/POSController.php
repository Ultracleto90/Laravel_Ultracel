<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    // Cargar clientes de la sucursal para el Combobox
    public function clientes(Request $request)
    {
        $request->validate(['taller_id' => 'required|integer']);

        $clientes = DB::table('clientes')
            ->where('taller_id', $request->taller_id)
            ->select('id_cliente', DB::raw("CONCAT(nombre, ' ', apellidos) as nombre_completo"), 'telefono')
            ->orderBy('nombre', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'clientes' => $clientes
        ]);
    }
    // 1. Buscar productos para Venta Directa
    public function buscarProductos(Request $request)
    {
        $termino = $request->termino ?? '';
        $productos = DB::table('inventario')
            ->where('taller_id', $request->taller_id)
            ->where('tipo_producto', 'Venta Directa')
            ->where('stock', '>', 0)
            ->where(function($q) use ($termino) {
                $q->where('nombre_producto', 'LIKE', "%{$termino}%")
                  ->orWhere('sku', 'LIKE', "%{$termino}%");
            })->select('id_producto', 'nombre_producto', 'precio_venta')->get();

        return response()->json(['status' => true, 'productos' => $productos]);
    }

    // 2. Cargar reparaciones terminadas de un cliente
    public function reparacionesPorCliente(Request $request)
    {
        $reparaciones = DB::table('reparaciones')
            ->join('equipos', 'reparaciones.id_equipo', '=', 'equipos.id_equipo')
            ->where('equipos.id_cliente', $request->id_cliente)
            ->where('reparaciones.taller_id', $request->taller_id ?? 1)
            ->where('reparaciones.estado', 'Reparado')
            ->select(
                'reparaciones.id_reparacion',
                DB::raw("CONCAT(equipos.marca, ' ', equipos.modelo) as equipo"),
                'reparaciones.presupuesto'
            )
            ->get();

        return response()->json([
            'status' => true,
            'reparaciones' => $reparaciones
        ]);
    }

    // 3. ¡El Motor de Ventas! Cobra, descuenta stock y actualiza reparaciones
    public function procesarVenta(Request $request)
    {
        DB::beginTransaction();
        try {
            $id_venta = DB::table('ventas')->insertGetId([
                'taller_id' => $request->taller_id,
                'id_cliente' => $request->id_cliente != 0 ? $request->id_cliente : null,
                'id_vendedor' => $request->id_vendedor,
                'monto_total' => $request->total,
                'fecha_venta' => now()
            ]);

            foreach ($request->items as $item) {
                $esProducto = $item['tipo'] == 'P';
                
                DB::table('venta_detalles')->insert([
                    'id_venta' => $id_venta,
                    'id_producto' => $esProducto ? $item['id_item'] : null,
                    'id_reparacion' => !$esProducto ? $item['id_item'] : null,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'descripcion_linea' => $item['descripcion']
                ]);

                if ($esProducto) {
                    DB::table('inventario')->where('id_producto', $item['id_item'])->decrement('stock', $item['cantidad']);
                } else {
                    DB::table('reparaciones')->where('id_reparacion', $item['id_item'])->update(['estado' => 'Entregado', 'fecha_entrega_real' => now()]);
                }
            }
            DB::commit();
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 4. Historial Completo de Ventas
    public function historialVentas(Request $request)
    {
        $ventas = DB::table('ventas as v')
            ->leftJoin('clientes as c', 'v.id_cliente', '=', 'c.id_cliente')
            ->join('users as u', 'v.id_vendedor', '=', 'u.id')
            ->where('v.taller_id', $request->taller_id)
            ->select('v.id_venta', DB::raw("DATE_FORMAT(v.fecha_venta, '%Y-%m-%d %H:%i') as fecha"), DB::raw("IFNULL(CONCAT(c.nombre, ' ', c.apellidos), 'Venta de Mostrador') AS cliente"), 'u.name AS vendedor', 'v.monto_total')
            ->orderBy('v.fecha_venta', 'desc')->get();
        return response()->json(['status' => true, 'ventas' => $ventas]);
    }

    // 5. Detalles de un Ticket
    public function detallesVenta(Request $request)
    {
        $detalles = DB::table('venta_detalles')
            ->where('id_venta', $request->id_venta)
            ->select('cantidad', 'descripcion_linea', 'precio_unitario')->get();
        return response()->json(['status' => true, 'detalles' => $detalles]);
    }
    
}