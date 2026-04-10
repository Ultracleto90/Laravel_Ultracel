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
        
        $termino = $request->termino ?? '';

        $productos = DB::table('inventario')
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO PERFECTO
            ->where(function($query) use ($termino) {
                $query->where('nombre_producto', 'LIKE', "%{$termino}%")
                      ->orWhere('marca_compatible', 'LIKE', "%{$termino}%")
                      ->orWhere('sku', 'LIKE', "%{$termino}%");
            })
            ->orderBy('nombre_producto', 'asc')
            // 🐛 PARCHE: Agregamos 'id_producto' al select porque Python lo necesita
            ->select('id_producto', 'sku', 'nombre_producto', 'tipo_producto', 'stock', 'precio_venta', 'ubicacion_almacen')
            ->get();

        return response()->json([
            'status' => true,
            'productos' => $productos
        ]);
    }
    
    // Obtener un producto específico por ID
    public function obtenerProducto(Request $request)
    {
        $request->validate(['taller_id' => 'required|integer', 'id_producto' => 'required|integer']);

        $producto = DB::table('inventario')
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO 
            ->where('id_producto', $request->id_producto)
            ->first();
            
        return response()->json(['status' => true, 'producto' => $producto]);
    }

    // Obtener el ID de un producto buscando por su SKU
    public function obtenerIdPorSku(Request $request)
    {
        $request->validate(['taller_id' => 'required|integer', 'sku' => 'required|string']);

        $producto = DB::table('inventario')
            ->where('sku', $request->sku)
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO
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
                    ->where('taller_id', $request->taller_id) // 🔒 CANDADO 
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
        $request->validate(['taller_id' => 'required|integer', 'sku' => 'required|string']);

         try {
             DB::table('inventario')
                 ->where('taller_id', $request->taller_id) // 🔒 CANDADO
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
        $request->validate(['taller_id' => 'required|integer', 'sku' => 'required|string']);

        $existe = DB::table('inventario')
            ->where('sku', $request->sku)
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO
            ->first();

        if ($existe) {
            return response()->json(['status' => false, 'message' => 'Ese SKU ya está registrado en el inventario.'], 400);
        }

        DB::table('inventario')->insert([
            'taller_id' => $request->taller_id,
            'sku' => $request->sku,
            'nombre_producto' => $request->nombre_producto,
            'tipo_producto' => $request->tipo_producto, 
            'marca_compatible' => $request->marca_compatible,
            'stock' => $request->stock,
            // 🔥 EL PARCHE: Atrapamos los dos precios
            'precio_compra' => $request->precio_compra ?? 0.00,
            'precio_venta' => $request->precio_venta ?? 0.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => true, 'message' => 'Producto agregado con éxito.']);
    }

    // --- ACTUALIZAR PRODUCTO EXISTENTE ---
    public function actualizarProducto(Request $request)
    {
        $request->validate(['taller_id' => 'required|integer', 'sku' => 'required|string']);

        DB::table('inventario')
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO
            ->where('sku', $request->sku)
            ->update([
                'nombre_producto' => $request->nombre_producto,
                'tipo_producto' => $request->tipo_producto,
                'marca_compatible' => $request->marca_compatible,
                'stock' => $request->stock,
                // 🔥 EL PARCHE: Actualizamos los dos precios
                'precio_compra' => $request->precio_compra ?? 0.00,
                'precio_venta' => $request->precio_venta ?? 0.00,
                'updated_at' => now(),
            ]);

        return response()->json(['status' => true, 'message' => 'Producto actualizado correctamente.']);
    }

    // =========================================================
    // 📱 FUNCIONES EXCLUSIVAS PARA LA APP MÓVIL
    // =========================================================

    public function agregarMovil(Request $request)
    {
        // 🔒 FUGA DE DATOS SELLADA: Obligamos a la App a mandar su ID, si no, lo rechazamos.
        $request->validate([
            'taller_id' => 'required|integer',
            'nombre' => 'required|string',
            'precio' => 'required|numeric'
        ]);

        $sku_automatico = 'MOB-' . strtoupper(substr(uniqid(), -5));

        DB::table('inventario')->insert([
            'taller_id' => $request->taller_id, // YA NO DEFAULTA A 1
            'sku' => $sku_automatico,
            'nombre_producto' => $request->nombre, 
            'precio_venta' => $request->precio,    
            'stock' => $request->stock ?? 1,            
            'tipo_producto' => 'Refacción',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => true, 'message' => 'Refacción guardada desde el móvil.']);
    }

    public function inventarioSucursal($tallerId)
    {
        $productos = DB::table('inventario')
            ->where('taller_id', $tallerId) // 🔒 CANDADO EN GET
            ->where('stock', '>', 0)
            ->select('id_producto as id', 'nombre_producto as nombre', 'stock', 'precio_venta as precio') 
            ->get();

        return response()->json($productos, 200); 
    }
}