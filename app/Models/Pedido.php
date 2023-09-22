<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $table = 'pedido'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'pedido_id'; // Clave primaria de la tabla

    public $timestamps = false; // Si no necesitas las marcas de tiempo created_at y updated_at

    protected $fillable = [
        'fecha',
        'totalP',
        'usuario_id', // Nombre de la columna en la tabla de la relación con Usuario
    ];

    // Relación con la entidad Usuario
    public function usuario()
    {
        return $this->belongsTo('App\Models\Usuario', 'usuario_id', 'usuarioId');
    }

    // Relación con la entidad DetallePedido
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
}
