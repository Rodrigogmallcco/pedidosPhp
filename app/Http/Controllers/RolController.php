<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rol;

class RolController extends Controller
{
    public function index()
    {
        $rol = Rol::all(); // Obtener todos los productos desde la base de datos

        return response()->json($rol);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombreDelRol' => 'required|string|unique:rol,nombreDelRol',
        ]);

        $rol = Rol::create([
            'nombreDelRol' => $request->input('nombreDelRol'),
        ]);
        return response()->json(['rol' => $rol], 201);
    }
}
