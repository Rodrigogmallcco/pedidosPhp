<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;
    protected $table = 'detalle_pedido'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'detalle_id'; // Clave primaria de la tabla

    public $timestamps = false; 

    protected $fillable = [
        'stock',
        'precio_unitario',
        'total_d',
        'estado',
        'pedido_id', 
        'producto_id',
        'archivo_adjunto',
    ];

    // Relación con la entidad Pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id', 'pedido_id');
    }

    // Relación con la entidad Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class,'producto_id','producto_id');
    }
}
