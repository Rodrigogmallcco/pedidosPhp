<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    
    protected $table = 'usuario'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'usuarioId'; // Clave primaria de la tabla

    protected $fillable = [
        'personaId', // Nombre de la columna en la tabla de la relación con Persona
        'username',
        'password',
        'correoElectronico',
        'estado',
    ];

    // Relación con la entidad Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'personaId', 'personaId');
    }

    // Relación con la entidad UsuarioRol
    public function usuarioRol()
    {
        return $this->hasMany(UsuarioRol::class, 'usuario_id', 'usuarioId');
    }
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
