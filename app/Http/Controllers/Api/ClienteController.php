<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    // 1. Obtener detalles de un cliente específico para editar
    public function obtenerCliente(Request $request)
    {
        $cliente = DB::table('clientes')
            ->where('taller_id', $request->taller_id) // 🔒 CANDADO DE SEGURIDAD
            ->where('id_cliente', $request->id_cliente)
            ->first();
            
        return response()->json(['status' => true, 'cliente' => $cliente]);
    }

    // 2. Guardar o actualizar un cliente
    public function guardarCliente(Request $request)
    {
        $request->validate([
            'taller_id' => 'required|integer',
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'telefono' => 'required|string',
        ]);

        try {
            if ($request->id_cliente) {
                // Actualizar
                DB::table('clientes')
                    ->where('taller_id', $request->taller_id) // 🔒 CANDADO DE SEGURIDAD
                    ->where('id_cliente', $request->id_cliente)
                    ->update([
                        'nombre' => $request->nombre,
                        'apellidos' => $request->apellidos,
                        'telefono' => $request->telefono,
                        'email' => $request->email,
                        'updated_at' => now()
                    ]);
                $mensaje = 'Cliente actualizado correctamente.';
            } else {
                // Crear nuevo
                DB::table('clientes')->insert([
                    'taller_id' => $request->taller_id,
                    'nombre' => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'telefono' => $request->telefono,
                    'email' => $request->email,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $mensaje = 'Cliente creado correctamente.';
            }
            return response()->json(['status' => true, 'message' => $mensaje]);
        } catch (\Exception $e) {
            // Manejo de error por teléfono duplicado (código 23000 de SQLSTATE)
            if ($e->getCode() == '23000') {
                 return response()->json(['status' => false, 'message' => 'El teléfono ya está registrado en este taller.'], 400);
            }
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 3. Eliminar un cliente
    public function eliminarCliente(Request $request)
    {
         try {
             DB::table('clientes')
                 ->where('taller_id', $request->taller_id) // 🔒 CANDADO DE SEGURIDAD
                 ->where('id_cliente', $request->id_cliente)
                 ->delete();
                 
             return response()->json(['status' => true, 'message' => 'Cliente eliminado.']);
         } catch (\Exception $e) {
             return response()->json(['status' => false, 'message' => 'No se pudo eliminar el cliente.'], 500);
         }
    }

    // 4. Recepción de un equipo (Crea cliente si no existe, luego el equipo y la reparación)
    // 4. Recepción de un equipo (Crea cliente si no existe, luego el equipo y la reparación)
    public function registrarEquipo(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Determinar el ID del cliente
            $id_cliente = $request->id_cliente ?? null;

            // 🔒 NUEVO CANDADO ANTI-HACKERS (Validar propiedad del cliente existente)
            if ($id_cliente) {
                $clientePerteneceAlTaller = DB::table('clientes')
                    ->where('id_cliente', $id_cliente)
                    ->where('taller_id', $request->taller_id)
                    ->exists();

                if (!$clientePerteneceAlTaller) {
                    throw new \Exception('Acceso Denegado: Este cliente no pertenece a tu sucursal.');
                }
            }

            // Si no viene un ID explícito, significa que es un cliente nuevo (desde los campos de texto)
            if (!$id_cliente && $request->has('cliente')) {
                $clienteExistente = DB::table('clientes')
                    ->where('telefono', $request->cliente['telefono'])
                    ->where('taller_id', $request->taller_id) // 🔒 CANDADO OK
                    ->first();
                
                if ($clienteExistente) {
                    $id_cliente = $clienteExistente->id_cliente;
                } else {
                    $id_cliente = DB::table('clientes')->insertGetId([
                        'taller_id' => $request->taller_id,
                        'nombre' => $request->cliente['nombre'],
                        'apellidos' => $request->cliente['apellidos'],
                        'telefono' => $request->cliente['telefono'],
                        'email' => $request->cliente['email'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            // Si por alguna extraña razón no tenemos ID en este punto, abortamos la misión
            if (!$id_cliente) {
                throw new \Exception('No se pudo determinar el cliente para este equipo.');
            }

            // 2. Insertar equipo
            $id_equipo = DB::table('equipos')->insertGetId([
                'id_cliente' => $id_cliente,
                'tipo_equipo' => 'Celular',
                'marca' => $request->equipo['marca'],
                'modelo' => $request->equipo['modelo'],
                'imei_o_serie' => $request->equipo['imei'] ?? null,
                'created_at' => now()
            ]);

            // 🔐 3. GENERADOR DE PIN SEGURO (4 DÍGITOS)
            $pinAleatorio = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

            // 4. Insertar reparación (¡Cambiamos a insertGetId para atrapar el Folio!)
            $id_reparacion = DB::table('reparaciones')->insertGetId([
                'id_equipo' => $id_equipo,
                'taller_id' => $request->taller_id, // 🔒 CANDADO OK
                'problema_reportado' => $request->equipo['descripcion'],
                'estado' => 'Recibido',
                'pin_cliente' => $pinAleatorio, // <-- EL NUEVO CANDADO INYECTADO
                'fecha_recepcion' => now()
            ]);

            DB::commit();
            
            // 🚀 Regresamos el Folio y el PIN a la App de Python
            return response()->json([
                'status' => true, 
                'message' => 'Equipo registrado correctamente.',
                'folio' => $id_reparacion,
                'pin' => $pinAleatorio
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    // 5. Obtener historial de reparaciones de un cliente
    public function historialReparaciones(Request $request)
    {
        $historial = DB::table('reparaciones as r')
            ->join('equipos as e', 'r.id_equipo', '=', 'e.id_equipo')
            ->where('r.taller_id', $request->taller_id) // 🔒 CANDADO DE SEGURIDAD
            ->where('e.id_cliente', $request->id_cliente)
            ->select('r.id_reparacion', DB::raw("DATE_FORMAT(r.fecha_recepcion, '%Y-%m-%d') as fecha"), DB::raw("CONCAT(e.marca, ' ', e.modelo) AS dispositivo"), 'r.estado', 'r.presupuesto')
            ->orderBy('r.fecha_recepcion', 'desc')
            ->get();
            
        return response()->json(['status' => true, 'historial' => $historial]);
    }
}