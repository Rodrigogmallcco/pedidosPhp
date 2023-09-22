<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\Persona;
use App\Models\UsuarioRol;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

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

        $personaId = $persona->persona_id;
        Log::info('Valor de personaId: ' . $personaId);

        $nombreDelRol = $request->input('nombreDelRol');
        $rol = Rol::where('nombreDelRol', $nombreDelRol)->first();

        if (!$rol) {
            return response()->json(['error' => 'El rol especificado no existe'], 404);
        }

        $usuario = new Usuario([
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'correoElectronico' => $request->input('correoElectronico'),
            'estado' => true, 
            'persona_id' => $personaId,
        ]);

        $usuario->save();

        $usuarioRol = new UsuarioRol([
            'usuario_id' => $usuario->usuario_id,
            'rol_id' => $rol->rol_id,
        ]);

        $usuarioRol->save();

        return response()->json(['message' => 'Usuario creado con éxito'], 201);
    }

    public function listarProductos(Request $request)
    {
        $nombrePersona = $request->input('nombrePersona');
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');

        $resultados = DB::select('CALL pedidos(?, ?, ?)', [$nombrePersona, $fechaInicio, $fechaFin]);

        Log::debug('Consulta ejecutada:', ['query' => 'CALL pedidos(?, ?, ?)', 'params' => [$nombrePersona, $fechaInicio, $fechaFin]]);

        // Registra los resultados en el archivo de registro
        Log::debug('Resultados de la consulta:', ['data' => $resultados]);
        // Devuelve los resultados como parte de la respuesta JSON
        return response()->json(['message' => 'Procedimiento almacenado ejecutado con éxito', 'data' => $resultados], 200);
    }    
}
