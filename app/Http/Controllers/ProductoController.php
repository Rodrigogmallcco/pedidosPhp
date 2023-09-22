<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all(); 

        return response()->json($productos);
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'stockp' => 'required|integer',
        ]);

        $validatedData['codigo_uuid'] = Str::uuid();

        $producto = Producto::create($validatedData);

        return response()->json(['message' => 'Producto creado correctamente', 'data' => $producto], 201);
    }
}
