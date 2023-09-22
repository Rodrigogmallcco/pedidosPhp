<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    use HasFactory;
    protected $table = 'usuario_rol'; 

    protected $primaryKey = 'usuarioRolId'; 
    public $timestamps = false;

    protected $fillable = [
        'usuario_id', 
        'rol_id',
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
