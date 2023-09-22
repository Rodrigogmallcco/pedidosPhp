<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\Persona;
use App\Models\UsuarioRol;

class UsuarioController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'direccion' => 'required|string',
            'tipoDocumento' => 'required|string',
            'numeroDocumento' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'correoElectronico' => 'required|email',
            'nombreDelRol' => 'required|string',
        ]);

        $persona = new Persona([
            'nombre' => $request->input('nombre'),
            'direccion' => $request->input('direccion'),
            'tipoDocumento' => $request->input('tipoDocumento'),
            'numeroDocumento' => $request->input('numeroDocumento'),
        ]);
        $persona->save();
        $nombreDelRol = $request->input('nombreDelRol');
        $rol = Rol::where('nombreDelRol', $nombreDelRol)->first();

        if (!$rol) {
            return response()->json(['error' => 'El rol especificado no existe'], 404);
        }

        $usuario = new Usuario([
            'personaId' => $persona->personaId,
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'correoElectronico' => $request->input('correoElectronico'),
            'estado' => true, 
        ]);

        $usuario->save();

        $usuarioRol = new UsuarioRol([
            'usuario_id' => $usuario->usuarioId,
            'rol_id' => $rol->rolId,
        ]);

        $usuarioRol->save();

        return response()->json(['message' => 'Usuario creado con éxito'], 201);
    }

    public function listarProductos($nombrePersona)
{
    $usuario = Usuario::whereHas('persona', function ($query) use ($nombrePersona) {
        $query->where('nombre', $nombrePersona);
    })->first();

    if (!$usuario) {
        return response()->json(['error' => 'Cliente no encontrado'], 404);
    }

    $pedidos = $usuario->pedidos;

    $productosPedidos = [];

    foreach ($pedidos as $pedido) {
        foreach ($pedido->detalles as $detallePedido) {
            $producto = $detallePedido->producto;
            $productosPedidos[] = $producto;
        }
    }

    return response()->json(['productos_pedidos' => $productosPedidos], 200);
}


    
    
}
