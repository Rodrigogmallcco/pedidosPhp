<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    use HasFactory;
    protected $table = 'usuario_rol'; // Nombre de la tabla en la base de datos

    protected $primaryKey = 'usuarioRolId'; // Clave primaria de la tabla
    public $timestamps = false;

    protected $fillable = [
        'usuario_id', // Nombre de la columna en la tabla de la relación con Usuario
        'rol_id', // Nombre de la columna en la tabla de la relación con Rol
    ];

    // Relación con la entidad Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuarioId');
    }

    // Relación con la entidad Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'rolId');
    }
}
