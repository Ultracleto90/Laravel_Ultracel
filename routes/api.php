<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// ¡ESTA ES LA LÍNEA MÁGICA QUE FALTABA! Le dice a Laravel dónde vive el controlador
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\LicenciaController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\ReparacionController;
use App\Http\Controllers\Api\InventarioController;
use App\Http\Controllers\Api\DiagnosticoController;
use App\Http\Controllers\Api\SolicitudMaterialController;
use App\Http\Controllers\Api\POSController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\PortalClienteController;


// La ruta que escucha a Python
Route::post('/login', [AuthController::class, 'login']);
//Ruta insana de activacion de licencias
Route::post('/activar-licencia', [LicenciaController::class, 'activar']);
//otra bendita ruta para administrar usuarios
Route::post('/empleados', [UsuarioController::class, 'listarEmpleados']);
//Rutas para Darle macizo a la edicion de los usuarios
Route::post('/empleado/ver', [UsuarioController::class, 'verEmpleado']);
Route::post('/empleado/crear', [UsuarioController::class, 'crearEmpleado']);
Route::post('/empleado/actualizar', [UsuarioController::class, 'actualizarEmpleado']);
Route::post('/reparaciones/pendientes', [ReparacionController::class, 'pendientes']);
Route::post('/login', [UsuarioController::class, 'login']);
Route::post('/reparaciones/detalles', [ReparacionController::class, 'detalles']);
Route::post('/reparaciones/terminar', [ReparacionController::class, 'terminar']);
Route::post('/inventario/buscar', [InventarioController::class, 'buscar']);
Route::post('/diagnostico/inventario', [DiagnosticoController::class, 'inventarioDisponible']);
Route::post('/diagnostico/guardar', [DiagnosticoController::class, 'guardar']);
Route::post('/material/listar', [SolicitudMaterialController::class, 'listar']);
Route::post('/material/crear', [SolicitudMaterialController::class, 'crear']);
Route::post('/pos/clientes', [POSController::class, 'clientes']);
Route::post('/clientes/obtener', [ClienteController::class, 'obtenerCliente']);
Route::post('/clientes/guardar', [ClienteController::class, 'guardarCliente']);
Route::post('/clientes/eliminar', [ClienteController::class, 'eliminarCliente']);
Route::post('/clientes/registrar-equipo', [ClienteController::class, 'registrarEquipo']);
Route::post('/inventario/obtener', [InventarioController::class, 'obtenerProducto']);
Route::post('/inventario/obtener-por-sku', [InventarioController::class, 'obtenerIdPorSku']);
Route::post('/inventario/guardar', [InventarioController::class, 'guardarProducto']);
Route::post('/inventario/eliminar', [InventarioController::class, 'eliminarProducto']);

Route::post('/clientes/historial', [ClienteController::class, 'historialReparaciones']);
Route::post('/material/admin-listar', [SolicitudMaterialController::class, 'listarAdmin']);
Route::post('/material/actualizar-estado', [SolicitudMaterialController::class, 'actualizarEstado']);
Route::post('/diagnostico/inventario', [DiagnosticoController::class, 'inventarioParaDiagnostico']);
Route::post('/inventario/crear', [InventarioController::class, 'crearProducto']);
Route::post('/inventario/actualizar', [InventarioController::class, 'actualizarProducto']);
Route::post('/pos/buscar-productos', [POSController::class, 'buscarProductos']);
Route::post('/pos/reparaciones-cliente', [POSController::class, 'reparacionesPorCliente']);


// Estas son vitales para tu motor de ventas y el historial:
Route::post('/pos/procesar-venta', [POSController::class, 'procesarVenta']);
Route::post('/pos/historial-ventas', [POSController::class, 'historialVentas']);
Route::post('/pos/detalles-venta', [POSController::class, 'detallesVenta']);

//RUta para el portal del cliente (App móvil)
Route::post('/portal/rastrear', [PortalClienteController::class, 'rastrear']);
Route::post('/ticket/consultar', [\App\Http\Controllers\Api\PortalClienteController::class, 'consultarTicket']);