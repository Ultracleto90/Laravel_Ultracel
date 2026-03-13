<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// ¡ESTA ES LA LÍNEA MÁGICA QUE FALTABA! Le dice a Laravel dónde vive el controlador
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\LicenciaController;
use App\Http\Controllers\Api\UsuarioController;

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