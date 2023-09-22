<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
class pedidoController extends Controller
{
    public function nuevoPedido(Request $request)
    {
        $usuario = Usuario::find($request->usuarioId);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $pedido = new Pedido([
            'fecha' => Carbon::now()->toDateString(),
        ]);
        $pedido->save();

        $totalPedido = 0;

        foreach ($request->detalles as $detalleRequest) {
            $producto = Producto::find($detalleRequest['productoId']);

            if (!$producto) {
                return response()->json(['error' => 'Producto no encontrado'], 404);
            }

            $detallePedido = new DetallePedido([
                'stock' => $detalleRequest['cantidad'],
                'precioUnitario' => $producto->precio,
                'estado' => 'Pendiente',
                'totalD' => $producto->precio * $detalleRequest['cantidad'],
                'pedido_pedido_id' => $pedido->id,
            
            ]);

            $detallePedido->pedido()->associate($pedido);
            $detallePedido->producto()->associate($producto);

            $detallePedido->save();

            $pedido->detalles()->save($detallePedido);


            $totalPedido += $detallePedido->totalD;
        }

        $pedido->totalP = $totalPedido;
        $usuario->pedidos()->save($pedido);

        return response()->json(['message' => 'Pedido creado con éxito', 'pedido' => $pedido], 201);
    }

    public function actualizarEstado(Request $request, $detalle_id)
{
    try {
        $detallePedido = DetallePedido::find($detalle_id);

        if (!$detallePedido) {
            return response()->json(['error' => 'Detalle de pedido no encontrado'], 404);
        }

        $request->validate([
            'estado' => 'required|string',
            /* 'archivo'=> 'file', */
            
        ]);

        $detallePedido->estado = $request->input('estado');
        
        $detallePedido->save();
        return response()->json(['message' => 'Estado actualizado con éxito', 'detallePedido' => $detallePedido]);
    
    } catch (\Exception $e) {
        Log::error('Error al actualizar estado y archivo: ' . $e->getMessage());
        return response()->json(['error' => 'Ha ocurrido un error al actualizar estado y archivo'], 500);
    }
}

public function subirArchivo(Request $request, $detalle_id)
    {
        $detallePedido = DetallePedido::find($detalle_id);

        if (!$detallePedido) {
            return response()->json(['error' => 'Detalle de pedido no encontrado'], 404);
        }

    $request->validate([
        'archivoAdjunto' => 'file',
    ]);

   
    if ($request->hasFile('archivoAdjunto')) {
        $archivo = $request->file('archivoAdjunto');

        if ($archivo->isValid()) {
            $detallePedido->archivoAdjunto = base64_encode(file_get_contents($archivo));
            $detallePedido->save();

            return redirect()->back()->with('success', 'Archivo subido y guardado con éxito.');
        } else {
            return redirect()->back()->with('error', 'El archivo no es válido.');
        }
    } else {
        return redirect()->back()->with('error', 'No se proporcionó ningún archivo.');
    }
    }
}