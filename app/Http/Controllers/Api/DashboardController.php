<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function obtenerAnaliticas(Request $request)
    {
        $request->validate(['taller_id' => 'required|integer']);
        $tallerId = $request->taller_id;
        $hoy = Carbon::today();

        // =========================================================
        // 1. KPIs SUPERIORES (Métricas del día)
        // =========================================================
        $ventasHoy = DB::table('ventas')
            ->where('taller_id', $tallerId)
            ->whereDate('fecha_venta', $hoy)
            ->sum('monto_total');

        $recibidosHoy = DB::table('reparaciones')
            ->where('taller_id', $tallerId)
            ->whereDate('fecha_recepcion', $hoy)
            ->count();

        $entregadosHoy = DB::table('reparaciones')
            ->where('taller_id', $tallerId)
            ->whereDate('fecha_entrega_real', $hoy)
            ->where('estado', 'Entregado')
            ->count();

        // =========================================================
        // 2. GRÁFICA DE LÍNEAS (Ventas de los últimos 7 días)
        // =========================================================
        $diasVentas = [];
        $ingresosVentas = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $nombreDia = $fecha->locale('es')->isoFormat('ddd'); // Ej: lun, mar, mié
            
            $totalDia = DB::table('ventas')
                ->where('taller_id', $tallerId)
                ->whereDate('fecha_venta', $fecha)
                ->sum('monto_total');

            $diasVentas[] = ucfirst($nombreDia);
            $ingresosVentas[] = (float) $totalDia;
        }

        // =========================================================
        // 3. GRÁFICA DE DONA (Estado de Reparaciones Activas)
        // =========================================================
        // Agrupamos los estados reales de tu BD en 3 categorías simples
        $listos = DB::table('reparaciones')
            ->where('taller_id', $tallerId)
            ->whereIn('estado', ['Reparado'])
            ->count();

        $enTaller = DB::table('reparaciones')
            ->where('taller_id', $tallerId)
            ->whereIn('estado', ['En Diagnóstico', 'En Reparación'])
            ->count();

        $enEspera = DB::table('reparaciones')
            ->where('taller_id', $tallerId)
            ->whereIn('estado', ['Recibido', 'Esperando Aprobación'])
            ->count();

        // =========================================================
        // 4. GRÁFICA DE BARRAS HORIZONTALES (Top 5 Productos)
        // =========================================================
        $topProductos = DB::table('venta_detalles')
            ->join('ventas', 'venta_detalles.id_venta', '=', 'ventas.id_venta')
            ->join('inventario', 'venta_detalles.id_producto', '=', 'inventario.id_producto')
            ->where('ventas.taller_id', $tallerId)
            ->whereNotNull('venta_detalles.id_producto')
            ->select('inventario.nombre_producto', DB::raw('SUM(venta_detalles.cantidad) as total_vendido'))
            ->groupBy('inventario.id_producto', 'inventario.nombre_producto')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        $nombresTop = $topProductos->pluck('nombre_producto')->toArray();
        $cantidadesTop = $topProductos->pluck('total_vendido')->map(function($val) { return (int) $val; })->toArray();

        // Retornamos el JSON empaquetado exactamente como Python lo necesita
        return response()->json([
            'status' => true,
            'kpis' => [
                'ventas_hoy' => number_format($ventasHoy, 2),
                'recibidos_hoy' => $recibidosHoy,
                'entregados_hoy' => $entregadosHoy
            ],
            'grafica_ventas' => [
                'dias' => $diasVentas,
                'ingresos' => $ingresosVentas
            ],
            'grafica_dona' => [
                'labels' => ['Listos', 'En Taller', 'Recibidos'],
                'sizes' => [$listos, $enTaller, $enEspera]
            ],
            'grafica_top' => [
                'productos' => empty($nombresTop) ? ['Sin datos'] : $nombresTop,
                'cantidades' => empty($cantidadesTop) ? [0] : $cantidadesTop
            ]
        ]);
    }
}