<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    // Función para buscar productos en tiempo real
    public function buscar(Request $request)
    {
        $request->validate(['taller_id' => 'required|integer']);
        
        // Si no mandan término de búsqueda, usamos un string vacío para traer todo
        $termino = $request->termino ?? '';

        $productos = DB::table('inventario')
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO PERFECTO (Ya lo tenías)
            ->where(function($query) use ($termino) {
                $query->where('nombre_producto', 'LIKE', "%{$termino}%")
                      ->orWhere('marca_compatible', 'LIKE', "%{$termino}%")
                      ->orWhere('sku', 'LIKE', "%{$termino}%");
            })
            ->orderBy('nombre_producto', 'asc')
            ->select('sku', 'nombre_producto', 'tipo_producto', 'stock', 'precio_venta', 'ubicacion_almacen')
            ->get();

        return response()->json([
            'status' => true,
            'productos' => $productos
        ]);
    }
    
    // Obtener un producto específico por ID (para rellenar el formulario de edición)
    public function obtenerProducto(Request $request)
    {
        $producto = DB::table('inventario')
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO DE SEGURIDAD AÑADIDO
            ->where('id_producto', $request->id_producto)
            ->first();
            
        return response()->json(['status' => true, 'producto' => $producto]);
    }

    // Obtener el ID de un producto buscando por su SKU
    public function obtenerIdPorSku(Request $request)
    {
        $producto = DB::table('inventario')
            ->where('sku', $request->sku)
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO (Ya lo tenías)
            ->first();
            
        return response()->json(['status' => true, 'id_producto' => $producto ? $producto->id_producto : null]);
    }

    // Guardar o actualizar un producto
    public function guardarProducto(Request $request)
    {
        $request->validate([
            'taller_id' => 'required|integer',
            'sku' => 'required|string',
            'nombre_producto' => 'required|string',
            'stock' => 'required|integer',
        ]);

        try {
            $datos = [
                'taller_id' => $request->taller_id,
                'sku' => $request->sku,
                'nombre_producto' => $request->nombre_producto,
                'descripcion' => $request->descripcion,
                'marca_compatible' => $request->marca_compatible,
                'modelo_compatible' => $request->modelo_compatible,
                'stock' => $request->stock,
                'precio_compra' => $request->precio_compra,
                'precio_venta' => $request->precio_venta,
                'ubicacion_almacen' => $request->ubicacion_almacen,
                'updated_at' => now()
            ];

            if ($request->id_producto) {
                DB::table('inventario')
                    ->where('taller_id', $request->taller_id) // 🔒 CANDADO DE SEGURIDAD AÑADIDO
                    ->where('id_producto', $request->id_producto)
                    ->update($datos);
                $mensaje = 'Producto actualizado correctamente.';
            } else {
                $datos['created_at'] = now();
                DB::table('inventario')->insert($datos);
                $mensaje = 'Producto creado correctamente.';
            }
            return response()->json(['status' => true, 'message' => $mensaje]);
        } catch (\Exception $e) {
            if ($e->getCode() == '23000') {
                 return response()->json(['status' => false, 'message' => "El SKU '{$request->sku}' ya existe en tu inventario."], 400);
            }
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Eliminar un producto permanentemente
    public function eliminarProducto(Request $request)
    {
         try {
             DB::table('inventario')
                 ->where('taller_id', $request->taller_id) // 🔒 CANDADO (Ya lo tenías, solo lo ordené)
                 ->where('sku', $request->sku)
                 ->delete();
                 
             return response()->json(['status' => true, 'message' => 'Producto eliminado.']);
         } catch (\Exception $e) {
             return response()->json(['status' => false, 'message' => 'No se pudo eliminar el producto.'], 500);
         }
    }
    
    // --- CREAR NUEVO PRODUCTO ---
    public function crearProducto(Request $request)
    {
        // 1. Verificamos que el SKU no esté repetido en el mismo taller
        $existe = DB::table('inventario')
            ->where('sku', $request->sku)
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO (Ya lo tenías)
            ->first();

        if ($existe) {
            return response()->json(['status' => false, 'message' => 'Ese SKU ya está registrado en el inventario.'], 400);
        }

        // 2. Lo guardamos en la base de datos
        DB::table('inventario')->insert([
            'taller_id' => $request->taller_id,
            'sku' => $request->sku,
            'nombre_producto' => $request->nombre_producto,
            'tipo_producto' => $request->tipo_producto, // Aquí llega "Refacción" o "Venta Directa"
            'marca_compatible' => $request->marca_compatible,
            'stock' => $request->stock,
            'precio_venta' => $request->precio_venta,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => true, 'message' => 'Producto agregado con éxito.']);
    }

    // --- ACTUALIZAR PRODUCTO EXISTENTE ---
    public function actualizarProducto(Request $request)
    {
        DB::table('inventario')
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO (Ya lo tenías, solo lo subí de orden)
            ->where('sku', $request->sku)
            ->update([
                'nombre_producto' => $request->nombre_producto,
                'tipo_producto' => $request->tipo_producto,
                'marca_compatible' => $request->marca_compatible,
                'stock' => $request->stock,
                'precio_venta' => $request->precio_venta,
                'updated_at' => now(),
            ]);

        return response()->json(['status' => true, 'message' => 'Producto actualizado correctamente.']);
    }

    // =========================================================
    // 📱 FUNCIONES DE RESCATE EXCLUSIVAS PARA LA APP DE LALO
    // =========================================================

    public function agregarMovil(Request $request)
    {
        // 1. Generamos un SKU automático porque Lalo no lo tiene en la app
        $sku_automatico = 'MOB-' . strtoupper(substr(uniqid(), -5));

        // 2. Mapeamos lo que manda Lalo a lo que espera tu BD
        \Illuminate\Support\Facades\DB::table('inventario')->insert([
            'taller_id' => $request->taller_id ?? 1,
            'sku' => $sku_automatico,
            'nombre_producto' => $request->nombre, // Lalo manda 'nombre'
            'precio_venta' => $request->precio,    // Lalo manda 'precio'
            'stock' => $request->stock,            // Lalo manda 'stock'
            'tipo_producto' => 'Refacción',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => true, 'message' => 'Refacción guardada desde el móvil.']);
    }

    public function inventarioSucursal($tallerId)
    {
        $productos = \Illuminate\Support\Facades\DB::table('inventario')
            ->where('taller_id', $tallerId)
            ->where('stock', '>', 0)
            // 🐛 CORREGIDO: Tus columnas en BD se llaman id_producto y nombre_producto
            ->select('id_producto as id', 'nombre_producto as nombre', 'stock', 'precio_venta as precio') 
            ->get();

        return response()->json($productos, 200); 
    }
}