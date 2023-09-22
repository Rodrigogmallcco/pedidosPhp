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
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
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
            'usuario_id'=> $usuario->usuario_id,
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
                'precio_unitario' => $producto->precio,
                'estado' => 'Pendiente',
                'total_d' => $producto->precio * $detalleRequest['cantidad'],
                'pedido_id' => $pedido->id,
            
            ]);

            $detallePedido->pedido()->associate($pedido);
            $detallePedido->producto()->associate($producto);

            $detallePedido->save();

            


            $totalPedido += $detallePedido->total_d;
        }

        $pedido->total_p = $totalPedido;

        $usuario->pedidos()->save($pedido);

        return response()->json(['message' => 'Pedido creado con éxito', 'pedido' => $pedido], 201);
    }


    public function actualizarEstado(Request $request, $pedido_id)
{
    try {
        $detallePedidos = DetallePedido::where('pedido_id', $pedido_id)->get();

        if ($detallePedidos->isEmpty()) {
            return response()->json(['error' => 'Detalle de pedido no encontrado'], 404);
        }

        $request->validate([
            'estado' => 'required|string',
            
        ]);

        foreach ($detallePedidos as $detallePedido) {
            $detallePedido->estado = $request->input('estado');
            $detallePedido->save();
        }
        return response()->json(['message' => 'Estado actualizado con éxito', 'detallePedido' => $detallePedido]);
    
    } catch (\Exception $e) {
        Log::error('Error al actualizar estado' . $e->getMessage());
        return response()->json(['error' => 'Ha ocurrido un error al actualizar estado '], 500);
    }
}

public function actualizarDetallePedido(Request $request, $detalle_id)
{
    try {
        $detallePedido = DetallePedido::find($detalle_id);

        if (!$detallePedido) {
            return response()->json(['error' => 'Detalle de pedido no encontrado'], 404);
        }

        $request->validate([
            'estado' => 'required|string',
        ]);

        $detallePedido->estado = $request->input('estado');
        $detallePedido->save();

        return response()->json(['message' => 'Estado del detalle de pedido actualizado con éxito', 'detallePedido' => $detallePedido]);

    } catch (\Exception $e) {
        Log::error('Error al actualizar el estado del detalle de pedido: ' . $e->getMessage());
        return response()->json(['error' => 'Ha ocurrido un error al actualizar el estado del detalle de pedido'], 500);
    }
}


public function subirArchivo(Request $request, $detalle_id){
    try{
    $client = new Google_Client();
    $client->setAuthConfig(base_path('datoss/credenciales.json'));
    $client->addScope(Google_Service_Drive::DRIVE_FILE);

    $service = new Google_Service_Drive($client);

    $rutaArchivo=$request->file('archivo')->getPathname();
    $nombreArchivo = $request->file('archivo')->getClientOriginalName();
    $archivo = new Google_Service_Drive_DriveFile();
    $archivo->setName($nombreArchivo);

    $idCarpetaDestino = '1-FuuitMMziyzVAYvkOjJynvvZ1ADS-Vp';
    $archivo->setParents([$idCarpetaDestino]);

    $resultado = $service->files->create($archivo, [
        'data' => file_get_contents($rutaArchivo),
        'mimeType' => $request->file('archivo')->getMimeType(),
    ]);

    $archivoId = $resultado->getId();
    $archivo = $service->files->get($archivoId, ['fields' => 'webViewLink']);


    //dd($resultado);

    $enlaceArchivo = $archivo->getWebViewLink();
    //ded($enlaceArchivo);

    $detallePedido = DetallePedido::find($detalle_id);
    if (!$detallePedido) {
        return response()->json(['error' => 'Detalle de pedido no encontrado'], 404);
    }
    $detallePedido->archivo_adjunto = $enlaceArchivo;
    $detallePedido->save();
    return response()->json(['message' => 'Archivo subido con éxito', 'enlaceArchivo' => $enlaceArchivo]);

} catch (\Exception $e) {
    Log::error('Error al subir el archivo a Google Drive: ' . $e->getMessage());
    return response()->json(['error' => 'Ha ocurrido un error al subir el archivo a Google Drive'], 500);
}
}
    
}