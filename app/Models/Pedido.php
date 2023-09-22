<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $table = 'pedido'; 

    protected $primaryKey = 'pedido_id'; 

    public $timestamps = false; 

    protected $fillable = [
        'fecha',
        'total_p',
        'usuario_id', 
    ];

    public function usuario()
    {
        return $this->belongsTo('App\Models\Usuario', 'usuario_id', 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'detalle_id', 'detalle_id');
    }
}
