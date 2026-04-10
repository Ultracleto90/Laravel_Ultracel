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

        $utilidadHoy = DB::table('ventas')
            ->where('taller_id', $tallerId)
            ->whereDate('fecha_venta', $hoy)
            ->sum('utilidad_neta');

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
                // 🔥 EL TRUCO: Le ponemos (float) antes de la variable para convertir los null en 0
                'ventas_hoy' => number_format((float)$ventasHoy, 2, '.', ''),
                'utilidad_hoy' => number_format((float)$utilidadHoy, 2, '.', ''), 
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

    // =========================================================
    // 📱 RUTAS EXCLUSIVAS PARA DASHBOARD MÓVIL (LALO)
    // =========================================================

    // 1. Monitor de Estadísticas Generales
    public function statsMovil($taller_id)
    {
        // Reparaciones activas (que no estén entregadas ni canceladas)
        $reparaciones = DB::table('reparaciones')
            ->where('taller_id', $taller_id)
            ->whereNotIn('estado', ['Entregado', 'Cancelado'])
            ->count();

        // Ingresos del mes actual
        $ingresos = DB::table('ventas')
            ->where('taller_id', $taller_id)
            ->whereMonth('fecha_venta', Carbon::now()->month)
            ->sum('monto_total');

        // Empleados activos en la sucursal (suponiendo que todos los usuarios pertenecen al taller)
        $empleados = DB::table('users')
            ->where('taller_id', $taller_id)
            ->count();

        return response()->json([
            'reparaciones_taller' => $reparaciones,
            'ingresos_mes' => (float) $ingresos,
            'empleados_activos' => $empleados
        ]);
    }

   
    // 2. Monitor de Personal (Ranking de Técnicos)
    public function rankingTecnicos($taller_id)
    {
        $ranking = DB::table('users')
            ->where('users.taller_id', $taller_id)
            ->where('users.activo', 1) 
            // 🔥 EL ESCUDO: Asegurarnos de que solo traiga a los TÉCNICOS al ranking
            // (Ajusta 'rol' al nombre real de tu columna si se llama distinto, ej: 'role_id')
            // 🔥 EL FIX: Aceptamos "Tecnico" (Python) y "technician" (Estándar)
            ->whereIn('users.rol', ['Tecnico', 'technician', 'tecnico']) 
            
            ->leftJoin('reparaciones', function($join) {
                $join->on('users.id', '=', 'reparaciones.id_tecnico_asignado')
                     ->whereIn('reparaciones.estado', ['Reparado', 'Entregado']);
            })
            // Seleccionamos explícitamente el nombre real
            ->select('users.name as nombre', DB::raw('COUNT(reparaciones.id_reparacion) as total'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total')
            ->get();

        return response()->json($ranking);
    }

    // 3. Monitor de Ventas Semanales
    public function ventasSemanales($taller_id)
    {
        $ventas = [];
        
        // Iteramos los últimos 7 días
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $nombreDia = ucfirst($fecha->locale('es')->isoFormat('ddd')); // Lun, Mar, Mié
            
            $totalDia = DB::table('ventas')
                ->where('taller_id', $taller_id)
                ->whereDate('fecha_venta', $fecha)
                ->sum('monto_total');

            $ventas[] = [
                'dia' => $nombreDia, 
                'monto' => (float) $totalDia
            ];
        }

        return response()->json($ventas);
    }

    // 4. Monitor de Inventario
    public function inventarioGraficas($taller_id)
    {
        // A) REFACCIONES
        $refaccionesBD = DB::table('inventario')
            ->where('taller_id', $taller_id)
            ->where('tipo_producto', 'Refacción')
            ->select('nombre_producto as categoria', DB::raw('SUM(stock) as stock'))
            ->groupBy('nombre_producto')
            ->get();

        // B) ACCESORIOS (Venta Directa)
        $accesoriosBD = DB::table('inventario')
            ->where('taller_id', $taller_id)
            ->where('tipo_producto', 'Venta Directa')
            ->get();

        $todas = [];
        $porMarca = [];

        // Clasificamos en memoria para darle la estructura exacta a Lalo
        foreach($accesoriosBD as $acc) {
            $tipo = $acc->nombre_producto; // Ej: Micas, Fundas
            $marca = $acc->marca_compatible ?: 'General'; // Si es NULL, lo metemos a General

            // Sumatoria Global ("Todas")
            if(!isset($todas[$tipo])) $todas[$tipo] = 0;
            $todas[$tipo] += $acc->stock;

            // Sumatoria por Marca ("Apple", "Samsung", etc.)
            if(!isset($porMarca[$marca])) $porMarca[$marca] = [];
            if(!isset($porMarca[$marca][$tipo])) $porMarca[$marca][$tipo] = 0;
            $porMarca[$marca][$tipo] += $acc->stock;
        }

        // Formateamos el JSON
        $accesoriosFormateado = ['Todas' => []];
        foreach($todas as $tipo => $stock) {
            $accesoriosFormateado['Todas'][] = ['tipo' => $tipo, 'stock' => (int) $stock];
        }

        foreach($porMarca as $marca => $tipos) {
            $accesoriosFormateado[$marca] = [];
            foreach($tipos as $tipo => $stock) {
                $accesoriosFormateado[$marca][] = ['tipo' => $tipo, 'stock' => (int) $stock];
            }
        }

        return response()->json([
            'refacciones' => $refaccionesBD,
            'accesorios' => $accesoriosFormateado
        ]);
    }
}