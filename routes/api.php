<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\pedidoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
Route::get('productos', [ProductoController::class, 'index']);
Route::post('crearPro', [ProductoController::class, 'store']);
Route::get('rol', [RolController::class, 'index']);
Route::post('crearRol', [RolController::class, 'store']);
Route::post('crearUsuario',[UsuarioController::class, 'register']);
Route::post('pedido',[pedidoController::class, 'nuevoPedido']);
Route::get('listPedido',[pedidoController::class,'list']);
Route::post('clientesPedidos', [UsuarioController::class,'listarProductos']);
Route::put('updateEstado/{pedido_id}', [pedidoController::class,'actualizarEstado']);
Route::put('updateEstadoDetalle/{detalle_id}',[pedidoController::class,'actualizarDetallePedido']);
Route::post('subirArchivo/{detalle_id}', [pedidoController::class,'subirArchivo']);
